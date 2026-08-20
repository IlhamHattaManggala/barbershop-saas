<div class="space-y-6">
    <!-- Header with Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Kelola Barbershop (Tenants)</flux:heading>
            <flux:subheading>Manajemen seluruh outlet barbershop terdaftar di platform SaaS Anda.</flux:subheading>
        </div>

        <div class="flex items-center gap-3">
            <flux:modal.trigger name="create-tenant-modal">
                <flux:button variant="primary" icon="plus">
                    Tambah Barbershop Baru
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    @if(!empty($success_message))
        <flux:badge color="emerald" size="lg" class="w-full justify-between p-3">
            <span>{{ $success_message }}</span>
        </flux:badge>
    @endif

    <!-- Full-Width Barbershop Outlet Table -->
    <flux:card class="p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-zinc-200 dark:border-zinc-700">
            <div>
                <flux:heading size="lg">Daftar Outlet Barbershop</flux:heading>
                <flux:subheading>Total {{ $tenants->count() }} outlet barbershop pengguna platform SaaS.</flux:subheading>
            </div>

            <flux:input wire:model.live="search" placeholder="Cari outlet / owner..." icon="magnifying-glass" class="w-full sm:w-64" />
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nama Outlet</flux:table.column>
                <flux:table.column>URL Path / Slug</flux:table.column>
                <flux:table.column>Pemilik (Owner)</flux:table.column>
                <flux:table.column>Paket Langganan</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($tenants as $t)
                    <flux:table.row key="{{ $t->id }}">
                        <flux:table.cell class="font-bold text-xs">
                            {{ $t->name }}
                            <div class="text-[10px] text-zinc-400 font-normal">{{ $t->address ?? 'Alamat belum diatur' }}</div>
                        </flux:table.cell>
                        <flux:table.cell class="font-mono text-xs text-indigo-600 font-bold">
                            <a href="{{ url('/' . $t->slug) }}" target="_blank" class="hover:underline">/{{ $t->slug }}</a>
                        </flux:table.cell>
                        <flux:table.cell class="text-xs">
                            <div class="font-semibold">{{ $t->users->where('role', 'owner')->first()?->name ?? 'Pemilik' }}</div>
                            <div class="text-[10px] text-zinc-400 font-mono">{{ $t->users->where('role', 'owner')->first()?->email }}</div>
                        </flux:table.cell>
                        <flux:table.cell class="text-xs font-bold uppercase">
                            {{ $t->subscription_plan }}
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($t->status === 'active')
                                <flux:badge size="sm" color="emerald">Aktif</flux:badge>
                            @else
                                <flux:badge size="sm" color="rose">Suspended</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button wire:click="toggleStatus({{ $t->id }})" size="xs" variant="subtle">
                                {{ $t->status === 'active' ? 'Suspend' : 'Aktifkan' }}
                            </flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center py-6 text-zinc-400 text-xs">
                            Belum ada outlet barbershop terdaftar.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <!-- Flux Popup Modal: Tambah Barbershop Baru -->
    <flux:modal name="create-tenant-modal" class="md:w-[500px] space-y-6">
        <div>
            <flux:heading size="lg">Tambah Barbershop Baru</flux:heading>
            <flux:subheading>Daftarkan outlet barbershop & akun owner baru di platform SaaS Anda.</flux:subheading>
        </div>

        <form wire:submit.prevent="createTenant" class="space-y-4 text-xs">
            <div>
                <flux:label>Nama Barbershop Outlet</flux:label>
                <flux:input wire:model="name" required placeholder="Contoh: Crown Barber Studio" />
            </div>

            <div>
                <flux:label>Nama Pemilik (Owner)</flux:label>
                <flux:input wire:model="owner_name" required placeholder="Nama Lengkap Owner" />
            </div>

            <div>
                <flux:label>Email Login Pemilik</flux:label>
                <flux:input type="email" wire:model="owner_email" required placeholder="owner@barber.com" />
            </div>

            <div>
                <flux:label>Nomor Telepon / WhatsApp</flux:label>
                <flux:input wire:model="phone" placeholder="081234567890" />
            </div>

            <div>
                <flux:label>Alamat Outlet</flux:label>
                <flux:textarea wire:model="address" rows="2" placeholder="Jl. Sudirman No. 12..." />
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">
                    Daftarkan Barbershop
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
