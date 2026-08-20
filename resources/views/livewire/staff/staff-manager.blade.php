<div class="space-y-6">
    <!-- Header with Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Staf Barber & Hak Akses</flux:heading>
            <flux:subheading>Manajemen akun staf barber workstation dan kasir toko.</flux:subheading>
        </div>

        <div class="flex items-center gap-3">
            <flux:modal.trigger name="create-staff-modal">
                <flux:button variant="primary" icon="plus">
                    Tambah Akun Staf Baru
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    @if(!empty($success_message))
        <flux:badge color="emerald" size="lg" class="w-full justify-between p-3">
            <span>{{ $success_message }}</span>
        </flux:badge>
    @endif

    @if(session()->has('delete_error'))
        <flux:badge color="rose" size="lg" class="w-full justify-between p-3">
            <span>{{ session('delete_error') }}</span>
        </flux:badge>
    @endif

    <!-- Full-Width Staff Table -->
    <flux:card class="p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-zinc-200 dark:border-zinc-700">
            <div>
                <flux:heading size="lg">Daftar Staf Toko</flux:heading>
                <flux:subheading>Total {{ $staffMembers->count() }} staf barber & kasir terdaftar.</flux:subheading>
            </div>

            <flux:input wire:model.live="search" placeholder="Cari staf..." icon="magnifying-glass" class="w-full sm:w-64" />
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nama Staf</flux:table.column>
                <flux:table.column>Email / Akun Login</flux:table.column>
                <flux:table.column>Nomor WA / Telepon</flux:table.column>
                <flux:table.column>Peran Staf / Hak Akses</flux:table.column>
                <flux:table.column>Aksi Manager</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($staffMembers as $u)
                    <flux:table.row key="{{ $u->id }}">
                        <flux:table.cell class="font-bold text-xs">
                            {{ $u->name }}
                        </flux:table.cell>
                        <flux:table.cell class="text-xs font-mono text-zinc-500">
                            {{ $u->email }}
                        </flux:table.cell>
                        <flux:table.cell class="text-xs font-mono">
                            {{ $u->phone ?? '-' }}
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($u->role === 'owner')
                                <flux:badge size="sm" color="amber">Owner (Pemilik)</flux:badge>
                            @elseif($u->role === 'cashier')
                                <flux:badge size="sm" color="indigo">Kasir POS</flux:badge>
                            @else
                                <flux:badge size="sm" color="emerald">Barber Workstation</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:modal.trigger name="edit-staff-modal">
                                    <flux:button wire:click="editStaff({{ $u->id }})" size="xs" variant="subtle" icon="pencil-square">
                                        Edit
                                    </flux:button>
                                </flux:modal.trigger>

                                @if($u->id !== auth()->id())
                                    <flux:modal.trigger name="delete-staff-modal">
                                        <flux:button wire:click="confirmDeleteStaff({{ $u->id }})" size="xs" variant="subtle" class="text-rose-600 hover:bg-rose-50" icon="trash">
                                            Hapus
                                        </flux:button>
                                    </flux:modal.trigger>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center py-6 text-zinc-400 text-xs">
                            Belum ada staf terdaftar.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <!-- Flux Popup Modal: Tambah Akun Staf Baru -->
    <flux:modal name="create-staff-modal" class="md:w-[480px] space-y-6">
        <div>
            <flux:heading size="lg">Tambah Akun Staf Baru</flux:heading>
            <flux:subheading>Buatkan akun login baru untuk kasir toko atau barber workstation.</flux:subheading>
        </div>

        <form wire:submit.prevent="createStaff" class="space-y-4 text-xs">
            <div>
                <flux:label>Nama Lengkap Staf</flux:label>
                <flux:input wire:model="name" required placeholder="Contoh: Rian Barber" />
            </div>

            <div>
                <flux:label>Email Login</flux:label>
                <flux:input type="email" wire:model="email" required placeholder="rian@babershop.my.id" />
            </div>

            <div>
                <flux:label>Nomor WhatsApp</flux:label>
                <flux:input wire:model="phone" placeholder="08123456789" />
            </div>

            <div>
                <flux:label>Peran / Access Role</flux:label>
                <flux:select wire:model="role">
                    <option value="barber">Barber Workstation (Pangkas)</option>
                    <option value="cashier">Kasir POS (Pembayaran)</option>
                </flux:select>
            </div>

            <flux:text class="text-[11px] text-zinc-400">Password default untuk akun staf baru adalah: <strong>password</strong></flux:text>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">
                    Buat Akun Staf
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Flux Popup Modal: Edit Data Staf -->
    <flux:modal name="edit-staff-modal" class="md:w-[480px] space-y-6">
        <div>
            <flux:heading size="lg">Edit Akun Staf</flux:heading>
            <flux:subheading>Perbarui data profil & hak akses staf barbershop.</flux:subheading>
        </div>

        <form wire:submit.prevent="updateStaff" class="space-y-4 text-xs">
            <div>
                <flux:label>Nama Lengkap Staf</flux:label>
                <flux:input wire:model="edit_name" required placeholder="Contoh: Rian Barber" />
            </div>

            <div>
                <flux:label>Email Login</flux:label>
                <flux:input type="email" wire:model="edit_email" required placeholder="rian@babershop.my.id" />
            </div>

            <div>
                <flux:label>Nomor WhatsApp</flux:label>
                <flux:input wire:model="edit_phone" placeholder="08123456789" />
            </div>

            <div>
                <flux:label>Peran / Access Role</flux:label>
                <flux:select wire:model="edit_role">
                    <option value="barber">Barber Workstation (Pangkas)</option>
                    <option value="cashier">Kasir POS (Pembayaran)</option>
                    <option value="owner">Owner (Pemilik Toko)</option>
                </flux:select>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">
                    Simpan Perubahan
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Flux Popup Modal: Konfirmasi Hapus Staf -->
    <flux:modal name="delete-staff-modal" class="md:w-[420px] space-y-4 !pb-1">
        <div>
            <flux:heading size="lg">Konfirmasi Hapus Akun Staf</flux:heading>
            <flux:subheading class="mt-2">
                Apakah Anda yakin ingin menghapus akun staf <strong class="text-zinc-900 dark:text-white underline">{{ $deleting_staff_name ?? 'staf ini' }}</strong>? Tindakan ini tidak dapat dibatalkan.
            </flux:subheading>
        </div>

        <div class="flex items-center justify-end gap-2 pt-3">
            <flux:modal.close>
                <flux:button variant="ghost">Batal</flux:button>
            </flux:modal.close>
            <flux:modal.close>
                <flux:button wire:click="deleteStaff" variant="danger">
                    Hapus Akun Staf
                </flux:button>
            </flux:modal.close>
        </div>
    </flux:modal>
</div>
