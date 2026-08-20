<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Component;

class ServicesManager extends Component
{
    public $name = '';

    public $price = 50000;

    public $duration_minutes = 30;

    public $description = '';

    public $search = '';

    public $success_message = '';

    // Edit Service State
    public $editing_service_id = null;

    public $edit_name = '';

    public $edit_price = 50000;

    public $edit_duration_minutes = 30;

    public $edit_description = '';

    // Delete Service State
    public $deleting_service_id = null;

    public $deleting_service_name = '';

    public function createService()
    {
        $this->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:5',
        ]);

        $tenantId = auth()->user()->tenant_id ?? 1;

        Service::create([
            'tenant_id' => $tenantId,
            'name' => $this->name,
            'price' => $this->price,
            'duration_minutes' => $this->duration_minutes,
            'description' => $this->description,
            'is_active' => true,
        ]);

        $this->success_message = "Layanan Pangkas '{$this->name}' Berhasil Ditambahkan!";
        $this->reset(['name', 'price', 'duration_minutes', 'description']);
    }

    public function editService($id)
    {
        $service = Service::findOrFail($id);
        $this->editing_service_id = $service->id;
        $this->edit_name = $service->name;
        $this->edit_price = $service->price;
        $this->edit_duration_minutes = $service->duration_minutes;
        $this->edit_description = $service->description ?? '';
    }

    public function updateService()
    {
        $this->validate([
            'edit_name' => 'required|string',
            'edit_price' => 'required|numeric|min:0',
            'edit_duration_minutes' => 'required|integer|min:5',
        ]);

        $service = Service::findOrFail($this->editing_service_id);
        $service->update([
            'name' => $this->edit_name,
            'price' => $this->edit_price,
            'duration_minutes' => $this->edit_duration_minutes,
            'description' => $this->edit_description,
        ]);

        $this->success_message = "Layanan '{$service->name}' Berhasil Diperbarui!";
        $this->reset(['editing_service_id', 'edit_name', 'edit_price', 'edit_duration_minutes', 'edit_description']);
    }

    public function confirmDeleteService($id)
    {
        $service = Service::findOrFail($id);
        $this->deleting_service_id = $service->id;
        $this->deleting_service_name = $service->name;
    }

    public function deleteService()
    {
        if (! $this->deleting_service_id) {
            return;
        }
        $service = Service::findOrFail($this->deleting_service_id);
        $name = $service->name;
        $service->delete();
        $this->success_message = "Layanan '{$name}' Berhasil Dihapus!";
        $this->reset(['deleting_service_id', 'deleting_service_name']);
    }

    public function toggleActive($serviceId)
    {
        $service = Service::find($serviceId);
        if ($service) {
            $service->update(['is_active' => ! $service->is_active]);
        }
    }

    public function render()
    {
        $tenantId = auth()->user()->tenant_id ?? 1;

        $services = Service::where('tenant_id', $tenantId)
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->get();

        return view('livewire.services.services-manager', [
            'services' => $services,
        ])->layout('layouts.app');
    }
}
