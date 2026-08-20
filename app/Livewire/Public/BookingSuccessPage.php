<?php

namespace App\Livewire\Public;

use App\Models\Reservation;
use App\Models\Tenant;
use Livewire\Component;

class BookingSuccessPage extends Component
{
    public string $slug;

    public string $reservationCode;

    public ?Tenant $tenant = null;

    public ?Reservation $reservation = null;

    public function mount(string $slug, string $reservationCode)
    {
        $this->slug = $slug;
        $this->reservationCode = $reservationCode;

        $this->tenant = Tenant::where('slug', $slug)->firstOrFail();
        $this->reservation = Reservation::where('tenant_id', $this->tenant->id)
            ->where('reservation_code', $reservationCode)
            ->with(['service', 'barber'])
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public.booking-success-page')
            ->layout('layouts.app');
    }
}
