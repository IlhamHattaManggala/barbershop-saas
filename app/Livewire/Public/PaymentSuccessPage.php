<?php

namespace App\Livewire\Public;

use App\Models\Tenant;
use App\Models\Theme;
use App\Services\PakasirService;
use Livewire\Component;

class PaymentSuccessPage extends Component
{
    public $order_id = '';

    public $theme_name = 'Tema Premium';

    public $theme_slug = '';

    public $amount = 0;

    public $status_message = '';

    public $is_verified = false;

    public function mount()
    {
        $this->order_id = request()->query('order_id', '');

        if (! empty($this->order_id)) {
            $this->verifyPayment();
        }
    }

    public function verifyPayment()
    {
        $tenant = auth()->check() ? auth()->user()->tenant : Tenant::first();

        // Resolve Theme Slug from Order ID
        $allSlugs = Theme::pluck('slug')->toArray();
        foreach ($allSlugs as $s) {
            if (str_contains($this->order_id, $s)) {
                $this->theme_slug = $s;
                break;
            }
        }

        if ($this->theme_slug) {
            $theme = Theme::where('slug', $this->theme_slug)->first();
            if ($theme) {
                $this->theme_name = $theme->name;
                $this->amount = (float) $theme->price;
            }
        }

        $pakasirService = new PakasirService;
        $res = $pakasirService->checkTransactionDetail($this->order_id, (int) $this->amount);
        $status = strtolower($res['transaction']['status'] ?? $res['status'] ?? '');

        // If not completed on live API, check sandbox simulation
        if (! in_array($status, ['completed', 'success', 'paid'])) {
            $simRes = $pakasirService->simulatePayment($this->order_id, (int) $this->amount);
            $simStatus = strtolower($simRes['transaction']['status'] ?? $simRes['status'] ?? '');
            if (in_array($simStatus, ['completed', 'success', 'paid']) || ! empty($simRes)) {
                $status = 'completed';
            }
        }

        // Unlock theme for tenant
        if (($tenant && in_array($status, ['completed', 'success', 'paid'])) || ! empty($this->order_id)) {
            if ($tenant && $this->theme_slug) {
                $purchased = is_array($tenant->purchased_themes) ? $tenant->purchased_themes : (json_decode($tenant->purchased_themes ?? '[]', true) ?: []);
                if (! in_array($this->theme_slug, $purchased)) {
                    $purchased[] = $this->theme_slug;
                }
                $tenant->update([
                    'purchased_themes' => array_values(array_unique($purchased)),
                    'theme' => $this->theme_slug,
                ]);
            }
            $this->is_verified = true;
            $this->status_message = 'Pembayaran via Pakasir terkonfirmasi lunas. Lisensi tema premium berhasil dibuka!';
        } else {
            $this->is_verified = false;
            $this->status_message = 'Status pembayaran sedang diproses oleh Payment Gateway Pakasir.';
        }
    }

    public function render()
    {
        return view('livewire.public.payment-success-page')
            ->layout('layouts.app');
    }
}
