<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCapacityTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_fails_if_people_count_exceeds_max_people()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create(['is_active' => true]);
        $room = HotelRoom::factory()->create([
            'hotel_id' => $hotel->id,
            'max_people' => 2,
            'is_active' => true,
            'price_per_night' => 100
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/bookings/create', [
            'hotel_id' => $hotel->id,
            'room_ids' => [$room->id],
            'check_in_date' => now()->addDay()->format('Y-m-d'),
            'check_out_date' => now()->addDays(2)->format('Y-m-d'),
            'adults_count' => 2,
            'young_count' => 1, // Total 3, exceeds max_people 2
        ]);

        $response->assertStatus(400);
        $this->assertStringContainsString('يتجاوز السعة القصوى', $response->json('message'));
    }

    public function test_tiered_pricing_calculation()
    {
        $user = User::factory()->create();
        $hotel = Hotel::factory()->create(['is_active' => true]);
        $room = HotelRoom::factory()->create([
            'hotel_id' => $hotel->id,
            'max_people' => 4,
            'is_active' => true,
            'price_per_night' => 100
        ]);

        // 1 adult (100) + 1 young (50) = 150 per night
        // 1 night = 150 total

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/bookings/create', [
            'hotel_id' => $hotel->id,
            'room_ids' => [$room->id],
            'check_in_date' => now()->addDay()->format('Y-m-d'),
            'check_out_date' => now()->addDays(2)->format('Y-m-d'),
            'adults_count' => 1,
            'young_count' => 1,
        ]);

        $response->assertStatus(201);
        $this->assertEquals(150, $response->json('data.final_price'));
        
        $booking = Booking::first();
        $this->assertEquals(1, $booking->adults_count);
        $this->assertEquals(1, $booking->young_count);
        $this->assertEquals(2, $booking->guests_count);
    }

    public function test_hotel_filter_by_people_count()
    {
        $hotel1 = Hotel::factory()->create(['is_active' => true]);
        HotelRoom::factory()->create([
            'hotel_id' => $hotel1->id,
            'max_people' => 2,
            'is_active' => true
        ]);

        $hotel2 = Hotel::factory()->create(['is_active' => true]);
        HotelRoom::factory()->create([
            'hotel_id' => $hotel2->id,
            'max_people' => 4,
            'is_active' => true
        ]);

        // Search for 3 people
        $response = $this->getJson('/api/hotels-filter?people_count=3');

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertCount(1, $data);
        $this->assertEquals($hotel2->id, $data[0]['id']);
    }
}
