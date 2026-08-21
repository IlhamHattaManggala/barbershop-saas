<?php

namespace App\Livewire\Settings;

use App\Models\Tenant;
use Livewire\Component;

class ReceiptSettings extends Component
{
    public $receipt_paper_size = '58mm';

    public $receipt_header_text = '';

    public $receipt_footer_text = 'Terima kasih atas kunjungan Anda. Harap simpan struk ini sebagai bukti pembayaran resmi.';

    public $receipt_show_logo = true;

    public $receipt_show_barber = true;

    public $receipt_enable_print = true;

    public $success_message = '';

    public function mount()
    {
        $tenant = auth()->user()->tenant;
        if ($tenant) {
            $receiptSettings = $tenant->receipt_settings ?? [];
            $this->receipt_paper_size = $receiptSettings['paper_size'] ?? '58mm';
            $this->receipt_header_text = $receiptSettings['header_text'] ?? '';
            $this->receipt_footer_text = $receiptSettings['footer_text'] ?? 'Terima kasih atas kunjungan Anda. Harap simpan struk ini sebagai bukti pembayaran resmi.';
            $this->receipt_show_logo = isset($receiptSettings['show_logo']) ? (bool) $receiptSettings['show_logo'] : true;
            $this->receipt_show_barber = isset($receiptSettings['show_barber']) ? (bool) $receiptSettings['show_barber'] : true;
            $this->receipt_enable_print = isset($receiptSettings['enable_print']) ? (bool) $receiptSettings['enable_print'] : true;
        }
    }

    public function saveReceiptSettings()
    {
        $this->validate([
            'receipt_paper_size' => 'required|in:58mm,80mm',
            'receipt_header_text' => 'nullable|string|max:300',
            'receipt_footer_text' => 'nullable|string|max:300',
        ]);

        $tenant = auth()->user()->tenant ?? Tenant::first();

        if ($tenant) {
            $tenant->update([
                'receipt_settings' => [
                    'paper_size' => $this->receipt_paper_size,
                    'header_text' => $this->receipt_header_text,
                    'footer_text' => $this->receipt_footer_text,
                    'show_logo' => (bool) $this->receipt_show_logo,
                    'show_barber' => (bool) $this->receipt_show_barber,
                    'enable_print' => (bool) $this->receipt_enable_print,
                ],
            ]);

            $this->success_message = 'Pengaturan Struk Kasir POS Thermal Berhasil Disimpan!';
        }
    }

    public function render()
    {
        return view('livewire.settings.receipt-settings')
            ->layout('layouts.app');
    }
}
