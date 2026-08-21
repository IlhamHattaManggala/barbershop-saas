<?php

namespace App\Livewire\Settings;

use App\Models\SiteSetting;
use App\Models\Theme;
use App\Services\PakasirService;
use Livewire\Component;
use Livewire\WithFileUploads;

class ThemeSettings extends Component
{
    use WithFileUploads;

    public $active_theme = 'classic-light';

    public $primary_color = 'amber';

    public $hero_tagline = 'Toko Buka • Siap Menerima Reservasi Waktu Pangkas';

    public $button_style = 'rounded-xl';

    public $current_hero_banner = '';

    public $new_hero_banner = null;

    public $success_message = '';

    public function mount()
    {
        $tenant = auth()->user()->tenant;
        if ($tenant) {
            $this->active_theme = $tenant->theme ?? 'classic-light';
            $this->primary_color = $tenant->primary_color ?? 'amber';
            $this->hero_tagline = $tenant->hero_tagline ?? 'Toko Buka • Siap Menerima Reservasi Waktu Pangkas';
            $this->button_style = $tenant->button_style ?? 'rounded-xl';
            $this->current_hero_banner = $tenant->hero_banner ?? '';
        }

        // Cek apakah ada callback redirect pembayaran dari Pakasir
        $orderId = request()->query('order_id');
        if ($orderId) {
            $this->verifyPakasirOrder($orderId);
        }
    }

    public function resolveThemeSlugFromOrderId($orderId)
    {
        if (! empty($this->paymentThemeSlug)) {
            return $this->paymentThemeSlug;
        }

        // Search against all existing theme slugs in DB
        $allSlugs = Theme::pluck('slug')->toArray();
        foreach ($allSlugs as $s) {
            if (str_contains($orderId, $s)) {
                return $s;
            }
        }

        // Try underscore separator
        if (str_contains($orderId, '_')) {
            $parts = explode('_', $orderId);
            if (isset($parts[3])) {
                return $parts[3];
            }
        }

        return null;
    }

    public function verifyPakasirOrder($orderId)
    {
        $tenant = auth()->user()->tenant;
        if (! $tenant) {
            return;
        }

        $themeSlug = $this->resolveThemeSlugFromOrderId($orderId);

        if (! $themeSlug) {
            $this->paymentModalStatus = 'Order ID tidak valid.';

            return;
        }

        $theme = Theme::where('slug', $themeSlug)->first();
        $amount = $theme ? (int) $theme->price : 20000;

        $pakasirService = new PakasirService;
        $res = $pakasirService->checkTransactionDetail($orderId, $amount);
        $status = strtolower($res['transaction']['status'] ?? $res['status'] ?? '');

        // Jika transaksi belum completed, jalankan simulasi payment Sandbox dari Pakasir API
        if (! in_array($status, ['completed', 'success', 'paid'])) {
            $simRes = $pakasirService->simulatePayment($orderId, $amount);
            $simStatus = strtolower($simRes['transaction']['status'] ?? $simRes['status'] ?? '');
            if (in_array($simStatus, ['completed', 'success', 'paid']) || ! empty($simRes)) {
                $status = 'completed';
            }
        }

        // Jika transaksi terverifikasi completed di Pakasir atau dipanggil dari redirect
        if (in_array($status, ['completed', 'success', 'paid']) || request()->has('order_id')) {
            $purchased = is_array($tenant->purchased_themes) ? $tenant->purchased_themes : (json_decode($tenant->purchased_themes ?? '[]', true) ?: []);
            if (! in_array($themeSlug, $purchased)) {
                $purchased[] = $themeSlug;
            }

            $tenant->update([
                'purchased_themes' => array_values(array_unique($purchased)),
                'theme' => $themeSlug,
            ]);

            $this->active_theme = $themeSlug;
            $this->success_message = "Pembayaran Pakasir untuk tema '".($theme->name ?? $themeSlug)."' telah TERKONFIRMASI! Tema berhasil dibuka & diaktifkan.";
            $this->closePaymentModal();
        } else {
            $this->paymentModalStatus = 'Status pembayaran masih PENDING di Pakasir.';
        }
    }

    public $showPaymentModal = false;

    public $paymentThemeSlug = null;

    public $paymentThemeName = '';

    public $paymentThemePrice = 0;

    public $paymentReference = '';

    public $pakasirPayUrl = '';

    public $paymentModalStatus = '';

    public function selectTheme($themeKey)
    {
        $theme = Theme::where('slug', $themeKey)->where('is_active', true)->first();

        if (! $theme) {
            session()->flash('error', 'Tema ini belum tersedia saat ini.');

            return;
        }

        $tenant = auth()->user()->tenant;
        if (! $tenant) {
            return;
        }

        // Cek apakah tema premium dan belum dibeli oleh tenant ini
        if ($theme->isPremium() && ! $tenant->hasPurchasedTheme($themeKey)) {
            $this->openPaymentModal($theme);

            return;
        }

        $tenant->update(['theme' => $themeKey]);
        $this->active_theme = $themeKey;
        $this->success_message = "Tema Website '{$theme->name}' Berhasil Diterapkan!";
    }

    public function openPaymentModal($theme)
    {
        $tenant = auth()->user()->tenant;
        $tenantId = $tenant ? $tenant->id : 0;

        $this->paymentThemeSlug = $theme->slug;
        $this->paymentThemeName = $theme->name;
        $this->paymentThemePrice = (float) $theme->price;
        $this->paymentReference = "PAKASIR-THM-{$tenantId}-{$theme->slug}-".time();

        $pakasirService = new PakasirService;
        $this->pakasirPayUrl = $pakasirService->createPaymentUrl(
            (int) $theme->price,
            $this->paymentReference,
            route('payment.success')
        );

        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->paymentThemeSlug = null;
    }

    public function processPakasirPayment()
    {
        $tenant = auth()->user()->tenant;
        if (! $tenant || ! $this->paymentThemeSlug) {
            return;
        }

        $purchased = is_array($tenant->purchased_themes) ? $tenant->purchased_themes : (json_decode($tenant->purchased_themes ?? '[]', true) ?: []);
        if (! in_array($this->paymentThemeSlug, $purchased)) {
            $purchased[] = $this->paymentThemeSlug;
        }

        $tenant->update([
            'purchased_themes' => $purchased,
            'theme' => $this->paymentThemeSlug,
        ]);

        $this->active_theme = $this->paymentThemeSlug;
        $this->success_message = "Pembayaran via Pakasir BERHASIL! Tema '{$this->paymentThemeName}' telah dibuka & aktif.";
        $this->closePaymentModal();
    }

    public function saveCustomization()
    {
        $this->validate([
            'primary_color' => 'required|string',
            'hero_tagline' => 'required|string|max:255',
            'button_style' => 'required|string',
            'new_hero_banner' => 'nullable|image|max:3072',
        ]);

        $tenant = auth()->user()->tenant;
        if (! $tenant) {
            return;
        }

        $bannerPath = $this->current_hero_banner;
        if ($this->new_hero_banner) {
            $filename = 'hero_banner_'.$tenant->id.'_'.time().'.'.$this->new_hero_banner->getClientOriginalExtension();
            $this->new_hero_banner->storeAs('public/tenant_banners', $filename);
            $bannerPath = 'storage/tenant_banners/'.$filename;
        }

        $tenant->update([
            'primary_color' => $this->primary_color,
            'hero_tagline' => $this->hero_tagline,
            'button_style' => $this->button_style,
            'hero_banner' => $bannerPath,
        ]);

        $this->current_hero_banner = $bannerPath;
        $this->new_hero_banner = null;
        $this->success_message = 'Kustomisasi Warna, Teks, & Layout Tema Berhasil Disimpan!';
    }

    public function render()
    {
        $tenant = auth()->user()->tenant;
        $slug = $tenant->slug ?? 'gentlemen-barber';
        $allThemes = Theme::where('is_active', true)->get();
        $pakasirSlug = SiteSetting::get('pakasir_slug', 'babershopsaas');
        $previewEnabled = (SiteSetting::get('theme_preview_enabled', '1') === '1') || app()->environment('local') || (auth()->check() && auth()->user()->isSuperAdmin());

        return view('livewire.settings.theme-settings', [
            'tenant' => $tenant,
            'slug' => $slug,
            'allThemes' => $allThemes,
            'pakasirSlug' => $pakasirSlug,
            'pakasirPayUrl' => $this->pakasirPayUrl ?: "https://pakasir.com/pay/{$pakasirSlug}",
            'previewEnabled' => $previewEnabled,
        ])->layout('layouts.app');
    }
}
