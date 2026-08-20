<div class="space-y-6">
    
    <!-- Cashier Header (Matching Owner Dashboard Header Style) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Dashboard Kasir Barbershop</flux:heading>
            <flux:subheading>Ringkasan transaksi kasir, omset harian, & jadwal antrean outlet {{ auth()->user()->tenant->name ?? 'Gentlemen Barber' }}</flux:subheading>
        </div>

        <div class="flex items-center gap-3">
            <flux:button variant="primary" icon="shopping-bag" :href="route('pos')">
                Buka Mesin Kasir POS
            </flux:button>
            <flux:button variant="subtle" icon="calendar" :href="route('reservations')">
                Papan Antrean
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    <!-- Stat Cards (Matching Owner Dashboard Neutral Card Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <flux:card class="p-5 space-y-2">
            <flux:subheading size="sm" class="font-medium">Pendapatan Hari Ini</flux:subheading>
            <flux:heading size="xl" class="font-extrabold">Rp {{ number_format($todayTotalAmount, 0, ',', '.') }}</flux:heading>
            <flux:text class="text-xs text-zinc-500">Dari {{ $totalTransactionsCount }} transaksi hari ini</flux:text>
        </flux:card>

        <flux:card class="p-5 space-y-2">
            <flux:subheading size="sm" class="font-medium">Pembayaran Tunai</flux:subheading>
            <flux:heading size="xl" class="font-extrabold">Rp {{ number_format($cashTotal, 0, ',', '.') }}</flux:heading>
            <flux:text class="text-xs text-zinc-500">Uang fisik di laci kasir</flux:text>
        </flux:card>

        <flux:card class="p-5 space-y-2">
            <flux:subheading size="sm" class="font-medium">QRIS / Transfer Bank</flux:subheading>
            <flux:heading size="xl" class="font-extrabold">Rp {{ number_format($qrisTotal, 0, ',', '.') }}</flux:heading>
            <flux:text class="text-xs text-zinc-500">Masuk rekening digital</flux:text>
        </flux:card>

        <flux:card class="p-5 space-y-2">
            <flux:subheading size="sm" class="font-medium">Jadwal Antrean Hari Ini</flux:subheading>
            <flux:heading size="xl" class="font-extrabold">{{ $todayReservationsCount }} Pelanggan</flux:heading>
            <flux:text class="text-xs text-zinc-500">Di papan reservasi hari ini</flux:text>
        </flux:card>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left 8 Cols: Today's Transactions Table -->
        <div class="lg:col-span-8 space-y-4">
            <flux:card class="p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-700">
                    <div>
                        <flux:heading size="lg">Riwayat Transaksi Kasir Hari Ini</flux:heading>
                        <flux:subheading>Daftar transaksi yang diproses di outlet hari ini.</flux:subheading>
                    </div>
                    <flux:badge size="sm" color="zinc">{{ $totalTransactionsCount }} Transaksi</flux:badge>
                </div>

                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>No Transaksi</flux:table.column>
                        <flux:table.column>Pelanggan</flux:table.column>
                        <flux:table.column>Metode</flux:table.column>
                        <flux:table.column class="text-right">Total</flux:table.column>
                        <flux:table.column class="text-center">Struk WA</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse($todayTransactions as $trx)
                            <flux:table.row>
                                <flux:table.cell class="font-mono font-bold">
                                    {{ $trx->transaction_number }}
                                    <div class="text-[10px] text-zinc-400 font-normal">{{ $trx->created_at->format('H:i') }} WIB</div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <span class="font-medium">{{ $trx->customer_name }}</span>
                                    <div class="text-[10px] text-zinc-400">{{ $trx->customer_phone ?: '-' }}</div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge size="sm" color="zinc" class="uppercase">
                                        {{ $trx->payment_method }}
                                    </flux:badge>
                                </flux:table.cell>
                                <flux:table.cell class="text-right font-bold font-mono">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                </flux:table.cell>
                                <flux:table.cell class="text-center">
                                    @if($trx->customer_phone)
                                        <flux:button size="xs" variant="subtle" icon="chat-bubble-left-ellipsis" href="https://wa.me/{{ $trx->customer_phone }}?text=Halo%20{{ urlencode($trx->customer_name) }},%20terima%20kasih%20telah%20berkunjung%20ke%20Gentlemen%20Barber%20Studio.%20Total%20pembayaran:%20Rp%20{{ number_format($trx->total_amount, 0, ',', '.') }}." target="_blank">
                                            WA Struk
                                        </flux:button>
                                    @else
                                        <span class="text-zinc-400 text-xs">-</span>
                                    @endif
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="5" class="text-center py-6 text-zinc-400">
                                    Belum ada transaksi diproses hari ini.
                                </flux:cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </flux:card>
        </div>

        <!-- Right 4 Cols: Quick POS & Low Stock Alert -->
        <div class="lg:col-span-4 space-y-4">
            
            <flux:card class="p-5 space-y-3">
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-2">
                    <flux:heading size="sm" class="font-semibold">Quick POS Kiosk</flux:heading>
                    <flux:badge size="sm" color="zinc">Layar Penuh</flux:badge>
                </div>
                <flux:text class="text-xs">
                    Siap melayani transaksi pelanggan? Klik tombol di bawah untuk membuka Mesin Kasir POS layar penuh.
                </flux:text>
                <flux:button variant="primary" class="w-full justify-center" icon="shopping-bag" :href="route('pos')">
                    Mulai Transaksi POS
                </flux:button>
            </flux:card>

            <flux:card class="p-5 space-y-3">
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-2">
                    <flux:heading size="sm" class="font-semibold">Stok Produk Menipis</flux:heading>
                    <flux:badge size="sm" color="zinc">{{ $lowStockProducts->count() }} Item</flux:badge>
                </div>

                <div class="space-y-2">
                    @forelse($lowStockProducts as $p)
                        <div class="flex items-center justify-between p-2.5 rounded-lg border border-zinc-200 dark:border-zinc-700 text-xs">
                            <span class="font-medium text-zinc-800 dark:text-zinc-200 truncate">{{ $p->name }}</span>
                            <span class="font-bold text-rose-500 font-mono">Sisa: {{ $p->stock }}</span>
                        </div>
                    @empty
                        <div class="text-center py-4 text-xs text-zinc-400">
                            Semua stok produk aman & cukup.
                        </div>
                    @endforelse
                </div>
            </flux:card>

        </div>

    </div>

</div>
