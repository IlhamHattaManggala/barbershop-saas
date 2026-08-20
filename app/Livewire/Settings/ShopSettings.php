<?php

namespace App\Livewire\Settings;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShopSettings extends Component
{
    use WithFileUploads;

    public $name = '';

    public $phone = '';

    public $address = '';

    public $description = '';

    public $new_logo;

    public $current_logo = '';

    public $new_qris_image;

    public $current_qris_image = '';

    public $bank_info = '';

    public $barber_commission_percentage = 40;

    public $slug = '';

    public $success_message = '';

    public function mount()
    {
        $tenant = auth()->user()->tenant;
        if ($tenant) {
            $this->name = $tenant->name;
            $this->phone = $tenant->phone ?? '';
            $this->address = $tenant->address ?? '';
            $this->description = $tenant->description ?? '';
            $this->current_logo = $tenant->logo ?? '';
            $this->current_qris_image = $tenant->qris_image ?? '';
            $this->bank_info = $tenant->bank_info ?? '';
            $this->barber_commission_percentage = $tenant->barber_commission_percentage ?? 40;
            $this->slug = $tenant->slug;
        } else {
            $this->name = 'Gentlemen Barber Studio';
            $this->slug = 'gentlemen-barber';
        }
    }

    public function saveShopSettings()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'description' => 'nullable|string|max:500',
            'bank_info' => 'nullable|string|max:500',
            'barber_commission_percentage' => 'required|integer|min:0|max:100',
            'new_logo' => 'nullable|image|max:2048',
            'new_qris_image' => 'nullable|image|max:3072',
        ]);

        $tenant = auth()->user()->tenant ?? Tenant::first();

        if ($tenant) {
            $data = [
                'name' => $this->name,
                'phone' => $this->phone,
                'address' => $this->address,
                'description' => $this->description,
                'bank_info' => $this->bank_info,
                'barber_commission_percentage' => $this->barber_commission_percentage,
            ];

            if ($this->new_logo) {
                $logoPath = $this->new_logo->store('uploads/tenants', 'public');
                $data['logo'] = 'storage/'.$logoPath;
                $this->current_logo = 'storage/'.$logoPath;
                $this->reset('new_logo');
            }

            if ($this->new_qris_image) {
                $qrisPath = $this->new_qris_image->store('uploads/tenants/qris', 'public');
                $data['qris_image'] = 'storage/'.$qrisPath;
                $this->current_qris_image = 'storage/'.$qrisPath;
                $this->reset('new_qris_image');
            }

            $tenant->update($data);
            $this->success_message = 'Pengaturan Outlet Barbershop & Barcode QRIS Berhasil Disimpan!';
        }
    }

    public function removeQrisImage()
    {
        $tenant = auth()->user()->tenant ?? Tenant::first();
        if ($tenant) {
            $tenant->update(['qris_image' => null]);
            $this->current_qris_image = '';
            $this->success_message = 'Gambar QRIS berhasil dihapus!';
        }
    }

    public function render()
    {
        return view('livewire.settings.shop-settings')
            ->layout('layouts.app');
    }
}
