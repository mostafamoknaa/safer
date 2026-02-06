<?php

namespace App\Services\Web;

use App\Services\FawaterkService;
use Illuminate\Support\Facades\Http;
use Exception;

class WebFawaterkService extends FawaterkService
{
    /**
     * Execute a payment to get the redirection URL for Web.
     *
     * @param array $data
     * @return array
     */
    public function executePayment(array $data)
    {
        $payload = array_merge([
            'currency' => 'EGP',
            'cartTotal' => $data['amount'],
            'customer' => [
                'first_name' => $data['user_name'],
                'last_name' => $data['user_name'],
                'email' => $data['user_email'],
                'phone' => $data['user_phone'],
            ],
            'redirectionUrls' => [
                'successUrl' => route('web.payments.callback.success'),
                'failUrl' => route('web.payments.callback.fail'),
                'pendingUrl' => route('web.payments.callback.pending'),
            ],
            'cartItems' => [
                [
                    'name' => $data['description'] ?? 'Booking Payment',
                    'price' => $data['amount'],
                    'quantity' => '1',
                ]
            ]
        ], $data['additional_payload'] ?? []);

        $response = Http::withToken($this->apiToken)
            ->post("{$this->baseUrl}/executePayment", $payload);

        if (!$response->successful()) {
            throw new Exception('Failed to execute payment: ' . $response->body());
        }

        return $response->json();
    }
}
