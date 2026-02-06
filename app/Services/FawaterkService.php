<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class FawaterkService
{
    protected string $baseUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->baseUrl = config('services.fawaterk.base_url');
        $this->apiToken = config('services.fawaterk.api_token');

        if (empty($this->apiToken)) {
            throw new Exception('Fawaterk API token is not configured.');
        }
    }

    /**
     * Get available payment methods.
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $response = Http::withToken($this->apiToken)
            ->get("{$this->baseUrl}/getPaymentMethods");

        if (!$response->successful()) {
            throw new Exception('Failed to fetch payment methods: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Execute a payment to get the redirection URL.
     *
     * @param array $data
     * @return array
     */
    public function executePayment(array $data)
    {
        // Require minimal data: amount, payment_method_id, customer details
        $payload = array_merge([
            'currency' => 'EGP', // Default currency
            'cartTotal' => $data['amount'],
            'customer' => [
                'first_name' => $data['user_name'],
                'last_name' => $data['user_name'], // Placeholder if needed
                'email' => $data['user_email'],
                'phone' => $data['user_phone'],
            ],
            'redirectionUrls' => [
                'successUrl' => route('api.payments.callback.success'), // Define this route
                'failUrl' => route('api.payments.callback.fail'),    // Define this route
                'pendingUrl' => route('api.payments.callback.pending'), // Define this route
            ],
            'cartItems' => [
                [
                    'name' => 'Booking Payment',
                    'price' => $data['amount'],
                    'quantity' => 1,
                ]
            ],
            'sendEmail' => true,
            'sendSMS' => true,
        ], $data);

        // Remove convenience fields that are not part of API payload if any
        unset($payload['amount'], $payload['user_name'], $payload['user_email'], $payload['user_phone']);

        $response = Http::withToken($this->apiToken)
            ->post("{$this->baseUrl}/invoiceInitPay", $payload);

        if (!$response->successful()) {
            Log::error('Fawaterk Error: ' . $response->body());
            throw new Exception('Failed to execute payment: ' . $response->body());
        }

        Log::info('Fawaterk Response: ', $response->json());

        return $response->json();
    }

    /**
     * Check the status of an invoice/payment.
     *
     * @param string $invoiceId
     * @return array
     */
    public function checkInvoiceStatus(string $invoiceId)
    {
        $url = "{$this->baseUrl}/getInvoiceId/{$invoiceId}";
        Log::info("Fawaterk Checking Status: $url");

        $response = Http::withToken($this->apiToken)
            ->get($url);

        if (!$response->successful()) {
            Log::error('Fawaterk Status Error: ' . $response->body());
            throw new Exception('Failed to check invoice status: ' . $response->body());
        }

        return $response->json();
    }
}
