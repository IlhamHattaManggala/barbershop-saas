<?php

namespace App\Livewire\Reports;

use App\Models\Product;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Component;

class ReportsManager extends Component
{
    public $period = 'this_month'; // today, last_7_days, this_month, all

    public $start_date = '';

    public $end_date = '';

    public function mount()
    {
        $this->start_date = date('Y-m-01');
        $this->end_date = date('Y-m-d');
    }

    public function render()
    {
        $tenant = auth()->user()->tenant;
        $tenantId = auth()->user()->tenant_id;
        $commissionPercentage = $tenant ? ($tenant->barber_commission_percentage ?? 40) : 40;
        $commissionRate = $commissionPercentage / 100;

        $query = Transaction::where('tenant_id', $tenantId)->where('status', 'completed');

        if ($this->period === 'today') {
            $query->whereDate('created_at', date('Y-m-d'));
        } elseif ($this->period === 'last_7_days') {
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 days')));
        } elseif ($this->period === 'this_month') {
            $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
        } elseif ($this->period === 'custom' && $this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59']);
        }

        $transactions = $query->latest()->get();

        $totalRevenue = $transactions->sum('total_amount');
        $totalTransactionsCount = $transactions->count();

        // Barber Staff Performance Breakdown
        $barbers = User::where('tenant_id', $tenantId)->whereIn('role', ['barber', 'owner'])->get();
        $barberReports = [];

        foreach ($barbers as $b) {
            $bTransactions = $transactions->where('barber_user_id', $b->id);
            $bRevenue = $bTransactions->sum('total_amount');
            $bCount = $bTransactions->count();

            if ($bCount > 0 || $b->role === 'barber') {
                $barberReports[] = [
                    'id' => $b->id,
                    'name' => $b->name,
                    'role' => $b->role,
                    'cut_count' => $bCount,
                    'total_revenue' => $bRevenue,
                    'estimated_commission' => $bRevenue * $commissionRate,
                ];
            }
        }

        // Top Services & Products
        $servicesCount = Service::where('tenant_id', $tenantId)->count();
        $productsCount = Product::where('tenant_id', $tenantId)->count();

        // Find registered cashier
        $cashier = User::where('tenant_id', $tenantId)->where('role', 'cashier')->first();
        $cashierName = $cashier ? $cashier->name : 'Rian Kasir';

        return view('livewire.reports.reports-manager', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'totalTransactionsCount' => $totalTransactionsCount,
            'barberReports' => $barberReports,
            'commissionPercentage' => $commissionPercentage,
            'servicesCount' => $servicesCount,
            'productsCount' => $productsCount,
            'cashierName' => $cashierName,
        ])->layout('layouts.app');
    }
}
