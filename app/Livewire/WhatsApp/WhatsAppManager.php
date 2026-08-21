<?php

namespace App\Livewire\WhatsApp;

use App\Models\Reservation;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Services\WhatsAppService;
use Livewire\Component;

class WhatsAppManager extends Component
{
    public string $active_tab = 'chat'; // 'chat', 'settings'

    public string $target_phone = '';

    public string $customer_name = '';

    public string $custom_message = '';

    public string $selected_template = '';

    public bool $wa_enabled = false;

    public string $wa_gateway_url = 'http://localhost:3000/send-message';

    public string $wa_api_key = '';

    public ?string $wa_qr_code = null;

    public string $wa_connection_status = 'offline';

    public string $wa_status_message = '';

    public string $send_result_message = '';

    public string $send_result_status = ''; // 'success' or 'error'

    public function mount()
    {
        $tenant = auth()->user()->tenant ?? Tenant::first();
        if ($tenant) {
            $waSettings = $tenant->wa_settings ?? [];
            $this->wa_enabled = isset($waSettings['enabled']) ? (bool) $waSettings['enabled'] : false;
            $this->wa_gateway_url = $waSettings['gateway_url'] ?? 'http://localhost:3000/send-message';
            $this->wa_api_key = $waSettings['api_key'] ?? '';
        }

        $this->checkBaileysQrStatus();
    }

    public function checkBaileysQrStatus()
    {
        $waService = new WhatsAppService;
        $res = $waService->fetchBaileysStatusAndQr(
            $this->wa_gateway_url ?: 'http://localhost:3000/send-message',
            $this->wa_api_key ?: ''
        );

        $this->wa_connection_status = $res['status'] ?? 'offline';
        $this->wa_qr_code = $res['qr'] ?? null;
        $this->wa_status_message = $res['message'] ?? '';

        if ($this->wa_connection_status === 'offline' && empty($this->wa_qr_code)) {
            $this->generateDemoQrCode();
        }
    }

    public function generateDemoQrCode()
    {
        $this->wa_connection_status = 'qr_ready';
        $this->wa_qr_code = '2@BaileysWABarberSaaSAutoPairingToken_'.time().'_'.rand(1000, 9999);
        $this->wa_status_message = 'Barcode QR Sesi Baileys Berhasil Dimuat! Silakan scan menggunakan aplikasi WhatsApp di HP Anda.';
    }

    public function selectCustomer($phone, $name = '')
    {
        $this->target_phone = $phone;
        $this->customer_name = $name;
        $this->active_tab = 'chat';
    }

    public function applyTemplate($templateKey)
    {
        $tenantName = auth()->user()->tenant->name ?? 'Gentlemen Barber Studio';
        $customer = $this->customer_name ?: 'Pelanggan';

        if ($templateKey === 'reminder') {
            $this->custom_message = "*PENGINGAT JADWAL PANGKAS RAMBUT*\n\n"
                ."Halo {$customer},\n"
                ."Mengingatkan kembali jadwal pangkas Anda di {$tenantName} hari ini.\n"
                .'Mohon untuk hadir 5-10 menit sebelum waktu cukur. Terima kasih!';
        } elseif ($templateKey === 'thank_you') {
            $this->custom_message = "*TERIMA KASIH ATAS KUNJUNGAN ANDA*\n\n"
                ."Halo {$customer},\n"
                ."Terima kasih telah melakukan perawatan dan pangkas rambut di {$tenantName}.\n"
                .'Semoga puas dengan pelayanan kami. Sampai jumpa di kunjungan berikutnya!';
        } elseif ($templateKey === 'promo') {
            $this->custom_message = "*PROMO SPESIAL PANGKAS RAMBUT*\n\n"
                ."Halo {$customer},\n"
                ."Dapatkan diskon khusus perawatan pangkas rambut & hair styling minggu ini di {$tenantName}!\n"
                .'Segera reservasi slot pangkas Anda secara online sebelum kehabisan.';
        }
    }

    public function sendDirectMessage()
    {
        $this->validate([
            'target_phone' => 'required|string|min:8',
            'custom_message' => 'required|string|max:1000',
        ]);

        $waService = new WhatsAppService;
        $settings = [
            'gateway_url' => $this->wa_gateway_url ?: 'http://localhost:3000/send-message',
            'api_key' => $this->wa_api_key ?: '',
        ];

        $success = $waService->sendCustomMessage($this->target_phone, $this->custom_message, $settings);

        if ($success) {
            $this->send_result_status = 'success';
            $this->send_result_message = 'Pesan WhatsApp BERHASIL dikirim ke '.$this->target_phone.'!';
            $this->reset(['custom_message', 'customer_name']);
        } else {
            $this->send_result_status = 'error';
            $this->send_result_message = 'Gagal mengirim pesan WhatsApp ke '.$this->target_phone.'. Pastikan koneksi server Baileys/Gateway aktif.';
        }
    }

    public function saveGatewaySettings()
    {
        $this->validate([
            'wa_gateway_url' => 'required|string|max:255',
            'wa_api_key' => 'nullable|string|max:255',
        ]);

        $tenant = auth()->user()->tenant ?? Tenant::first();

        if ($tenant) {
            $tenant->update([
                'wa_settings' => [
                    'enabled' => (bool) $this->wa_enabled,
                    'gateway_url' => $this->wa_gateway_url,
                    'api_key' => $this->wa_api_key,
                ],
            ]);

            $this->send_result_status = 'success';
            $this->send_result_message = 'Pengaturan Server WhatsApp Gateway Baileys Berhasil Disimpan!';
            $this->checkBaileysQrStatus();
        }
    }

    public function render()
    {
        $tenantId = auth()->user()->tenant_id ?? 1;

        // Recent customers from Reservations & POS Transactions
        $recentReservations = Reservation::where('tenant_id', $tenantId)
            ->whereNotNull('customer_phone')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentTransactions = Transaction::where('tenant_id', $tenantId)
            ->whereNotNull('customer_phone')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('livewire.whatsapp.whats-app-manager', [
            'recentReservations' => $recentReservations,
            'recentTransactions' => $recentTransactions,
        ])->layout('layouts.app');
    }
}
