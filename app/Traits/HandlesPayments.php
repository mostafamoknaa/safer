<?php

namespace App\Traits;

use App\Models\Payment;
use App\Services\FawaterkService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

trait HandlesPayments
{
    /**
     * Initiate a direct payment for a payable model.
     *
     * @param mixed $payable
     * @param float $amount
     * @param string $method
     * @param int|null $methodId
     * @param string|null $notes
     * @return array|null
     */
    protected function initiatePayment($payable, float $amount, string $method, ?int $methodId = null, ?string $notes = null)
    {
        // 1. Create Payment Record
        $payment = Payment::create([
            'payable_type' => get_class($payable),
            'payable_id' => $payable->id,
            'booking_id' => ($payable instanceof \App\Models\Booking) ? $payable->id : null,
            'amount' => $amount,
            'payment_method' => $method,
            'status' => 'pending',
            'notes' => $notes,
        ]);

        $responseData = [
            'payment_id' => $payment->id,
            'amount' => (float) $payment->amount,
        ];

        // 2. Handle Online Payment via Fawaterk
        if ($method === 'online') {
            try {
                $fawaterkService = app(FawaterkService::class);
                
                $type = class_basename($payable);
                $reference = match($type) {
                    'Booking' => $payable->booking_reference,
                    'ServiceRequest' => $payable->request_reference,
                    'EventTicket' => $payable->ticket_reference,
                    default => "#{$payable->id}"
                };

                $description = "Payment for {$type} #{$reference}";

                $fawaterkData = [
                    'amount' => $amount,
                    'user_name' => Auth::user()->name,
                    'user_email' => Auth::user()->email,
                    'user_phone' => Auth::user()->phone ?? '01000000000',
                    'payment_method_id' => $methodId ?? 3, // Default to a common method if not provided
                    'description' => $description,
                ];

                $result = $fawaterkService->executePayment($fawaterkData);

                if (isset($result['data']['payment_data']['redirectTo'])) {
                    $payment->update([
                        'transaction_id' => $result['data']['invoice_id'] ?? null,
                        'notes' => ($payment->notes ? $payment->notes . "\n" : "") . 'Fawaterk Invoice ID: ' . ($result['data']['invoice_id'] ?? 'N/A')
                    ]);
                    
                    $responseData['payment_url'] = $result['data']['payment_data']['redirectTo'];
                } elseif (isset($result['data']['payment_data']['fawryCode'])) {
                    $payment->update([
                        'transaction_id' => $result['data']['invoice_id'] ?? null,
                        'notes' => ($payment->notes ? $payment->notes . "\n" : "") . 'Fawaterk Invoice ID: ' . ($result['data']['invoice_id'] ?? 'N/A') . "\nFawry Code: " . $result['data']['payment_data']['fawryCode']
                    ]);
                    
                    $responseData['fawry_code'] = $result['data']['payment_data']['fawryCode'];
                    $responseData['expire_date'] = $result['data']['payment_data']['expireDate'];
                } else {
                     $payment->update([
                        'transaction_id' => $result['data']['invoice_id'] ?? null,
                    ]);
                    $responseData['payment_data'] = $result['data']['payment_data'] ?? [];
                }

            } catch (\Exception $e) {
                Log::error("Direct Payment Initiation Error: " . $e->getMessage());
                $payment->update(['status' => 'failed', 'notes' => 'Failed to init online payment: ' . $e->getMessage()]);
                $responseData['payment_error'] = $e->getMessage();
            }
        }

        return $responseData;
    }
}
