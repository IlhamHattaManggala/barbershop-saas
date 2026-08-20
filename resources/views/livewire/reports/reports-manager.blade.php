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
                <h2 class="text-sm font-bold text-black uppercase mt-2">LAPORAN OPERASIONAL & KEUANGAN OUTLET</h2>
            </div>
            <div class="text-right text-xs text-black space-y-1">
                <div><strong>Periode Laporan:</strong> {{ strtoupper(str_replace('_', ' ', $period)) }}</div>
                <div><strong>Dicetak Pada:</strong> {{ date('d/m/Y H:i') }} WIB</div>
                <div><strong>Dicetak Oleh:</strong> {{ auth()->user()->name }} (Owner)</div>
            </div>
        </div>

        <!-- Print Summary Bar -->
        <div class="grid grid-cols-4 gap-2 text-xs py-2 border-b border-black font-semibold">
            <div>Total Omset: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div>Total Nota: {{ number_format($totalTransactionsCount, 0, ',', '.') }} Nota</div>
            <div>Rata-Rata: Rp {{ $totalTransactionsCount > 0 ? number_format($totalRevenue / $totalTransactionsCount, 0, ',', '.') : 0 }}</div>
            <div>Bagi Hasil Barber ({{ $commissionPercentage }}%): Rp {{ number_format(array_sum(array_column($barberReports, 'estimated_commission')), 0, ',', '.') }}</div>
        </div>

        <!-- 1. Print Table: Rekap Performa Barber -->
        <div>
            <h3 class="text-xs font-bold uppercase mb-2">1. Rekap Performa & Bagi Hasil Staf Barber ({{ $commissionPercentage }}%)</h3>
            <table class="print-table">
                <thead>
                    <tr>
                        <th>Nama Barber</th>
                        <th>Total Pangkas (Qty)</th>
                        <th>Total Omset</th>
                        <th>Estimasi Komisi Barber ({{ $commissionPercentage }}%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barberReports as $b)
                        <tr>
                            <td><strong>{{ $b['name'] }}</strong> {{ $b['role'] === 'owner' ? '(Owner)' : '' }}</td>
                            <td>{{ $b['cut_count'] }} Nota</td>
                            <td>Rp {{ number_format($b['total_revenue'], 0, ',', '.') }}</td>
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

        <!-- 2. Print Table: Riwayat Transaksi Kasir -->
        <div>
            <h3 class="text-xs font-bold uppercase mb-2">2. Rincian Riwayat Transaksi Kasir POS</h3>
            <table class="print-table">
                <thead>
                    <tr>
                        <th>Kode / Waktu Transaksi</th>
                        <th>Nama Pelanggan</th>
                        <th>Metode Pembayaran</th>
                        <th>Total Omset Nota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                        <tr>
                            <td><strong>{{ $t->transaction_code }}</strong> ({{ $t->created_at->format('d/m/Y H:i') }} WIB)</td>
                            <td>{{ $t->customer_name ?? 'Pelanggan Umum' }}</td>
                            <td>{{ strtoupper($t->payment_method) }}</td>
                            <td>Rp {{ number_format($t->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">Belum ada riwayat transaksi pada periode ini.</td>
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
                    <div class="font-bold">Kasir</div>
                    <div class="h-16"></div>
                    <div class="font-bold uppercase">{{ $cashierName ?? 'Kasir Barbershop' }}</div>
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
                <flux:subheading>Rekapitulasi omset penjualan kasir, komisi staf barber, dan riwayat transaksi.</flux:subheading>
            </div>

            <div class="flex items-center gap-3">
                <flux:select wire:model.live="period">
                    <option value="today">Hari Ini</option>
                    <option value="last_7_days">7 Hari Terakhir</option>
                    <option value="this_month">Bulan Ini</option>
                    <option value="all">Semua Waktu</option>
                </flux:select>

                <button onclick="window.print()" class="px-4 py-2.5 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xl shadow transition inline-flex items-center gap-2 whitespace-nowrap shrink-0">
                    <flux:icon name="arrow-down-tray" class="size-4" />
                    <span>Download Laporan</span>
                </button>
            </div>
        </div>

        <!-- 4 Clean Financial Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <flux:card class="p-5 space-y-2 border border-zinc-200 dark:border-zinc-700">
                <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Omset Pendapatan</div>
                <div class="text-2xl font-extrabold font-mono text-zinc-900 dark:text-white">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-zinc-400">Total dari transaksi kasir POS</div>
            </flux:card>

            <flux:card class="p-5 space-y-2 border border-zinc-200 dark:border-zinc-700">
                <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Transaksi Lunas</div>
                <div class="text-2xl font-extrabold font-mono text-zinc-900 dark:text-white">
                    {{ number_format($totalTransactionsCount, 0, ',', '.') }} <span class="text-sm font-normal text-zinc-400">Nota</span>
                </div>
                <div class="text-[11px] text-zinc-400">Transaksi selesai dikerjakan</div>
            </flux:card>

            <flux:card class="p-5 space-y-2 border border-zinc-200 dark:border-zinc-700">
                <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Rata-Rata Transaksi</div>
                <div class="text-2xl font-extrabold font-mono text-zinc-900 dark:text-white">
                    Rp {{ $totalTransactionsCount > 0 ? number_format($totalRevenue / $totalTransactionsCount, 0, ',', '.') : 0 }}
                </div>
                <div class="text-[11px] text-zinc-400">Rata-rata belanja per pelanggan</div>
            </flux:card>

            <flux:card class="p-5 space-y-2 border border-zinc-200 dark:border-zinc-700">
                <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Estimasi Komisi Barber</div>
                <div class="text-2xl font-extrabold font-mono text-zinc-900 dark:text-white">
                    Rp {{ number_format(array_sum(array_column($barberReports, 'estimated_commission')), 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-zinc-400">Bagi hasil komisi pangkas barber ({{ $commissionPercentage }}%)</div>
            </flux:card>
        </div>

        <!-- Vertical Stack Layout: Screen Display Cards -->
        <div class="space-y-6">
            
            <!-- 1. Rekapitulasi Performa & Bagi Hasil Barber -->
            <flux:card class="p-6 space-y-4 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-700">
                    <div>
                        <flux:heading size="lg">Rekap Performa & Bagi Hasil Barber</flux:heading>
                        <flux:subheading>Jumlah pangkas, total omset per barber, dan estimasi komisi ({{ $commissionPercentage }}%).</flux:subheading>
                    </div>
                </div>

                <flux:table class="w-full">
                    <flux:table.columns>
                        <flux:table.column>Nama Barber</flux:table.column>
                        <flux:table.column>Total Pangkas</flux:table.column>
                        <flux:table.column>Total Omset</flux:table.column>
                        <flux:table.column>Estimasi Komisi Barber ({{ $commissionPercentage }}%)</flux:table.column>
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
                                    {{ $b['cut_count'] }} Nota
                                </flux:table.cell>
                                <flux:table.cell class="font-mono text-xs text-zinc-900 dark:text-white font-bold">
                                    Rp {{ number_format($b['total_revenue'], 0, ',', '.') }}
                                </flux:table.cell>
                                <flux:table.cell class="font-mono text-xs text-zinc-900 dark:text-white font-bold">
                                    Rp {{ number_format($b['estimated_commission'], 0, ',', '.') }}
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="4" class="text-center py-6 text-zinc-400 text-xs">
                                    Belum ada data staf barber.
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </flux:card>

            <!-- 2. Rincian Riwayat Transaksi Kasir -->
            <flux:card class="p-6 space-y-4 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-700">
                    <div>
                        <flux:heading size="lg">Rincian Riwayat Transaksi Kasir</flux:heading>
                        <flux:subheading>Daftar nota transaksi POS yang lunas.</flux:subheading>
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
                                    <div class="font-mono font-bold text-xs text-zinc-900 dark:text-white">{{ $t->transaction_code }}</div>
                                    <div class="text-[10px] text-zinc-400">{{ $t->created_at->format('d/m/Y H:i') }} WIB</div>
                                </flux:table.cell>
                                <flux:table.cell class="text-xs font-medium">
                                    {{ $t->customer_name ?? 'Pelanggan Umum' }}
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge size="sm" color="zinc">
                                        {{ strtoupper($t->payment_method) }}
                                    </flux:badge>
                                </flux:cell>
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
