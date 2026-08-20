<?php

namespace App\Livewire\Admin;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class TenantsManager extends Component
{
    public $name = '';

    public $slug = '';

    public $owner_name = '';

    public $owner_email = '';

    public $phone = '';

    public $address = '';

    public $subscription_plan = 'pro';

    public $search = '';

    public $success_message = '';

    public function createTenant()
    {
        $this->validate([
            'name' => 'required|string',
            'owner_email' => 'required|email|unique:users,email',
            'owner_name' => 'required|string',
        ]);

        $slug = Str::slug($this->name);

        $tenant = Tenant::create([
            'name' => $this->name,
            'slug' => $slug,
            'phone' => $this->phone,
            'address' => $this->address,
            'status' => 'active',
            'subscription_plan' => $this->subscription_plan,
        ]);

        $owner = User::create([
            'name' => $this->owner_name,
            'email' => $this->owner_email,
            'password' => bcrypt('password'),
            'role' => 'owner',
            'tenant_id' => $tenant->id,
            'phone' => $this->phone,
        ]);

        $tenant->update(['owner_id' => $owner->id]);

        $this->success_message = "Tenant Barbershop '{$tenant->name}' Berhasil Ditambahkan!";
        $this->reset(['name', 'slug', 'owner_name', 'owner_email', 'phone', 'address']);
    }

    public function toggleStatus($tenantId)
    {
        $tenant = Tenant::find($tenantId);
        if ($tenant) {
            $newStatus = $tenant->status === 'active' ? 'suspended' : 'active';
            $tenant->update(['status' => $newStatus]);
        }
    }

    public function render()
    {
        $tenants = Tenant::with('users')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('slug', 'like', "%{$this->search}%"))
            ->latest()
            ->get();

        return view('livewire.admin.tenants-manager', [
            'tenants' => $tenants,
        ])->layout('layouts.app');
    }
}
