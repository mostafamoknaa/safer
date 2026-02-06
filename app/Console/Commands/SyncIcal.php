<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\IcalUrl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncIcal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ical:sync {--url= : Sync specific URL only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync external iCal feeds to prevent double bookings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = IcalUrl::query();

        if ($this->option('url')) {
            $query->where('id', $this->option('url'));
        }

        $icalUrls = $query->get();

        if ($icalUrls->isEmpty()) {
            $this->info('No iCal URLs found to sync.');
            return 0;
        }

        $this->info('Starting iCal synchronization...');

        foreach ($icalUrls as $icalUrl) {
            $this->info("Syncing: {$icalUrl->name} ({$icalUrl->url})");

            try {
                $response = Http::timeout(30)->get($icalUrl->url);

                if (!$response->successful()) {
                    throw new \Exception('Failed to fetch iCal feed: ' . $response->status());
                }

                $icalContent = $response->body();
                $events = $this->parseIcal($icalContent);

                $this->info("Found " . count($events) . " events");

                foreach ($events as $event) {
                    $this->syncEvent($icalUrl, $event);
                }

                $icalUrl->update([
                    'last_sync_at' => now(),
                    'sync_status' => 'success',
                    'sync_message' => 'Synced ' . count($events) . ' events successfully',
                ]);

                $this->info("✓ Synced successfully");

            } catch (\Exception $e) {
                $this->error("✗ Failed: " . $e->getMessage());

                $icalUrl->update([
                    'sync_status' => 'failed',
                    'sync_message' => $e->getMessage(),
                ]);
            }
        }

        $this->info('Synchronization complete!');
        return 0;
    }

    /**
     * Parse iCal content and extract events.
     */
    private function parseIcal(string $content): array
    {
        $events = [];
        $lines = explode("\n", str_replace("\r\n", "\n", $content));
        $currentEvent = null;

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === 'BEGIN:VEVENT') {
                $currentEvent = [];
            } elseif ($line === 'END:VEVENT' && $currentEvent !== null) {
                if (isset($currentEvent['DTSTART']) && isset($currentEvent['DTEND'])) {
                    $events[] = $currentEvent;
                }
                $currentEvent = null;
            } elseif ($currentEvent !== null && strpos($line, ':') !== false) {
                [$key, $value] = explode(':', $line, 2);
                // Handle properties with parameters (e.g., DTSTART;VALUE=DATE:20260127)
                $key = explode(';', $key)[0];
                $currentEvent[$key] = $value;
            }
        }

        return $events;
    }

    /**
     * Sync a single event.
     */
    private function syncEvent(IcalUrl $icalUrl, array $event): void
    {
        $uid = $event['UID'] ?? null;
        $summary = $event['SUMMARY'] ?? 'External Booking';
        $dtstart = $event['DTSTART'] ?? null;
        $dtend = $event['DTEND'] ?? null;

        if (!$uid || !$dtstart || !$dtend) {
            return;
        }

        // Parse dates (handle both DATE and DATETIME formats)
        $checkInDate = $this->parseIcalDate($dtstart);
        $checkOutDate = $this->parseIcalDate($dtend);

        if (!$checkInDate || !$checkOutDate) {
            return;
        }

        // Check if booking already exists
        $existingBooking = Booking::where('external_event_uid', $uid)
            ->where('ical_url_id', $icalUrl->id)
            ->first();

        if ($existingBooking) {
            // Update existing booking
            $existingBooking->update([
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
            ]);
        } else {
            // Create new booking
            Booking::create([
                'hotel_id' => $icalUrl->hotelRoom->hotel_id,
                'room_id' => $icalUrl->hotel_room_id,
                'user_id' => 1, // System user or admin
                'ical_url_id' => $icalUrl->id,
                'external_event_uid' => $uid,
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
                'guests_count' => 1,
                'adults_count' => 1,
                'young_count' => 0,
                'rooms_count' => 1,
                'price_per_night' => 0,
                'total_price' => 0,
                'status' => 'confirmed',
                'notes' => "Imported from iCal: {$icalUrl->name}\nSummary: {$summary}",
            ]);
        }
    }

    /**
     * Parse iCal date format.
     */
    private function parseIcalDate(string $date): ?\Carbon\Carbon
    {
        try {
            // Remove VALUE=DATE: prefix if present
            $date = preg_replace('/^[^:]+:/', '', $date);

            // Handle DATE format (YYYYMMDD)
            if (preg_match('/^(\d{8})$/', $date, $matches)) {
                return \Carbon\Carbon::createFromFormat('Ymd', $matches[1]);
            }

            // Handle DATETIME format (YYYYMMDDTHHmmssZ)
            if (preg_match('/^(\d{8})T(\d{6})Z?$/', $date, $matches)) {
                return \Carbon\Carbon::createFromFormat('Ymd\THis', $matches[1] . 'T' . $matches[2]);
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
