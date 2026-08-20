<?php

namespace App\Livewire\Settings;

use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\WithFileUploads;

class BrandingSettings extends Component
{
    use WithFileUploads;

    public $app_name = '';

    public $app_tagline = '';

    public $footer_text = '';

    public $new_logo;

    public $new_favicon;

    public $current_logo = '';

    public $current_favicon = '';

    public $success_message = '';

    public function mount()
    {
        $this->app_name = SiteSetting::get('app_name', 'BarberSaaS');
        $this->app_tagline = SiteSetting::get('app_tagline', 'Platform Barbershop Multi-Tenant #1 di Indonesia');
        $this->footer_text = SiteSetting::get('footer_text', '© 2026 BarberSaaS. All rights reserved.');
        $this->current_logo = SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp');
        $this->current_favicon = SiteSetting::get('app_favicon', 'images/logos/Logo-BaberSaaS.webp');
    }

    public function saveBranding()
    {
        $this->validate([
            'app_name' => 'required|string|max:100',
            'app_tagline' => 'nullable|string|max:255',
            'footer_text' => 'nullable|string|max:255',
            'new_logo' => 'nullable|image|max:2048',
            'new_favicon' => 'nullable|image|max:1024',
        ]);

        SiteSetting::set('app_name', $this->app_name);
        SiteSetting::set('app_tagline', $this->app_tagline);
        SiteSetting::set('footer_text', $this->footer_text);

        if ($this->new_logo) {
            $logoPath = $this->new_logo->store('uploads/logos', 'public');
            SiteSetting::set('app_logo', 'storage/'.$logoPath);
            $this->current_logo = 'storage/'.$logoPath;
        }

        if ($this->new_favicon) {
            $faviconPath = $this->new_favicon->store('uploads/favicons', 'public');
            SiteSetting::set('app_favicon', 'storage/'.$faviconPath);
            $this->current_favicon = 'storage/'.$faviconPath;
        }

        $this->success_message = 'Pengaturan Identitas & Logo Website Berhasil Disimpan ke Database!';
    }

    public function render()
    {
        return view('livewire.settings.branding-settings')
            ->layout('layouts.app');
    }
}
