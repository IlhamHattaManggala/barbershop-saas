<x-layouts::app :title="__('Dashboard')">
    @php
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $tenant = $user->tenant;
        
        if ($isSuperAdmin) {
            // SuperAdmin Platform Metrics
            $totalTenants = \App\Models\Tenant::count();
            $activeTenants = \App\Models\Tenant::where('status', 'active')->count();
            $totalUsers = \App\Models\User::count();
            $tenantsList = \App\Models\Tenant::with('users')->latest()->get();
            $totalPlatformRevenue = 15500000; // Total SaaS MRR
        } else {
            // Tenant Operational Metrics
            $todayTransactions = \App\Models\Transaction::where('status', 'paid')
                ->when($tenant, fn($q) => $q->where('tenant_id', $tenant->id))
                ->whereDate('created_at', now())
                ->get();
                
            $omsetToday = $todayTransactions->sum('total_amount');
            $totalTxCount = $todayTransactions->count();
            
            $todayReservations = \App\Models\Reservation::when($tenant, fn($q) => $q->where('tenant_id', $tenant->id))
                ->whereDate('reservation_date', now())
                ->get();
                
            $activeReservationsCount = $todayReservations->whereIn('status', ['pending', 'confirmed'])->count();
            
            $lowStockProducts = \App\Models\Product::when($tenant, fn($q) => $q->where('tenant_id', $tenant->id))
                ->whereRaw('stock <= min_stock')
                ->get();
                
            $recentTransactions = \App\Models\Transaction::when($tenant, fn($q) => $q->where('tenant_id', $tenant->id))
                ->latest()
                ->take(5)
                ->get();
        }
    @endphp

    <div class="space-y-6">
        
        @if($isSuperAdmin)
            <!-- SUPERADMIN SAAS PLATFORM DASHBOARD -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <flux:heading size="xl" level="1">SuperAdmin SaaS Control Panel</flux:heading>
                    <flux:subheading>Pengelolaan Platform Multi-Tenant Barbershop & Langganan SaaS</flux:subheading>
                </div>

                <div class="flex items-center gap-3">
                    <flux:button variant="primary" icon="plus" :href="route('tenants')" wire:navigate>
                        Tambah Barbershop Baru
                    </flux:button>
                </div>
            </div>

            <flux:separator variant="subtle" />

            <!-- SuperAdmin Stat Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <flux:card class="p-5 space-y-2">
                    <flux:subheading size="sm" class="font-medium">Total Barbershop (Tenants)</flux:subheading>
                    <flux:heading size="xl" class="font-extrabold">{{ $totalTenants }} Outlet</flux:heading>
                    <flux:text class="text-xs text-zinc-500">{{ $activeTenants }} Outlet Aktif Berlangganan</flux:text>
                </flux:card>

                <flux:card class="p-5 space-y-2">
                    <flux:subheading size="sm" class="font-medium">Total Pendapatan SaaS (MRR)</flux:subheading>
                    <flux:heading size="xl" class="font-extrabold">Rp {{ number_format($totalPlatformRevenue, 0, ',', '.') }}</flux:heading>
                    <flux:text class="text-xs text-zinc-500">Bulan Ini</flux:text>
                </flux:card>

                <flux:card class="p-5 space-y-2">
                    <flux:subheading size="sm" class="font-medium">Total Pengguna Terdaftar</flux:subheading>
                    <flux:heading size="xl" class="font-extrabold">{{ $totalUsers }} User</flux:heading>
                    <flux:text class="text-xs text-zinc-500">Lintas Seluruh Role Platform</flux:text>
                </flux:card>

                <flux:card class="p-5 space-y-2">
                    <flux:subheading size="sm" class="font-medium">Paket Langganan Aktif</flux:subheading>
                    <flux:heading size="xl" class="font-extrabold">Pro & Enterprise</flux:heading>
                    <flux:text class="text-xs text-zinc-500">Performa server 99.9% uptime</flux:text>
                </flux:card>
            </div>

            <!-- Barbershop Tenants Table -->
            <flux:card class="p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-700">
                    <div>
                        <flux:heading size="lg">Daftar Barbershop (Tenant) Terdaftar</flux:heading>
                        <flux:subheading>Kelola seluruh outlet barbershop pengguna platform SaaS Anda.</flux:subheading>
                    </div>
                </div>

                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Nama Barbershop</flux:table.column>
                        <flux:table.column>Domain / Slug</flux:table.column>
                        <flux:table.column>Pemilik (Owner)</flux:table.column>
                        <flux:table.column>Paket</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse($tenantsList as $t)
                            <flux:table.row key="{{ $t->id }}">
                                <flux:table.cell class="font-bold text-xs">
                                    {{ $t->name }}
                                </flux:table.cell>
                                <flux:table.cell class="font-mono text-xs text-zinc-500">
                                    {{ $t->slug }}.barbersaas.id
                                </flux:table.cell>
                                <flux:table.cell class="text-xs">
                                    {{ $t->users->where('role', 'owner')->first()?->name ?? 'Pemilik' }}
                                </flux:table.cell>
                                <flux:table.cell class="text-xs uppercase font-bold">
                                    {{ $t->subscription_plan }}
                                </flux:table.cell>
                                <flux:table.cell>
                                    @if($t->status === 'active')
                                        <flux:badge size="sm" color="emerald">Aktif</flux:badge>
                                    @else
                                        <flux:badge size="sm" color="rose">Non-Aktif</flux:badge>
                                    @endif
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="5" class="text-center py-6 text-zinc-400 text-xs">
                                    Belum ada tenant terdaftar.
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </flux:card>

        @else

            <!-- TENANT OPERATIONAL DASHBOARD (OWNER / CASHIER / BARBER / CUSTOMER) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <flux:heading size="xl" level="1">Dashboard Analitik {{ $tenant ? $tenant->name : 'Barbershop' }}</flux:heading>
                    <flux:subheading>{{ $tenant ? $tenant->address : 'Operasional Barbershop' }} &bull; URL Booking: <a href="{{ url('/' . ($tenant->slug ?? 'gentlemen-barber')) }}" target="_blank" class="font-mono font-bold text-indigo-600 underline">/{{ $tenant->slug ?? 'gentlemen-barber' }}</a></flux:subheading>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @if($user->isCashier())
                        <flux:button variant="primary" icon="shopping-bag" :href="route('pos')" wire:navigate>
                            Buka Kasir POS
                        </flux:button>
                    @endif
                    @if($user->isOwner())
                        <flux:button variant="primary" icon="archive-box" :href="route('products')" wire:navigate>
                            Kelola Stok
                        </flux:button>
                        <flux:button variant="subtle" icon="users" :href="route('staff')" wire:navigate>
                            Staf Barber
                        </flux:button>
                    @endif
                </div>
            </div>

            <flux:separator variant="subtle" />

            <!-- Restrained Monochromatic Metrics Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <flux:card class="p-5 space-y-2">
                    <flux:subheading size="sm" class="font-medium">Total Omset Hari Ini</flux:subheading>
                    <flux:heading size="xl" class="font-extrabold text-indigo-600">Rp {{ number_format($omsetToday, 0, ',', '.') }}</flux:heading>
                    <flux:text class="text-xs text-zinc-500">Dari {{ $totalTxCount }} transaksi POS terbayar</flux:text>
                </flux:card>

                <flux:card class="p-5 space-y-2">
                    <flux:subheading size="sm" class="font-medium">Total Transaksi POS</flux:subheading>
                    <flux:heading size="xl" class="font-extrabold">{{ $totalTxCount }} Transaksi</flux:heading>
                    <flux:text class="text-xs text-zinc-500">Jasa pangkas & retail produk</flux:text>
                </flux:card>

                <flux:card class="p-5 space-y-2">
                    <flux:subheading size="sm" class="font-medium">Reservasi Pangkas Hari Ini</flux:subheading>
                    <flux:heading size="xl" class="font-extrabold">{{ $activeReservationsCount }} Booking</flux:heading>
                    <flux:text class="text-xs text-zinc-500">Di papan antrean workstation</flux:text>
                </flux:card>

                <flux:card class="p-5 space-y-2">
                    <flux:subheading size="sm" class="font-medium">Stok Produk Kritis</flux:subheading>
                    <flux:heading size="xl" class="font-extrabold text-rose-500">{{ $lowStockProducts->count() }} Item</flux:heading>
                    <flux:text class="text-xs text-zinc-500">{{ $lowStockProducts->first()?->name ?? 'Stok produk aman' }}</flux:text>
                </flux:card>

            </div>

            <!-- Main Content 2-Column Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left 2 Cols: Recent Transactions & Barber Performance -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Table 1: Transaksi Kasir Terbaru -->
                    <flux:card class="p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-700">
                            <div>
                                <flux:heading size="lg">Transaksi Kasir Terbaru</flux:heading>
                                <flux:subheading>Daftar transaksi harian outlet barbershop Anda.</flux:subheading>
                            </div>
                            <flux:button size="xs" variant="ghost" :href="route('pos')" wire:navigate>Ke Kasir POS &rarr;</flux:button>
                        </div>

                        <flux:table>
                            <flux:table.columns>
                                <flux:table.column>No. Transaksi</flux:table.column>
                                <flux:table.column>Pelanggan</flux:table.column>
                                <flux:table.column>Metode</flux:table.column>
                                <flux:table.column>Total Omset</flux:table.column>
                                <flux:table.column>Status</flux:table.column>
                            </flux:table.columns>

                            <flux:table.rows>
                                @forelse($recentTransactions as $tx)
                                    <flux:table.row key="{{ $tx->id }}">
                                        <flux:table.cell class="font-mono text-xs font-bold">
                                            {{ $tx->transaction_number }}
                                        </flux:table.cell>
                                        <flux:table.cell class="font-semibold text-xs">
                                            {{ $tx->customer_name ?? 'Pelanggan Umum' }}
                                        </flux:table.cell>
                                        <flux:table.cell class="uppercase text-xs font-mono">
                                            {{ $tx->payment_method }}
                                        </flux:table.cell>
                                        <flux:table.cell class="font-bold text-xs text-indigo-600">
                                            Rp {{ number_format($tx->total_amount, 0, ',', '.') }}
                                        </flux:table.cell>
                                        <flux:table.cell>
                                            <flux:badge size="sm" color="emerald">Lunas</flux:badge>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @empty
                                    <flux:table.row>
                                        <flux:table.cell colspan="5" class="text-center py-6 text-zinc-400 text-xs">
                                            Belum ada transaksi hari ini.
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endforelse
                            </flux:table.rows>
                        </flux:table>
                    </flux:card>

                    <!-- Table 2: Kinerja & Komisi Staf Barber Hari Ini -->
                    @if($user->isOwner())
                        <flux:card class="p-6 space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-700">
                                <div>
                                    <flux:heading size="lg">Performa Staf Barber Hari Ini</flux:heading>
                                    <flux:subheading>Rekap pangkas & omset yang dihasilkan oleh tim barber Anda.</flux:subheading>
                                </div>
                                <flux:button size="xs" variant="ghost" :href="route('staff')" wire:navigate>Kelola Staf &rarr;</flux:button>
                            </div>

                            <flux:table>
                                <flux:table.columns>
                                    <flux:table.column>Nama Barber</flux:table.column>
                                    <flux:table.column>Status</flux:table.column>
                                    <flux:table.column>Nomor HP / WA</flux:table.column>
                                    <flux:table.column>Peran Staf</flux:table.column>
                                </flux:table.columns>

                                <flux:table.rows>
                                    @php
                                        $barbers = \App\Models\User::where('tenant_id', $tenant?->id ?? 1)->whereIn('role', ['barber', 'cashier'])->get();
                                    @endphp
                                    @forelse($barbers as $b)
                                        <flux:table.row key="{{ $b->id }}">
                                            <flux:table.cell class="font-bold text-xs">
                                                {{ $b->name }}
                                            </flux:table.cell>
                                            <flux:table.cell>
                                                <flux:badge size="sm" color="emerald">Standby Workstation</flux:badge>
                                            </flux:table.cell>
                                            <flux:table.cell class="font-mono text-xs text-zinc-500">
                                                {{ $b->phone ?? '-' }}
                                            </flux:table.cell>
                                            <flux:table.cell class="text-xs uppercase font-semibold">
                                                {{ $b->role }}
                                            </flux:table.cell>
                                        </flux:table.row>
                                    @empty
                                        <flux:table.row>
                                            <flux:table.cell colspan="4" class="text-center py-6 text-zinc-400 text-xs">
                                                Belum ada staf barber terdaftar.
                                            </flux:table.cell>
                                        </flux:table.row>
                                    @endforelse
                                </flux:table.rows>
                            </flux:table>
                        </flux:card>
                    @endif
                </div>

                <!-- Right 1 Col: Workstation Antrean & Low Stock -->
                <div class="space-y-6">
                    
                    <!-- Antrean Workstation Card -->
                    <flux:card class="p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-700">
                            <flux:heading size="lg">Antrean Workstation</flux:heading>
                            <flux:badge size="sm" color="zinc">{{ $todayReservations->count() }} Jadwal</flux:badge>
                        </div>

                        <div class="space-y-3">
                            @forelse($todayReservations as $rsv)
                                <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs">{{ $rsv->customer_name }}</span>
                                        <span class="font-mono text-[11px] text-indigo-600 font-bold">{{ substr($rsv->start_time, 0, 5) }} WIB</span>
                                    </div>
                                    <flux:text class="text-xs">
                                        Barber: <strong>{{ $rsv->barber?->name ?? 'Bebas' }}</strong> &bull; {{ $rsv->service?->name }}
                                    </flux:text>
                                </div>
                            @empty
                                <flux:text class="text-center py-4 text-xs text-zinc-400">
                                    Tidak ada jadwal reservasi hari ini.
                                </flux:text>
                            @endforelse
                        </div>
                    </flux:card>

                    <!-- Peringatan Stok Low Card -->
                    <flux:card class="p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-700">
                            <flux:heading size="lg">Peringatan Stok Low</flux:heading>
                            <flux:badge size="sm" color="rose">{{ $lowStockProducts->count() }} Item</flux:badge>
                        </div>

                        <div class="space-y-2">
                            @forelse($lowStockProducts as $prod)
                                <div class="flex items-center justify-between p-2.5 rounded-lg bg-rose-50/50 dark:bg-zinc-900 border border-rose-200 dark:border-zinc-700 text-xs">
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $prod->name }}</div>
                                        <div class="text-[10px] text-zinc-400">Kategori: {{ $prod->category }}</div>
                                    </div>
                                    <flux:badge size="sm" color="rose">Sisa: {{ $prod->stock }}</flux:badge>
                                </div>
                            @empty
                                <flux:text class="text-center py-2 text-xs text-zinc-400">
                                    Seluruh stok produk aman.
                                </flux:text>
                            @endforelse
                        </div>

                        @if($user->isOwner())
                            <flux:button size="xs" variant="subtle" class="w-full" :href="route('products')" wire:navigate>
                                Ke Menu Inventaris & Restok &rarr;
                            </flux:button>
                        @endif
                    </flux:card>

                </div>

            </div>

        @endif

    </div>
</x-layouts::app>
