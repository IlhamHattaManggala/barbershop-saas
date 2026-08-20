<?php

namespace App\Livewire\Staff;

use App\Models\User;
use Livewire\Component;

class StaffManager extends Component
{
    public $name = '';

    public $email = '';

    public $phone = '';

    public $role = 'barber';

    public $search = '';

    public $success_message = '';

    // Edit Staff State
    public $editing_staff_id = null;

    public $edit_name = '';

    public $edit_email = '';

    public $edit_phone = '';

    public $edit_role = 'barber';

    // Delete Staff State
    public $deleting_staff_id = null;

    public $deleting_staff_name = '';

    public function createStaff()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:barber,cashier',
        ]);

        $tenantId = auth()->user()->tenant_id ?? 1;

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt('password'),
            'role' => $this->role,
            'tenant_id' => $tenantId,
            'phone' => $this->phone,
        ]);

        $this->success_message = "Akun Staf '{$this->name}' ({$this->role}) Berhasil Dibuat!";
        $this->reset(['name', 'email', 'phone']);
    }

    public function editStaff($id)
    {
        $staff = User::findOrFail($id);
        $this->editing_staff_id = $staff->id;
        $this->edit_name = $staff->name;
        $this->edit_email = $staff->email;
        $this->edit_phone = $staff->phone ?? '';
        $this->edit_role = $staff->role;
    }

    public function updateStaff()
    {
        $this->validate([
            'edit_name' => 'required|string|max:100',
            'edit_email' => 'required|email|unique:users,email,'.$this->editing_staff_id,
            'edit_role' => 'required|in:barber,cashier,owner',
        ]);

        $staff = User::findOrFail($this->editing_staff_id);
        $staff->update([
            'name' => $this->edit_name,
            'email' => $this->edit_email,
            'phone' => $this->edit_phone,
            'role' => $this->edit_role,
        ]);

        $this->success_message = "Data Staf '{$staff->name}' Berhasil Perbarui!";
        $this->reset(['editing_staff_id', 'edit_name', 'edit_email', 'edit_phone']);
    }

    public function confirmDeleteStaff($id)
    {
        $staff = User::findOrFail($id);
        $this->deleting_staff_id = $staff->id;
        $this->deleting_staff_name = $staff->name;
    }

    public function deleteStaff()
    {
        if (! $this->deleting_staff_id) {
            return;
        }

        $staff = User::findOrFail($this->deleting_staff_id);

        if ($staff->id === auth()->id()) {
            session()->flash('delete_error', 'Anda tidak dapat menghapus akun Anda sendiri!');
            $this->reset(['deleting_staff_id', 'deleting_staff_name']);

            return;
        }

        $name = $staff->name;
        $staff->delete();
        $this->success_message = "Akun Staf '{$name}' Berhasil Dihapus!";
        $this->reset(['deleting_staff_id', 'deleting_staff_name']);
    }

    public function render()
    {
        $tenantId = auth()->user()->tenant_id ?? 1;

        $staffMembers = User::where('tenant_id', $tenantId)
            ->whereIn('role', ['owner', 'cashier', 'barber'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->get();

        return view('livewire.staff.staff-manager', [
            'staffMembers' => $staffMembers,
        ])->layout('layouts.app');
    }
}
