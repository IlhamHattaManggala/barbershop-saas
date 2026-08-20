<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PakasirService
{
    protected string $apiKey;

    protected string $merchantSlug;

    protected bool $isActive;

    public function __construct()
    {
        $this->apiKey = SiteSetting::getEncrypted('pakasir_api_key', 'Vbt9gVU18YnB2fq316y9XoKnhbFep4vr') ?? 'Vbt9gVU18YnB2fq316y9XoKnhbFep4vr';
        $this->merchantSlug = SiteSetting::get('pakasir_slug', 'babershopsaas') ?? 'babershopsaas';
        $this->isActive = (SiteSetting::get('pakasir_is_active', '1') === '1');
    }

    public function getMerchantSlug(): string
    {
        return $this->merchantSlug;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey) && ! empty($this->merchantSlug) && $this->isActive;
    }

    /**
     * Generate official Pakasir Checkout Payment Link (Panduan Integrasi Pakasir Section B).
     * Format URL: https://app.pakasir.com/pay/{slug}/{amount}?order_id={order_id}&redirect={redirect_url}
     */
    public function createPaymentUrl(int $amount, string $orderId, ?string $redirectUrl = null): string
    {
        $amountInt = (int) $amount;
        $url = "https://app.pakasir.com/pay/{$this->merchantSlug}/{$amountInt}?order_id={$orderId}";

        if ($redirectUrl) {
            $url .= '&redirect='.urlencode($redirectUrl);
        }

        return $url;
    }

    /**
     * Transaction Detail API (Panduan Integrasi Pakasir Section E)
     * GET https://app.pakasir.com/api/transactiondetail?project={slug}&amount={amount}&order_id={order_id}&api_key={api_key}
     */
    public function checkTransactionDetail(string $orderId, int $amount): array
    {
        try {
            $response = Http::get('https://app.pakasir.com/api/transactiondetail', [
                'project' => $this->merchantSlug,
                'amount' => $amount,
                'order_id' => $orderId,
                'api_key' => $this->apiKey,
            ]);

            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('Pakasir checkTransactionDetail error: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Sandbox Payment Simulation API (Panduan Integrasi Pakasir Section C.4)
     * POST https://app.pakasir.com/api/paymentsimulation
     */
    public function simulatePayment(string $orderId, int $amount): array
    {
        try {
            $response = Http::post('https://app.pakasir.com/api/paymentsimulation', [
                'project' => $this->merchantSlug,
                'order_id' => $orderId,
                'amount' => $amount,
                'api_key' => $this->apiKey,
            ]);

            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('Pakasir simulatePayment error: '.$e->getMessage());

            return [];
        }
    }
}
