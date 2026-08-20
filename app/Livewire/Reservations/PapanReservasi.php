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

    public $status_filter = 'all';

    public $barber_filter = 'all';

    public $show_reassign_modal = false;

    public $reassign_reservation_id = null;

    public $reassign_barber_id = null;

    public function mount()
    {
        $this->reservation_date = date('Y-m-d');

        // ROLE SCOPING: If logged in as Barber, scope view & default barber choice to own User ID
        if (auth()->check() && auth()->user()->role === 'barber') {
            $this->barber_filter = auth()->id();
            $this->barber_user_id = auth()->id();
        }
    }

    public function openReassignModal($reservationId)
    {
        $reservation = Reservation::find($reservationId);
        if ($reservation) {
            $this->reassign_reservation_id = $reservation->id;
            $this->reassign_barber_id = $reservation->barber_user_id;
            $this->show_reassign_modal = true;
        }
    }

    public function closeReassignModal()
    {
        $this->show_reassign_modal = false;
        $this->reassign_reservation_id = null;
        $this->reassign_barber_id = null;
    }

    public function saveReassignBarber()
    {
        if ($this->reassign_reservation_id && $this->reassign_barber_id) {
            $reservation = Reservation::find($this->reassign_reservation_id);
            if ($reservation) {
                $newBarber = User::find($this->reassign_barber_id);
                $reservation->update(['barber_user_id' => $this->reassign_barber_id]);
                $this->success_message = 'Reservasi '.($reservation->reservation_code ?? 'Tamu').' Berhasil Dialihkan ke Barber '.($newBarber->name ?? 'Baru').'!';
            }
        }
        $this->closeReassignModal();
    }

    public function updateStatus($reservationId, $newStatus)
    {
        $reservation = Reservation::find($reservationId);
        if ($reservation) {
            $reservation->update(['status' => $newStatus]);
            $this->success_message = 'Status Reservasi '.$reservation->reservation_code.' Diperbarui!';
        }
    }

    public function sendToPosCheckout($reservationId)
    {
        return redirect()->to('/pos?reservation_id='.$reservationId);
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
        $assignedBarber = $this->barber_user_id ?: (auth()->user()->role === 'barber' ? auth()->id() : null);

        Reservation::create([
            'tenant_id' => $tenantId,
            'reservation_code' => 'RSV-'.strtoupper(Str::random(6)),
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_user_id' => auth()->id(),
            'service_id' => $this->service_id,
            'barber_user_id' => $assignedBarber,
            'reservation_date' => $this->reservation_date,
            'start_time' => $this->start_time,
            'end_time' => $endTime,
            'status' => 'confirmed',
            'notes' => $this->notes,
        ]);

        $this->success_message = 'Reservasi Baru Berhasil Dijadwalkan!';
        $this->reset(['customer_name', 'customer_phone', 'notes']);

        if (auth()->check() && auth()->user()->role === 'barber') {
            $this->barber_user_id = auth()->id();
        } else {
            $this->reset('barber_user_id');
        }
    }

    public function render()
    {
        $tenantId = auth()->user()->tenant_id ?? 1;

        $query = Reservation::where('tenant_id', $tenantId)
            ->with(['service', 'barber']);

        if ($this->reservation_date) {
            $query->whereDate('reservation_date', $this->reservation_date);
        }

        if ($this->status_filter !== 'all') {
            $query->where('status', $this->status_filter);
        }

        // ROLE SCOPING: If logged in as Barber, only show reservations assigned to him OR unassigned (barber_user_id is null)
        if (auth()->check() && auth()->user()->role === 'barber') {
            $query->where(function ($q) {
                $q->where('barber_user_id', auth()->id())
                    ->orWhereNull('barber_user_id');
            });
        } elseif ($this->barber_filter !== 'all') {
            $query->where('barber_user_id', $this->barber_filter);
        }

        $reservations = $query->orderBy('start_time', 'asc')->get();

        $services = Service::where('tenant_id', $tenantId)->where('is_active', true)->get();
        $barbers = User::where('tenant_id', $tenantId)->whereIn('role', ['barber', 'owner'])->get();

        return view('livewire.reservations.papan-reservasi', [
            'reservations' => $reservations,
            'services' => $services,
            'barbers' => $barbers,
        ])->layout('layouts.app');
    }
}
