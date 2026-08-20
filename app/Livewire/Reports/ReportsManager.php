<?php

namespace App\Livewire\Reports;

use App\Models\Product;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Livewire\Component;

class ReportsManager extends Component
{
    public $period = 'this_month'; // today, last_7_days, this_month, custom

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

        $barberCommissionPercentage = $tenant ? (float) ($tenant->barber_commission_percentage ?? 40) : 40.0;
        $cashierCommissionPercentage = $tenant ? (float) ($tenant->cashier_commission_percentage ?? 5) : 5.0;

        $query = Transaction::where('tenant_id', $tenantId)
            ->whereIn('status', ['paid', 'completed']);

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
        $transactionIds = $transactions->pluck('id')->toArray();
        $transactionItems = TransactionItem::whereIn('transaction_id', $transactionIds)->get();

        $totalRevenue = (float) $transactions->sum('total_amount');
        $totalTransactionsCount = $transactions->count();

        // 1. Barber Staff Performance & Commission Report
        $barbers = User::where('tenant_id', $tenantId)->whereIn('role', ['barber', 'owner'])->get();
        $barberReports = [];
        $totalBarberCommission = 0;

        foreach ($barbers as $b) {
            // Service items assigned to this barber
            $bServiceItems = $transactionItems->where('item_type', 'service')->where('barber_user_id', $b->id);
            $bServiceRevenue = (float) $bServiceItems->sum('subtotal');
            $bCutCount = $bServiceItems->sum('quantity');

            // Product items sold by this barber (if any)
            $bProductItems = $transactionItems->where('item_type', 'product')->where('barber_user_id', $b->id);
            $bProductRevenue = (float) $bProductItems->sum('subtotal');

            $bTotalRevenue = $bServiceRevenue + $bProductRevenue;
            $bCommission = $bServiceRevenue * ($barberCommissionPercentage / 100);

            if ($bCutCount > 0 || $b->role === 'barber') {
                $barberReports[] = [
                    'id' => $b->id,
                    'name' => $b->name,
                    'role' => $b->role,
                    'cut_count' => $bCutCount,
                    'service_revenue' => $bServiceRevenue,
                    'total_revenue' => $bTotalRevenue,
                    'estimated_commission' => $bCommission,
                ];
                $totalBarberCommission += $bCommission;
            }
        }

        // 2. Cashier Staff Performance & Commission Report
        $cashiers = User::where('tenant_id', $tenantId)->whereIn('role', ['cashier', 'owner'])->get();
        $cashierReports = [];
        $totalCashierCommission = 0;

        foreach ($cashiers as $c) {
            $cTransactions = $transactions->where('cashier_user_id', $c->id);
            $cRevenue = (float) $cTransactions->sum('total_amount');
            $cCount = $cTransactions->count();

            $cCommission = $cRevenue * ($cashierCommissionPercentage / 100);

            if ($cCount > 0 || $c->role === 'cashier') {
                $cashierReports[] = [
                    'id' => $c->id,
                    'name' => $c->name,
                    'role' => $c->role,
                    'trx_count' => $cCount,
                    'total_revenue' => $cRevenue,
                    'estimated_commission' => $cCommission,
                ];
                $totalCashierCommission += $cCommission;
            }
        }

        // 3. Overall Staff Commission & Outlet Net Split
        $totalStaffCommission = $totalBarberCommission + $totalCashierCommission;
        $totalOutletNet = max(0, $totalRevenue - $totalStaffCommission);

        $servicesCount = Service::where('tenant_id', $tenantId)->count();
        $productsCount = Product::where('tenant_id', $tenantId)->count();

        return view('livewire.reports.reports-manager', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'totalTransactionsCount' => $totalTransactionsCount,
            'barberReports' => $barberReports,
            'cashierReports' => $cashierReports,
            'barberCommissionPercentage' => $barberCommissionPercentage,
            'cashierCommissionPercentage' => $cashierCommissionPercentage,
            'totalBarberCommission' => $totalBarberCommission,
            'totalCashierCommission' => $totalCashierCommission,
            'totalStaffCommission' => $totalStaffCommission,
            'totalOutletNet' => $totalOutletNet,
            'servicesCount' => $servicesCount,
            'productsCount' => $productsCount,
        ])->layout('layouts.app');
    }
}
