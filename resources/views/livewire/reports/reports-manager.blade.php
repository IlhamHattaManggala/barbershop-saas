<div>
    <style>
        @media print {
            body { background: white !important; color: black !important; padding: 0 !important; margin: 0 !important; }
            header, nav, sidebar, flux-sidebar, [data-flux-sidebar], button, select, .no-print { display: none !important; }
            .print-only { display: block !important; }
            .print-table { width: 100% !important; border-collapse: collapse !important; margin-top: 10px !important; margin-bottom: 20px !important; }
            .print-table th, .print-table td { border: 1px solid #000 !important; padding: 6px 10px !important; font-size: 11px !important; color: #000 !important; text-align: left !important; }
            .print-table th { background-color: #f1f5f9 !important; font-weight: bold !important; uppercase; }
        }
        @media screen {
            .print-only { display: none !important; }
        }
    </style>

    <!-- ==================== PRINT ONLY DOCUMENT VIEW ==================== -->
    <div class="print-only font-sans text-black space-y-6">
        
        <!-- Official Document Kop -->
        <div class="flex items-center justify-between pb-4 border-b-2 border-black">
            <div>
                <h1 class="text-2xl font-black uppercase tracking-tight text-black">{{ auth()->user()->tenant->name ?? 'Gentlemen Barber Studio' }}</h1>
                <p class="text-xs font-semibold text-black">{{ auth()->user()->tenant->address ?? 'Jl. Sudirman No. 45, Jakarta' }} &bull; WA: {{ auth()->user()->tenant->phone ?? '-' }}</p>
                <h2 class="text-sm font-bold text-black uppercase mt-2">LAPORAN PEMBAGIAN KOMISI STAF & PENDAPATAN OUTLET</h2>
            </div>
            <div class="text-right text-xs text-black space-y-1">
                <div><strong>Periode Laporan:</strong> {{ strtoupper(str_replace('_', ' ', $period)) }}</div>
                <div><strong>Dicetak Pada:</strong> {{ date('d/m/Y H:i') }} WIB</div>
                <div><strong>Dicetak Oleh:</strong> {{ auth()->user()->name }} (Owner)</div>
            </div>
        </div>

        <!-- Print Financial Summary Bar -->
        <div class="grid grid-cols-4 gap-2 text-xs py-2 border-b border-black font-semibold">
            <div>Total Omset Kotor: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div>Total Komisi Staf: Rp {{ number_format($totalStaffCommission, 0, ',', '.') }}</div>
            <div>Bersih Outlet: Rp {{ number_format($totalOutletNet, 0, ',', '.') }}</div>
            <div>Total Nota: {{ number_format($totalTransactionsCount, 0, ',', '.') }} Nota</div>
        </div>

        <!-- 1. Print Table: Rekap Performa Barber -->
        <div>
            <h3 class="text-xs font-bold uppercase mb-2">1. Rekap Bagi Hasil Komisi Barber ({{ $barberCommissionPercentage }}%)</h3>
            <table class="print-table">
                <thead>
                    <tr>
                        <th>Nama Barber</th>
                        <th>Total Pangkas (Qty)</th>
                        <th>Total Omset Layanan</th>
                        <th>Hak Komisi Barber ({{ $barberCommissionPercentage }}%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barberReports as $b)
                        <tr>
                            <td><strong>{{ $b['name'] }}</strong> {{ $b['role'] === 'owner' ? '(Owner)' : '' }}</td>
                            <td>{{ $b['cut_count'] }} Item</td>
                            <td>Rp {{ number_format($b['service_revenue'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($b['estimated_commission'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">Belum ada data staf barber.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 2. Print Table: Rekap Performa Kasir -->
        <div>
            <h3 class="text-xs font-bold uppercase mb-2">2. Rekap Insentif Komisi Kasir ({{ $cashierCommissionPercentage }}%)</h3>
            <table class="print-table">
                <thead>
                    <tr>
                        <th>Nama Kasir / Tablet POS</th>
                        <th>Total Transaksi Diproses</th>
                        <th>Total Omset POS</th>
                        <th>Hak Komisi Kasir ({{ $cashierCommissionPercentage }}%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cashierReports as $c)
                        <tr>
                            <td><strong>{{ $c['name'] }}</strong> {{ $c['role'] === 'owner' ? '(Owner)' : '' }}</td>
                            <td>{{ $c['trx_count'] }} Nota</td>
                            <td>Rp {{ number_format($c['total_revenue'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($c['estimated_commission'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">Belum ada data transaksi kasir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Print Signature Approval Footer -->
        <div class="pt-10 text-xs font-sans">
            <div class="flex justify-between items-start">
                <div class="text-center w-52 space-y-1">
                    <div>Dibuat oleh,</div>
                    <div class="font-bold">Kasir / Tablet POS</div>
                    <div class="h-16"></div>
                    <div class="font-bold uppercase">Admin POS Outlet</div>
                </div>

                <div class="text-center w-52 space-y-1">
                    <div>Disetujui oleh,</div>
                    <div class="font-bold">Owner Barbershop</div>
                    <div class="h-16"></div>
                    <div class="font-bold uppercase">{{ auth()->user()->name }}</div>
                </div>
            </div>
        </div>
    </div>


    <!-- ==================== SCREEN ONLY INTERACTIVE VIEW ==================== -->
    <div class="no-print space-y-6">
        <!-- Header with Action Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <flux:heading size="xl" level="1">Laporan & Keuangan Outlet</flux:heading>
                <flux:subheading>Pembagian finansial transparan: Komisi Staf (Barber & Kasir) vs Bagian Bersih Outlet.</flux:subheading>
            </div>

            <div class="flex items-center gap-3">
                <flux:select wire:model.live="period">
                    <option value="today">Hari Ini</option>
                    <option value="last_7_days">7 Hari Terakhir</option>
                    <option value="this_month">Bulan Ini</option>
                    <option value="all">Semua Waktu</option>
                </flux:select>

                <button onclick="window.print()" class="px-4 py-2.5 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xl shadow transition inline-flex items-center gap-2 whitespace-nowrap shrink-0 cursor-pointer">
                    <flux:icon name="arrow-down-tray" class="size-4" />
                    <span>Cetak / Download Laporan</span>
                </button>
            </div>
        </div>

        <!-- 4 Clean Executive Financial Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Omzet Kotor -->
            <flux:card class="p-5 space-y-2 border border-zinc-200 dark:border-zinc-700">
                <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Omset Kotor</div>
                <div class="text-2xl font-extrabold font-mono text-zinc-900 dark:text-white">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-zinc-400">Total penerimaan dari {{ $totalTransactionsCount }} nota lunas</div>
            </flux:card>

            <!-- Card 2: Total Komisi Staf (Barber + Kasir) -->
            <flux:card class="p-5 space-y-2 border border-emerald-200 dark:border-emerald-800/60 bg-emerald-50/40 dark:bg-emerald-950/20">
                <div class="text-xs font-semibold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider">Total Komisi Staf</div>
                <div class="text-2xl font-extrabold font-mono text-emerald-700 dark:text-emerald-400">
                    Rp {{ number_format($totalStaffCommission, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-emerald-600 dark:text-emerald-500 font-medium">Barber ({{ $barberCommissionPercentage }}%) + Kasir ({{ $cashierCommissionPercentage }}%)</div>
            </flux:card>

            <!-- Card 3: Pendapatan Bersih Outlet -->
            <flux:card class="p-5 space-y-2 border border-indigo-200 dark:border-indigo-800/60 bg-indigo-50/40 dark:bg-indigo-950/20">
                <div class="text-xs font-semibold text-indigo-800 dark:text-indigo-400 uppercase tracking-wider">Bagian Bersih Outlet</div>
                <div class="text-2xl font-extrabold font-mono text-indigo-700 dark:text-indigo-400">
                    Rp {{ number_format($totalOutletNet, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-indigo-600 dark:text-indigo-500 font-medium">Sisa bagian bersih untuk kas toko</div>
            </flux:card>

            <!-- Card 4: Total Nota Transaksi -->
            <flux:card class="p-5 space-y-2 border border-zinc-200 dark:border-zinc-700">
                <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Nota Selesai</div>
                <div class="text-2xl font-extrabold font-mono text-zinc-900 dark:text-white">
                    {{ number_format($totalTransactionsCount, 0, ',', '.') }} <span class="text-sm font-normal text-zinc-400">Nota</span>
                </div>
                <div class="text-[11px] text-zinc-400">Transaksi selesai dikerjakan</div>
            </flux:card>
        </div>

        <!-- Vertical Stack Layout: Detailed Reports -->
        <div class="space-y-6">
            
            <!-- 1. Rekapitulasi Komisi Barber -->
            <flux:card class="p-6 space-y-4 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-700">
                    <div>
                        <flux:heading size="lg">1. Rekap Bagi Hasil Komisi Barber</flux:heading>
                        <flux:subheading>Jumlah pangkas, total omset pangkas, dan hak komisi barber ({{ $barberCommissionPercentage }}%).</flux:subheading>
                    </div>
                    <flux:badge color="zinc" size="sm" class="font-mono">Total Komisi: Rp {{ number_format($totalBarberCommission, 0, ',', '.') }}</flux:badge>
                </div>

                <flux:table class="w-full">
                    <flux:table.columns>
                        <flux:table.column>Nama Barber</flux:table.column>
                        <flux:table.column>Pangkas (Qty)</flux:table.column>
                        <flux:table.column>Total Omset Layanan</flux:table.column>
                        <flux:table.column>Hak Komisi Barber ({{ $barberCommissionPercentage }}%)</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse($barberReports as $b)
                            <flux:table.row key="{{ $b['id'] }}">
                                <flux:table.cell class="font-bold text-xs">
                                    {{ $b['name'] }}
                                    @if($b['role'] === 'owner')
                                        <flux:badge size="sm" color="zinc" class="ml-1">Owner</flux:badge>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell class="text-xs font-mono font-bold">
                                    {{ $b['cut_count'] }} Item
                                </flux:table.cell>
                                <flux:table.cell class="font-mono text-xs text-zinc-900 dark:text-white font-bold">
                                    Rp {{ number_format($b['service_revenue'], 0, ',', '.') }}
                                </flux:table.cell>
                                <flux:table.cell class="font-mono text-xs text-emerald-600 dark:text-emerald-400 font-bold">
                                    Rp {{ number_format($b['estimated_commission'], 0, ',', '.') }}
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="4" class="text-center py-6 text-zinc-400 text-xs">
                                    Belum ada data pangkas staf barber.
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </flux:card>

            <!-- 2. Rekapitulasi Komisi Kasir -->
            <flux:card class="p-6 space-y-4 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-700">
                    <div>
                        <flux:heading size="lg">2. Rekap Insentif Komisi Kasir</flux:heading>
                        <flux:subheading>Jumlah nota diproses, total omset POS, dan hak komisi kasir ({{ $cashierCommissionPercentage }}%).</flux:subheading>
                    </div>
                    <flux:badge color="zinc" size="sm" class="font-mono">Total Insentif Kasir: Rp {{ number_format($totalCashierCommission, 0, ',', '.') }}</flux:badge>
                </div>

                <flux:table class="w-full">
                    <flux:table.columns>
                        <flux:table.column>Nama Kasir / Akun POS</flux:table.column>
                        <flux:table.column>Nota Diproses</flux:table.column>
                        <flux:table.column>Total Omset POS</flux:table.column>
                        <flux:table.column>Hak Komisi Kasir ({{ $cashierCommissionPercentage }}%)</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse($cashierReports as $c)
                            <flux:table.row key="{{ $c['id'] }}">
                                <flux:table.cell class="font-bold text-xs">
                                    {{ $c['name'] }}
                                    @if($c['role'] === 'owner')
                                        <flux:badge size="sm" color="zinc" class="ml-1">Owner</flux:badge>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell class="text-xs font-mono font-bold">
                                    {{ $c['trx_count'] }} Nota
                                </flux:table.cell>
                                <flux:table.cell class="font-mono text-xs text-zinc-900 dark:text-white font-bold">
                                    Rp {{ number_format($c['total_revenue'], 0, ',', '.') }}
                                </flux:table.cell>
                                <flux:table.cell class="font-mono text-xs text-indigo-600 dark:text-indigo-400 font-bold">
                                    Rp {{ number_format($c['estimated_commission'], 0, ',', '.') }}
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="4" class="text-center py-6 text-zinc-400 text-xs">
                                    Belum ada data transaksi kasir.
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </flux:card>

            <!-- 3. Rincian Riwayat Transaksi POS -->
            <flux:card class="p-6 space-y-4 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-700">
                    <div>
                        <flux:heading size="lg">3. Rincian Riwayat Transaksi POS Lunas</flux:heading>
                        <flux:subheading>Daftar nota transaksi POS terbaru yang telah selesai dibayar.</flux:subheading>
                    </div>
                </div>

                <flux:table class="w-full">
                    <flux:table.columns>
                        <flux:table.column>Kode / Jam Transaksi</flux:table.column>
                        <flux:table.column>Nama Pelanggan</flux:table.column>
                        <flux:table.column>Metode Pembayaran</flux:table.column>
                        <flux:table.column>Total Omset Nota</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse($transactions as $t)
                            <flux:table.row key="{{ $t->id }}">
                                <flux:table.cell>
                                    <div class="font-mono font-bold text-xs text-zinc-900 dark:text-white">{{ $t->transaction_number }}</div>
                                    <div class="text-[10px] text-zinc-400">{{ $t->created_at->format('d/m/Y H:i') }} WIB</div>
                                </flux:table.cell>
                                <flux:table.cell class="text-xs font-medium">
                                    {{ $t->customer_name ?? 'Pelanggan Umum' }}
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge size="sm" color="zinc">
                                        {{ strtoupper($t->payment_method) }}
                                    </flux:badge>
                                </flux:table.cell>
                                <flux:table.cell class="font-mono text-xs font-bold text-zinc-900 dark:text-white">
                                    Rp {{ number_format($t->total_amount, 0, ',', '.') }}
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="4" class="text-center py-6 text-zinc-400 text-xs">
                                    Belum ada riwayat transaksi pada periode ini.
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </flux:card>

        </div>
    </div>
</div>
