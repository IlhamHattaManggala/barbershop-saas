<?php

namespace App\Livewire\Cashier;

use App\Models\Product;
use App\Models\Reservation;
use App\Models\Transaction;
use Livewire\Component;

class CashierDashboard extends Component
{
    public function render()
    {
        $tenantId = auth()->user()->tenant_id ?? 1;
        $userId = auth()->id();
        $today = date('Y-m-d');

        // Today's Transactions handled by this Cashier
        $todayTransactions = Transaction::where('tenant_id', $tenantId)
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $myTransactionsCount = $todayTransactions->where('cashier_user_id', $userId)->count();
        $todayTotalAmount = $todayTransactions->sum('total_amount');
        $cashTotal = $todayTransactions->where('payment_method', 'cash')->sum('total_amount');
        $qrisTotal = $todayTransactions->whereIn('payment_method', ['qris', 'transfer'])->sum('total_amount');

        // Reservations for today
        $todayReservationsCount = Reservation::where('tenant_id', $tenantId)
            ->whereDate('reservation_date', $today)
            ->count();

        // Low stock products alert
        $lowStockProducts = Product::where('tenant_id', $tenantId)
            ->whereColumn('stock', '<=', 'min_stock')
            ->get();

        return view('livewire.cashier.cashier-dashboard', [
            'todayTransactions' => $todayTransactions->take(10),
            'totalTransactionsCount' => $todayTransactions->count(),
            'myTransactionsCount' => $myTransactionsCount,
            'todayTotalAmount' => $todayTotalAmount,
            'cashTotal' => $cashTotal,
            'qrisTotal' => $qrisTotal,
            'todayReservationsCount' => $todayReservationsCount,
            'lowStockProducts' => $lowStockProducts,
        ])->layout('layouts.app');
    }
}
