<div class="space-y-6">
    <!-- Header with Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Papan Workstation & Reservasi</flux:heading>
            <flux:subheading>Pusat kendali jadwal pangkas, alur oper barber, dan integrasi kasir POS.</flux:subheading>
        </div>

        <div class="flex items-center gap-3">
            <flux:modal.trigger name="create-reservation-modal">
                <flux:button variant="primary" icon="plus" class="bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white text-white cursor-pointer">
                    Tambah Booking Baru
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    <!-- Alert Notification -->
    @if(!empty($success_message))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ $success_message }}</span>
            </div>
            <button type="button" wire:click="$set('success_message', '')" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- Filter Bar -->
    <flux:card class="p-4 border border-zinc-200 dark:border-zinc-800">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <flux:label>Filter Tanggal</flux:label>
                <flux:input type="date" wire:model.live="reservation_date" class="mt-1" />
            </div>

            <div>
                <flux:label>Filter Status Antrean</flux:label>
                <flux:select wire:model.live="status_filter" class="mt-1">
                    <option value="all">Semua Status</option>
                    <option value="pending">Booking Baru (Pending)</option>
                    <option value="confirmed">Terkonfirmasi (Di Tempat)</option>
                    <option value="in_service">Sedang Dicukur (In Service)</option>
                    <option value="completed">Selesai / Lunas</option>
                    <option value="cancelled">Dibatalkan</option>
                </flux:select>
            </div>

            <div>
                <flux:label>Filter Barber Specialist</flux:label>
                @if(auth()->check() && auth()->user()->role === 'barber')
                    <flux:input value="Tugas Saya ({{ auth()->user()->name }})" disabled class="mt-1 font-bold" />
                @else
                    <flux:select wire:model.live="barber_filter" class="mt-1">
                        <option value="all">Semua Barber</option>
                        @foreach($barbers as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </flux:select>
                @endif
            </div>
        </div>
    </flux:card>

    <!-- Full-Width Queue Board Table -->
    <flux:card class="p-6 border border-zinc-200 dark:border-zinc-800 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-zinc-200 dark:border-zinc-800">
            <div>
                <flux:heading size="lg">Daftar Antrean & Reservasi</flux:heading>
                <flux:subheading>Daftar reservasi terkonfirmasi & alur kerja kasir POS.</flux:subheading>
            </div>

            <div class="flex items-center gap-2">
                <flux:badge color="zinc">{{ $reservations->count() }} Booking</flux:badge>
            </div>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Kode / Jam</flux:table.column>
                <flux:table.column>Pelanggan</flux:table.column>
                <flux:table.column>Layanan Pangkas</flux:table.column>
                <flux:table.column>Barber Penanggung Jawab</flux:table.column>
                <flux:table.column>Status Antrean</flux:table.column>
                <flux:table.column>Aksi Operasional</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($reservations as $r)
                    <flux:table.row key="{{ $r->id }}">
                        <flux:table.cell>
                            <div class="font-mono font-bold text-xs text-zinc-900 dark:text-zinc-100">{{ $r->reservation_code }}</div>
                            <div class="text-[11px] text-zinc-500 font-bold mt-0.5">{{ substr($r->start_time, 0, 5) }} - {{ substr($r->end_time, 0, 5) }} WIB</div>
                        </flux:table.cell>
                        
                        <flux:table.cell>
                            <div class="font-bold text-xs text-zinc-900 dark:text-white">{{ $r->customer_name }}</div>
                            <div class="text-[11px] text-zinc-400 font-mono">{{ $r->customer_phone }}</div>
                        </flux:table.cell>
                        
                        <flux:table.cell class="text-xs font-medium">
                            <div>{{ $r->service?->name ?? 'Layanan Pangkas' }}</div>
                            @if($r->notes)
                                <div class="text-[10px] text-zinc-400 truncate max-w-xs" title="{{ $r->notes }}">Catatan: {{ $r->notes }}</div>
                            @endif
                        </flux:table.cell>
                        
                        <flux:table.cell class="text-xs font-bold text-zinc-700 dark:text-zinc-300">
                            <div class="flex items-center gap-1.5">
                                <span>{{ $r->barber?->name ?? 'Bebas (Siapa Saja Ready)' }}</span>
                                @if(in_array($r->status, ['pending', 'confirmed', 'in_service']))
                                    <button 
                                        type="button" 
                                        wire:click="openReassignModal({{ $r->id }})" 
                                        class="px-1.5 py-0.5 text-[10px] bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 rounded font-semibold transition cursor-pointer whitespace-nowrap"
                                        title="Oper ke Barber Ready Lain"
                                    >
                                        Oper Barber
                                    </button>
                                @endif
                            </div>
                        </flux:table.cell>
                        
                        <flux:table.cell>
                            @if($r->status === 'confirmed')
                                <flux:badge color="blue" size="sm">Tersedia (Di Tempat)</flux:badge>
                            @elseif($r->status === 'in_service')
                                <flux:badge color="purple" size="sm">Sedang Dicukur</flux:badge>
                            @elseif($r->status === 'completed')
                                <flux:badge color="emerald" size="sm" icon="check-circle">Selesai / Lunas</flux:badge>
                            @elseif($r->status === 'cancelled')
                                <flux:badge color="rose" size="sm">Dibatalkan</flux:badge>
                            @else
                                <flux:badge color="amber" size="sm">Booking Baru</flux:badge>
                            @endif
                        </flux:table.cell>
                        
                        <flux:table.cell>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <!-- Tombol Mulai Cukur -->
                                @if(in_array($r->status, ['pending', 'confirmed']))
                                    <button 
                                        type="button" 
                                        wire:click="updateStatus({{ $r->id }}, 'in_service')" 
                                        class="px-2.5 py-1 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition shadow-2xs cursor-pointer whitespace-nowrap"
                                    >
                                        Mulai Cukur
                                    </button>
                                @endif

                                <!-- Tombol 1-Click Checkout POS -->
                                @if($r->status !== 'completed' && $r->status !== 'cancelled')
                                    <button 
                                        type="button" 
                                        wire:click="sendToPosCheckout({{ $r->id }})" 
                                        class="px-2.5 py-1 text-xs font-bold bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg transition shadow-2xs cursor-pointer whitespace-nowrap flex items-center gap-1"
                                    >
                                        <flux:icon name="shopping-bag" class="size-3.5" />
                                        <span>Checkout POS</span>
                                    </button>
                                @endif

                                <!-- Tombol Batalkan -->
                                @if($r->status !== 'completed' && $r->status !== 'cancelled')
                                    <button 
                                        type="button" 
                                        wire:click="updateStatus({{ $r->id }}, 'cancelled')" 
                                        class="p-1 text-zinc-400 hover:text-red-600 rounded cursor-pointer"
                                        title="Batalkan Reservasi"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center py-8 text-zinc-400 text-xs">
                            Belum ada reservasi terdaftar pada filter ini.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <!-- MODAL 1: Oper Barber (Reassign Barber Modal) -->
    @if($show_reassign_modal)
        <div class="fixed inset-0 z-50 bg-zinc-950/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl max-w-sm w-full p-5 space-y-4 shadow-xl border border-zinc-200 dark:border-zinc-800">
                <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950 dark:text-white">Oper ke Barber Ready</h3>
                        <p class="text-xs text-zinc-500">Pilih barber lain yang sedang kosong.</p>
                    </div>
                    <button type="button" wire:click="closeReassignModal" class="p-1 text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 rounded cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-zinc-800 dark:text-zinc-200">Pilih Kapster / Barber Baru:</label>
                    <select wire:model="reassign_barber_id" class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl px-3 py-2 text-xs font-bold text-zinc-900 dark:text-white focus:outline-none">
                        <option value="">-- Pilih Barber Ready --</option>
                        @foreach($barbers as $b)
                            <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->role === 'owner' ? 'Owner' : 'Barber' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2 flex justify-end gap-2 border-t border-zinc-100 dark:border-zinc-800">
                    <button type="button" wire:click="closeReassignModal" class="px-3.5 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-semibold text-xs rounded-xl cursor-pointer">
                        Batal
                    </button>
                    <button type="button" wire:click="saveReassignBarber" class="px-3.5 py-1.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-semibold text-xs rounded-xl shadow-2xs cursor-pointer">
                        Oper Sekarang
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL 2: Tambah Booking Baru -->
    <flux:modal name="create-reservation-modal" class="md:w-[500px] space-y-6">
        <div>
            <flux:heading size="lg">Tambah Booking Baru</flux:heading>
            <flux:subheading>Daftarkan jadwal antrean pangkas baru untuk pelanggan.</flux:subheading>
        </div>

        <form wire:submit.prevent="createReservation" class="space-y-4 text-xs">
            <div>
                <flux:label>Nama Pelanggan</flux:label>
                <flux:input wire:model="customer_name" required placeholder="Contoh: Doni Setiawan" />
            </div>

            <div>
                <flux:label>Nomor WhatsApp</flux:label>
                <flux:input wire:model="customer_phone" required placeholder="081234567890" />
            </div>

            <div>
                <flux:label>Pilih Layanan Pangkas</flux:label>
                <flux:select wire:model="service_id" required>
                    <option value="">-- Pilih Layanan Pangkas --</option>
                    @foreach($services as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} (Rp {{ number_format($s->price, 0, ',', '.') }})</option>
                    @endforeach
                </flux:select>
            </div>

            <div>
                <flux:label>Pilih Barber (Opsional)</flux:label>
                <flux:select wire:model="barber_user_id">
                    <option value="">-- Bebas (Siapa Saja) --</option>
                    @foreach($barbers as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </flux:select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <flux:label>Tanggal Booking</flux:label>
                    <flux:input type="date" wire:model="reservation_date" required />
                </div>
                <div>
                    <flux:label>Jam Mulai</flux:label>
                    <flux:input type="time" wire:model="start_time" required />
                </div>
            </div>

            <div>
                <flux:label>Catatan Khusus (Model Rambut)</flux:label>
                <flux:textarea wire:model="notes" rows="2" placeholder="Contoh: Undercut Fade, cuci ekstra..." />
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">
                    Simpan Reservasi Baru
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
