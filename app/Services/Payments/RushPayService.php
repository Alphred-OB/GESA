<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

class RushPayService
{
    /**
     * Initialize a payment transaction.
     */
    public function initializeTransaction(array $payload): array
    {
        return $this->post('/payments/create', $payload);
    }

    /**
     * Generate a widget session for a payment.
     */
    public function generateWidgetSession(string $reference): array
    {
        return $this->post('/payments/widget-session', [
            'payment_reference' => $reference
        ]);
    }

    /**
     * Verify a payment transaction.
     */
    public function verifyTransaction(string $reference): array
    {
        return $this->get('/payments/' . urlencode($reference));
    }

    /**
     * Make a GET request to RushPay.
     */
    protected function get(string $path): array
    {
        try {
            $response = $this->client()->get($this->endpoint($path));
            
            if ($response->failed()) {
                $this->handleErrorResponse($response);
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('RushPay GET request failed: ' . $e->getMessage(), ['path' => $path]);
            throw $e;
        }
    }

    /**
     * Make a POST request to RushPay.
     */
    protected function post(string $path, array $payload): array
    {
        try {
            $response = $this->client()->post($this->endpoint($path), $payload);
            
            if ($response->failed()) {
                $this->handleErrorResponse($response);
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('RushPay POST request failed: ' . $e->getMessage(), ['path' => $path, 'payload' => $payload]);
            throw $e;
        }
    }

    /**
     * Get the HTTP client for RushPay with Bearer Token.
     */
    protected function client()
    {
        $token = $this->getAccessToken();

        return Http::withToken($token)
            ->acceptJson()
            ->timeout(15);
    }

    /**
     * Retrieve or generate an OAuth access token.
     */
    protected function getAccessToken(): string
    {
        return Cache::remember('rushpay_access_token', now()->addHours(11), function () {
            $clientId = Config::get('rushpay.client_key');
            $clientSecret = Config::get('rushpay.client_secret');

            if (!$clientId || !$clientSecret) {
                throw new \RuntimeException('RushPay OAuth credentials (Client Key/Secret) are not configured.');
            }

            $response = Http::post($this->endpoint('/auth/login'), [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'client_credentials'
            ]);

            if ($response->failed()) {
                Log::error('RushPay OAuth Authentication failed', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                    'debug_client_id_used' => substr($clientId, 0, 15) . '...',
                ]);
                throw new \RuntimeException('Unable to authenticate with RushPay.');
            }

            $token = Arr::get($response->json(), 'data.access_token') ?? Arr::get($response->json(), 'access_token');

            if (!$token) {
                throw new \RuntimeException('RushPay authentication response did not contain an access token.');
            }

            return $token;
        });
    }

    /**
     * Handle error responses from RushPay.
     */
    protected function handleErrorResponse($response)
    {
        $data = $response->json();
        $message = Arr::get($data, 'message', 'RushPay request failed.');
        throw new \RuntimeException($message);
    }

    /**
     * Get the full endpoint URL.
     */
    protected function endpoint(string $path): string
    {
        $baseUrl = rtrim(Config::get('rushpay.base_url', 'https://api.rushpay.cash/v1'), '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }
}
