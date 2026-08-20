<?php

namespace App\Livewire\Reservations;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class PapanReservasi extends Component
{
    public $customer_name = '';

    public $customer_phone = '';

    public $service_id = null;

    public $barber_user_id = null;

    public $reservation_date = '';

    public $start_time = '14:00';

    public $notes = '';

    public $success_message = '';

    public function mount()
    {
        $this->reservation_date = date('Y-m-d');
    }

    public function createReservation()
    {
        $this->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'service_id' => 'required|exists:services,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required',
        ]);

        $service = Service::find($this->service_id);
        $duration = $service ? $service->duration_minutes : 30;
        $endTime = date('H:i:s', strtotime($this->start_time." + {$duration} minutes"));

        $tenantId = auth()->user()->tenant_id ?? 1;

        Reservation::create([
            'tenant_id' => $tenantId,
            'reservation_code' => 'RSV-'.strtoupper(Str::random(6)),
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_user_id' => auth()->id(),
            'service_id' => $this->service_id,
            'barber_user_id' => $this->barber_user_id,
            'reservation_date' => $this->reservation_date,
            'start_time' => $this->start_time,
            'end_time' => $endTime,
            'status' => 'confirmed',
            'notes' => $this->notes,
        ]);

        $this->success_message = 'Reservasi Baru Berhasil Dijadwalkan!';
        $this->reset(['customer_name', 'customer_phone', 'notes']);
    }

    public function updateStatus($reservationId, $newStatus)
    {
        $reservation = Reservation::find($reservationId);
        if ($reservation) {
            $reservation->update(['status' => $newStatus]);
        }
    }

    public function render()
    {
        $tenantId = auth()->user()->tenant_id ?? 1;

        $reservations = Reservation::where('tenant_id', $tenantId)
            ->with(['service', 'barber'])
            ->latest()
            ->get();

        $services = Service::where('tenant_id', $tenantId)->where('is_active', true)->get();
        $barbers = User::where('tenant_id', $tenantId)->whereIn('role', ['barber', 'owner'])->get();

        return view('livewire.reservations.papan-reservasi', [
            'reservations' => $reservations,
            'services' => $services,
            'barbers' => $barbers,
        ])->layout('layouts.app');
    }
}
