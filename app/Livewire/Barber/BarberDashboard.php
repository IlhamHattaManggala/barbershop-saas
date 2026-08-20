<?php

namespace App\Livewire\Barber;

use App\Models\Reservation;
use Livewire\Component;

class BarberDashboard extends Component
{
    public $success_message = '';

    public function updateStatus($reservationId, $newStatus)
    {
        $reservation = Reservation::where('tenant_id', auth()->user()->tenant_id ?? 1)
            ->where('id', $reservationId)
            ->first();

        if ($reservation) {
            $reservation->update(['status' => $newStatus]);
            $statusLabel = match ($newStatus) {
                'in_service' => 'Sedang Dipotong (In-Service)',
                'completed' => 'Selesai Dipotong (Completed)',
                'cancelled' => 'Dibatalkan',
                default => 'Diubah',
            };
            $this->success_message = "Status reservasi {$reservation->reservation_code} berhasil diubah ke {$statusLabel}.";
        }
    }

    public function render()
    {
        $tenantId = auth()->user()->tenant_id ?? 1;
        $barberId = auth()->id();
        $today = date('Y-m-d');

        // Today's reservations assigned specifically to this Barber
        $myReservations = Reservation::where('tenant_id', $tenantId)
            ->where('barber_user_id', $barberId)
            ->whereDate('reservation_date', $today)
            ->with(['service', 'customer'])
            ->orderBy('start_time', 'asc')
            ->get();

        $myTotalCount = $myReservations->count();
        $myCompletedCount = $myReservations->where('status', 'completed')->count();
        $myPendingCount = $myReservations->whereIn('status', ['pending', 'confirmed', 'checked_in', 'in_service'])->count();

        // Total queue for the entire shop today
        $shopTotalCount = Reservation::where('tenant_id', $tenantId)
            ->whereDate('reservation_date', $today)
            ->count();

        return view('livewire.barber.barber-dashboard', [
            'myReservations' => $myReservations,
            'myTotalCount' => $myTotalCount,
            'myCompletedCount' => $myCompletedCount,
            'myPendingCount' => $myPendingCount,
            'shopTotalCount' => $shopTotalCount,
        ])->layout('layouts.app');
    }
}
