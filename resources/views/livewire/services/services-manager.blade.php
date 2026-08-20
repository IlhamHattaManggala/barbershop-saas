<div class="space-y-6">
    <!-- Header with Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Layanan Pangkas & Tarif</flux:heading>
            <flux:subheading>Katalog tarif potong rambut, coloring, hair spa, dan perawatan barber.</flux:subheading>
        </div>

        <div class="flex items-center gap-3">
            <flux:modal.trigger name="create-service-modal">
                <flux:button variant="primary" icon="plus">
                    Tambah Layanan Baru
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    @if(!empty($success_message))
        <flux:badge color="emerald" size="lg" class="w-full justify-between p-3">
            <span>{{ $success_message }}</span>
        </flux:badge>
    @endif

    <!-- Full-Width Services Table -->
    <flux:card class="p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-zinc-200 dark:border-zinc-700">
            <div>
                <flux:heading size="lg">Katalog Layanan Barbershop</flux:heading>
                <flux:subheading>Total {{ $services->count() }} paket layanan pangkas & perawatan terdaftar.</flux:subheading>
            </div>

            <flux:input wire:model.live="search" placeholder="Cari layanan..." icon="magnifying-glass" class="w-full sm:w-64" />
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nama Layanan</flux:table.column>
                <flux:table.column>Estimasi Durasi</flux:table.column>
                <flux:table.column>Harga Jasa</flux:table.column>
                <flux:table.column>Status Status</flux:table.column>
                <flux:table.column>Aksi Manager</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($services as $s)
                    <flux:table.row key="{{ $s->id }}">
                        <flux:table.cell class="font-bold text-xs">
                            {{ $s->name }}
                            <div class="text-[10px] text-zinc-400 font-normal">{{ $s->description }}</div>
                        </flux:table.cell>
                        <flux:table.cell class="text-xs font-mono">
                            {{ $s->duration_minutes }} Menit
                        </flux:table.cell>
                        <flux:table.cell class="font-bold text-xs text-indigo-600">
                            Rp {{ number_format($s->price, 0, ',', '.') }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button wire:click="toggleActive({{ $s->id }})" size="xs" variant="subtle">
                                {{ $s->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </flux:button>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:modal.trigger name="edit-service-modal">
                                    <flux:button wire:click="editService({{ $s->id }})" size="xs" variant="subtle" icon="pencil-square">
                                        Edit
                                    </flux:button>
                                </flux:modal.trigger>
                                <flux:modal.trigger name="delete-service-modal">
                                    <flux:button wire:click="confirmDeleteService({{ $s->id }})" size="xs" variant="subtle" class="text-rose-600 hover:bg-rose-50" icon="trash">
                                        Hapus
                                    </flux:button>
                                </flux:modal.trigger>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center py-6 text-zinc-400 text-xs">
                            Belum ada layanan terdaftar.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <!-- Flux Popup Modal: Tambah Layanan Baru -->
    <flux:modal name="create-service-modal" class="md:w-[480px] space-y-6">
        <div>
            <flux:heading size="lg">Tambah Layanan Baru</flux:heading>
            <flux:subheading>Daftarkan jenis jasa pangkas atau perawatan baru di barbershop Anda.</flux:subheading>
        </div>

        <form wire:submit.prevent="createService" class="space-y-4 text-xs">
            <div>
                <flux:label>Nama Layanan Pangkas</flux:label>
                <flux:input wire:model="name" required placeholder="Contoh: Gentleman Haircut & Wash" />
            </div>

            <div>
                <flux:label>Harga Jasa (Rp)</flux:label>
                <flux:input type="number" wire:model="price" required placeholder="65000" />
            </div>

            <div>
                <flux:label>Estimasi Durasi Pangkas (Menit)</flux:label>
                <flux:input type="number" wire:model="duration_minutes" required placeholder="45" />
            </div>

            <div>
                <flux:label>Deskripsi & Fasilitas</flux:label>
                <flux:textarea wire:model="description" rows="2" placeholder="Termasuk cuci rambut, pijat kepala, styling pomade..." />
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">
                    Simpan Layanan
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Flux Popup Modal: Edit Layanan -->
    <flux:modal name="edit-service-modal" class="md:w-[480px] space-y-6">
        <div>
            <flux:heading size="lg">Edit Layanan</flux:heading>
            <flux:subheading>Perbarui data nama tarif & estimasi durasi pangkas.</flux:subheading>
        </div>

        <form wire:submit.prevent="updateService" class="space-y-4 text-xs">
            <div>
                <flux:label>Nama Layanan Pangkas</flux:label>
                <flux:input wire:model="edit_name" required placeholder="Contoh: Gentleman Haircut & Wash" />
            </div>

            <div>
                <flux:label>Harga Jasa (Rp)</flux:label>
                <flux:input type="number" wire:model="edit_price" required />
            </div>

            <div>
                <flux:label>Estimasi Durasi Pangkas (Menit)</flux:label>
                <flux:input type="number" wire:model="edit_duration_minutes" required />
            </div>

            <div>
                <flux:label>Deskripsi & Fasilitas</flux:label>
                <flux:textarea wire:model="edit_description" rows="2" />
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

    <!-- Flux Popup Modal: Konfirmasi Hapus Layanan -->
    <flux:modal name="delete-service-modal" class="md:w-[420px] space-y-4 !pb-1">
        <div>
            <flux:heading size="lg">Konfirmasi Hapus Layanan</flux:heading>
            <flux:subheading class="mt-2">
                Apakah Anda yakin ingin menghapus paket layanan <strong class="text-zinc-900 dark:text-white underline">{{ $deleting_service_name ?? 'layanan ini' }}</strong>? Tindakan ini tidak dapat dibatalkan.
            </flux:subheading>
        </div>

        <div class="flex items-center justify-end gap-2 pt-3">
            <flux:modal.close>
                <flux:button variant="ghost">Batal</flux:button>
            </flux:modal.close>
            <flux:modal.close>
                <flux:button wire:click="deleteService" variant="danger">
                    Hapus Layanan
                </flux:button>
            </flux:modal.close>
        </div>
    </flux:modal>
</div>
