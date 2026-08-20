<div class="space-y-6">
    <!-- Header with Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Papan Workstation & Reservasi</flux:heading>
            <flux:subheading>Penjadwalan jam & antrean barber dan booking pelanggan online.</flux:subheading>
        </div>

        <div class="flex items-center gap-3">
            <flux:modal.trigger name="create-reservation-modal">
                <flux:button variant="primary" icon="plus" class="bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white text-white">
                    Tambah Booking Baru
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    @if(!empty($success_message))
        <flux:badge color="zinc" size="lg" class="w-full justify-between p-3 border border-zinc-200 dark:border-zinc-700">
            <span>{{ $success_message }}</span>
        </flux:badge>
    @endif

    <!-- Full-Width Reservations Table -->
    <flux:card class="p-6 border border-zinc-200 dark:border-zinc-800 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-zinc-200 dark:border-zinc-800">
            <div>
                <flux:heading size="lg">Daftar Reservasi Hari Ini</flux:heading>
                <flux:subheading>Total {{ $reservations->count() }} antrean reservasi workstation terdaftar.</flux:subheading>
            </div>

            <flux:badge color="zinc">{{ $reservations->count() }} Total Booking</flux:badge>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Kode / Jam</flux:table.column>
                <flux:table.column>Pelanggan</flux:table.column>
                <flux:table.column>Layanan Pangkas</flux:table.column>
                <flux:table.column>Barber Penanggung Jawab</flux:table.column>
                <flux:table.column>Status Antrean</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($reservations as $r)
                    <flux:table.row key="{{ $r->id }}">
                        <flux:table.cell>
                            <div class="font-mono font-bold text-xs text-zinc-900 dark:text-zinc-100">{{ $r->reservation_code }}</div>
                            <div class="text-[11px] text-zinc-500 font-bold">{{ substr($r->start_time, 0, 5) }} - {{ substr($r->end_time, 0, 5) }} WIB</div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="font-bold text-xs text-zinc-900 dark:text-white">{{ $r->customer_name }}</div>
                            <div class="text-[11px] text-zinc-400 font-mono">{{ $r->customer_phone }}</div>
                        </flux:table.cell>
                        <flux:table.cell class="text-xs font-medium">
                            {{ $r->service?->name }}
                        </flux:table.cell>
                        <flux:table.cell class="text-xs font-bold text-zinc-700 dark:text-zinc-300">
                            {{ $r->barber?->name ?? 'Bebas (Siapa Saja)' }}
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($r->status === 'confirmed')
                                <flux:badge color="zinc" size="sm">Terkonfirmasi</flux:badge>
                            @elseif($r->status === 'completed')
                                <flux:badge color="zinc" size="sm" icon="check-circle">Selesai</flux:badge>
                            @elseif($r->status === 'cancelled')
                                <flux:badge color="zinc" size="sm">Batal</flux:badge>
                            @else
                                <flux:badge color="zinc" size="sm">Pending</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-1.5">
                                @if($r->status !== 'completed')
                                    <flux:button wire:click="updateStatus({{ $r->id }}, 'completed')" size="xs" variant="subtle">
                                        Selesai
                                    </flux:button>
                                @endif
                                @if($r->status !== 'cancelled')
                                    <flux:button wire:click="updateStatus({{ $r->id }}, 'cancelled')" size="xs" variant="subtle" class="text-red-600 hover:text-red-700">
                                        Batal
                                    </flux:button>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center py-8 text-zinc-400 text-xs">
                            Belum ada reservasi terdaftar hari ini.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <!-- Flux Popup Modal: Tambah Booking Baru -->
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
