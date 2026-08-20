<?php

namespace App\Livewire\Settings;

use App\Models\SiteSetting;
use Livewire\Component;

class GatewaySettings extends Component
{
    public $pakasir_api_key = '';

    public $pakasir_slug = '';

    public $pakasir_is_active = true;

    public $show_key = false;

    public $success_message = '';

    public function mount()
    {
        $this->pakasir_api_key = SiteSetting::getEncrypted('pakasir_api_key', 'Vbt9gVU18YnB2fq316y9XoKnhbFep4vr');
        $this->pakasir_slug = SiteSetting::get('pakasir_slug', 'babershopsaas');
        $this->pakasir_is_active = (SiteSetting::get('pakasir_is_active', '1') === '1');
    }

    public function toggleShowKey()
    {
        $this->show_key = ! $this->show_key;
    }

    public function saveGateway()
    {
        $this->validate([
            'pakasir_api_key' => 'required|string',
            'pakasir_slug' => 'required|string|max:100',
        ]);

        SiteSetting::setEncrypted('pakasir_api_key', trim($this->pakasir_api_key));
        SiteSetting::set('pakasir_slug', trim($this->pakasir_slug));
        SiteSetting::set('pakasir_is_active', $this->pakasir_is_active ? '1' : '0');

        $this->success_message = 'Konfigurasi Payment Gateway Pakasir berhasil diperbarui dan tersimpan dengan enkripsi!';
    }

    public function render()
    {
        return view('livewire.settings.gateway-settings', [
            'webhook_url' => url('api/webhooks/pakasir'),
        ])->layout('layouts.app');
    }
}
