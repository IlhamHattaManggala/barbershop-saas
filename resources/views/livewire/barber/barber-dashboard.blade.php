<div class="space-y-6">
    <!-- Welcome Header with Action Link -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-zinc-200 dark:border-zinc-800">
        <div>
            <flux:heading size="xl" level="1">Dashboard Barber Workstation</flux:heading>
            <flux:subheading>Selamat bekerja, <strong>{{ auth()->user()->name }}</strong>! Ringkasan jadwal & antrean potong rambut Anda hari ini.</flux:subheading>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('barber.reservations') }}" wire:navigate class="px-4 py-2 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-medium text-xs rounded-xl transition inline-flex items-center gap-1.5 shadow-xs">
                <flux:icon icon="calendar" class="size-4" />
                <span>Buka Papan Reservasi</span>
            </a>
        </div>
    </div>

    @if(!empty($success_message))
        <flux:badge color="zinc" size="lg" class="w-full justify-between p-3 border border-zinc-200 dark:border-zinc-700">
            <span>{{ $success_message }}</span>
        </flux:badge>
    @endif

    <!-- Clean Monochrome KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Metric 1: My Total Today -->
        <flux:card class="p-5 flex items-center gap-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
            <div class="p-3 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded-xl">
                <flux:icon icon="user-group" class="size-5" />
            </div>
            <div>
                <div class="text-xs text-zinc-500 font-medium">Antrean Saya Hari Ini</div>
                <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $myTotalCount }}</div>
            </div>
        </flux:card>

        <!-- Metric 2: Completed Today -->
        <flux:card class="p-5 flex items-center gap-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
            <div class="p-3 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded-xl">
                <flux:icon icon="check-circle" class="size-5" />
            </div>
            <div>
                <div class="text-xs text-zinc-500 font-medium">Selesai Dipotong</div>
                <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $myCompletedCount }}</div>
            </div>
        </flux:card>

        <!-- Metric 3: Pending/In-Service -->
        <flux:card class="p-5 flex items-center gap-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
            <div class="p-3 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded-xl">
                <flux:icon icon="clock" class="size-5" />
            </div>
            <div>
                <div class="text-xs text-zinc-500 font-medium">Menunggu / Dipotong</div>
                <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $myPendingCount }}</div>
            </div>
        </flux:card>

        <!-- Metric 4: Total Shop Today -->
        <flux:card class="p-5 flex items-center gap-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
            <div class="p-3 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded-xl">
                <flux:icon icon="building-storefront" class="size-5" />
            </div>
            <div>
                <div class="text-xs text-zinc-500 font-medium">Total Toko Hari Ini</div>
                <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $shopTotalCount }}</div>
            </div>
        </flux:card>
    </div>

    <!-- Assigned Haircut Queue Table -->
    <flux:card class="p-6 space-y-4 border border-zinc-200 dark:border-zinc-800">
        <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-800">
            <div>
                <flux:heading size="lg">Jadwal & Antrean Potong Rambut Saya (Hari Ini)</flux:heading>
                <flux:subheading>Daftar pelanggan yang memilih Anda sebagai Barber hari ini ({{ date('d M Y') }}).</flux:subheading>
            </div>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Kode & Waktu Slot</flux:table.column>
                <flux:table.column>Pelanggan</flux:table.column>
                <flux:table.column>Layanan Pangkas</flux:table.column>
                <flux:table.column>Status Pengerjaan</flux:table.column>
                <flux:table.column>Aksi Cepat Barber</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($myReservations as $r)
                    <flux:table.row key="{{ $r->id }}">
                        <flux:table.cell class="font-mono text-xs">
                            <div class="font-bold text-zinc-900 dark:text-zinc-100">{{ $r->reservation_code }}</div>
                            <div class="text-zinc-500 text-[11px]">{{ date('H:i', strtotime($r->start_time)) }} - {{ date('H:i', strtotime($r->end_time)) }} WIB</div>
                        </flux:table.cell>

                        <flux:table.cell class="text-xs">
                            <div class="font-bold text-zinc-900 dark:text-white">{{ $r->customer_name }}</div>
                            <div class="text-zinc-400 font-mono text-[10px]">{{ $r->customer_phone }}</div>
                            @if($r->notes)
                                <div class="text-[10px] text-zinc-500 italic">"{{ $r->notes }}"</div>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell class="text-xs">
                            <div class="font-medium text-zinc-800 dark:text-zinc-200">{{ $r->service->name ?? 'Layanan' }}</div>
                            <div class="text-[10px] text-zinc-400">Rp {{ number_format($r->service->price ?? 0, 0, ',', '.') }} ({{ $r->service->duration_minutes ?? 30 }}m)</div>
                        </flux:table.cell>

                        <flux:table.cell>
                            @if($r->status === 'completed')
                                <flux:badge size="sm" color="zinc" icon="check-circle">Selesai</flux:badge>
                            @elseif($r->status === 'in_service')
                                <flux:badge size="sm" color="zinc" icon="scissors">Sedang Dipotong</flux:badge>
                            @elseif($r->status === 'cancelled')
                                <flux:badge size="sm" color="zinc">Dibatalkan</flux:badge>
                            @else
                                <flux:badge size="sm" color="zinc">Terjadwal</flux:badge>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="flex items-center gap-1.5">
                                @if($r->status !== 'completed' && $r->status !== 'cancelled')
                                    @if($r->status !== 'in_service')
                                        <flux:button wire:click="updateStatus({{ $r->id }}, 'in_service')" size="xs" variant="subtle">
                                            Mulai Potong
                                        </flux:button>
                                    @endif

                                    <flux:button wire:click="updateStatus({{ $r->id }}, 'completed')" size="xs" variant="primary" class="bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white text-white">
                                        Selesai
                                    </flux:button>
                                @else
                                    <span class="text-[10px] text-zinc-400 font-mono">Pengerjaan Selesai</span>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center py-8 text-zinc-400 text-xs">
                            Belum ada antrean reservasi potong rambut khusus Anda hari ini.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
