<?php

namespace App\Livewire\Public;

use Livewire\Component;

class PaymentFailedPage extends Component
{
    public $order_id = '';

    public $reason = '';

    public function mount()
    {
        $this->order_id = request()->query('order_id', '');
        $this->reason = request()->query('reason', 'Pembayaran dibatalkan atau waktu transaksi di Pakasir telah kedaluwarsa.');
    }

    public function render()
    {
        return view('livewire.public.payment-failed-page')
            ->layout('layouts.app');
    }
}
