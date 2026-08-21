<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Tenant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send automatic WhatsApp Booking Confirmation to customer.
     */
    public function sendBookingConfirmation(Reservation $reservation): bool
    {
        $tenant = $reservation->tenant ?? Tenant::find($reservation->tenant_id);
        if (! $tenant) {
            return false;
        }

        $waSettings = $tenant->wa_settings ?? [];
        $enabled = isset($waSettings['enabled']) ? (bool) $waSettings['enabled'] : false;

        if (! $enabled) {
            Log::info("WhatsAppService: WA notification disabled for Tenant #{$tenant->id}");

            return false;
        }

        $gatewayUrl = $waSettings['gateway_url'] ?? 'http://localhost:3000/send-message';
        $apiKey = $waSettings['api_key'] ?? '';

        $phone = $this->formatPhoneNumber($reservation->customer_phone);
        $dateFormatted = date('d/m/Y', strtotime($reservation->reservation_date));
        $timeFormatted = substr($reservation->start_time, 0, 5);

        $serviceName = $reservation->service?->name ?? 'Pangkas Rambut';
        $barberName = $reservation->barber?->name ?? 'Bebas (Siapa Saja Ready)';
        $tenantName = $tenant->name ?? 'Barbershop';

        // Construct clean message without emojis
        $message = "*KONFIRMASI RESERVASI PANGKAS RAMBUT*\n\n"
            ."Outlet: {$tenantName}\n"
            ."Kode Booking: {$reservation->reservation_code}\n\n"
            ."Nama Pelanggan: {$reservation->customer_name}\n"
            ."Layanan: {$serviceName}\n"
            ."Barber Specialist: {$barberName}\n"
            ."Tanggal & Jam: {$dateFormatted} (Jam {$timeFormatted} WIB)\n\n"
            ."Status: Terkonfirmasi\n\n"
            .'Harap datang 5-10 menit sebelum jam pangkas yang dijadwalkan. Terima kasih!';

        return $this->dispatchPayload($gatewayUrl, $phone, $message, $apiKey);
    }

    /**
     * Test sending custom WhatsApp message to verify Baileys/Gateway connection.
     */
    public function sendCustomMessage(string $phone, string $message, ?array $settings = null): bool
    {
        $gatewayUrl = $settings['gateway_url'] ?? 'http://localhost:3000/send-message';
        $apiKey = $settings['api_key'] ?? '';

        $formattedPhone = $this->formatPhoneNumber($phone);

        return $this->dispatchPayload($gatewayUrl, $formattedPhone, $message, $apiKey);
    }

    /**
     * Dispatch HTTP POST payload to WhatsApp Gateway endpoint.
     */
    private function dispatchPayload(string $gatewayUrl, string $phone, string $message, string $apiKey = ''): bool
    {
        try {
            $headers = ['Content-Type' => 'application/json'];
            if (! empty($apiKey)) {
                $headers['Authorization'] = 'Bearer '.$apiKey;
                $headers['x-api-key'] = $apiKey;
            }

            // Standard Baileys & Multi-Gateway Payload
            $payload = [
                'number' => $phone,
                'phone' => $phone,
                'target' => $phone,
                'receiver' => $phone,
                'message' => $message,
                'text' => $message,
                'api_key' => $apiKey,
            ];

            $response = Http::withHeaders($headers)
                ->timeout(5)
                ->post($gatewayUrl, $payload);

            if ($response->successful()) {
                Log::info("WhatsAppService: Successfully dispatched WA message to {$phone}");

                return true;
            }

            Log::warning("WhatsAppService: Gateway returned status {$response->status()} for {$phone}");

            return false;
        } catch (\Throwable $e) {
            Log::error('WhatsAppService Error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Fetch live Baileys WhatsApp Web QR Code and Connection Status.
     */
    public function fetchBaileysStatusAndQr(string $gatewayUrl, string $apiKey = ''): array
    {
        try {
            $parsedUrl = parse_url($gatewayUrl);
            $scheme = $parsedUrl['scheme'] ?? 'http';
            $host = $parsedUrl['host'] ?? 'localhost';
            $port = isset($parsedUrl['port']) ? ':'.$parsedUrl['port'] : '';
            $baseUrl = "{$scheme}://{$host}{$port}";

            $headers = ['Content-Type' => 'application/json'];
            if (! empty($apiKey)) {
                $headers['Authorization'] = 'Bearer '.$apiKey;
                $headers['x-api-key'] = $apiKey;
            }

            // Try endpoints: /qr, /status, /session/qr, or direct gatewayUrl
            $endpoints = ["{$baseUrl}/qr", "{$baseUrl}/status", "{$baseUrl}/session/qr", $gatewayUrl];

            foreach ($endpoints as $ep) {
                try {
                    $res = Http::withHeaders($headers)->timeout(3)->get($ep);
                    if ($res->successful()) {
                        $data = $res->json();

                        $status = strtolower($data['status'] ?? $data['connection'] ?? '');
                        if (in_array($status, ['connected', 'authenticated', 'open', 'ready']) || ! empty($data['user']) || ! empty($data['phone'])) {
                            $user = $data['user'] ?? $data['phone'] ?? $data['pushName'] ?? 'Terkoneksi';

                            return [
                                'status' => 'connected',
                                'user' => $user,
                                'qr' => null,
                                'message' => "WhatsApp Terhubung (Nomor/Sesi: {$user})",
                            ];
                        }

                        $qr = $data['qr'] ?? $data['qrcode'] ?? $data['qr_code'] ?? $data['image'] ?? null;
                        if ($qr) {
                            return [
                                'status' => 'qr_ready',
                                'qr' => $qr,
                                'user' => null,
                                'message' => 'Silakan scan Barcode QR WhatsApp ini dengan HP Anda.',
                            ];
                        }
                    }
                } catch (\Throwable $e) {
                    continue;
                }
            }

            return [
                'status' => 'offline',
                'qr' => null,
                'user' => null,
                'message' => 'Gagal terhubung ke Server Baileys WA Gateway ('.$baseUrl.'). Pastikan service Node.js Baileys sedang berjalan.',
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'offline',
                'qr' => null,
                'user' => null,
                'message' => 'Error: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Format Indonesian phone numbers (e.g. 08123456789 -> 628123456789).
     */
    public function formatPhoneNumber(string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($clean, '0')) {
            return '62'.substr($clean, 1);
        }

        if (str_starts_with($clean, '8')) {
            return '62'.$clean;
        }

        return $clean;
    }
}
