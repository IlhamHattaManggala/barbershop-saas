<div class="space-y-6">
    <!-- Header with Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Produk & Inventaris Retail</flux:heading>
            <flux:subheading>Manajemen stok pomade, vitamin rambut, dan produk retail toko.</flux:subheading>
        </div>

        <div class="flex items-center gap-3">
            <flux:modal.trigger name="create-product-modal">
                <flux:button variant="primary" icon="plus">
                    Tambah Produk Baru
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    @if(!empty($success_message))
        <flux:badge color="emerald" size="lg" class="w-full justify-between p-3">
            <span>{{ $success_message }}</span>
        </flux:badge>
    @endif

    <!-- Full-Width Products Table -->
    <flux:card class="p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-zinc-200 dark:border-zinc-700">
            <div>
                <flux:heading size="lg">Katalog Produk Toko</flux:heading>
                <flux:subheading>Total {{ $products->count() }} jenis produk retail terdaftar.</flux:subheading>
            </div>

            <flux:input wire:model.live="search" placeholder="Cari produk..." icon="magnifying-glass" class="w-full sm:w-64" />
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nama Produk</flux:table.column>
                <flux:table.column>Kategori</flux:table.column>
                <flux:table.column>Harga Jual</flux:table.column>
                <flux:table.column>Status Stok</flux:table.column>
                <flux:table.column>Quick Stok</flux:table.column>
                <flux:table.column>Aksi Manager</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($products as $p)
                    <flux:table.row key="{{ $p->id }}">
                        <flux:table.cell class="font-bold text-xs">
                            {{ $p->name }}
                            <div class="text-[10px] text-zinc-400 font-normal">{{ $p->description }}</div>
                        </flux:table.cell>
                        <flux:table.cell class="text-xs">
                            {{ $p->category }}
                        </flux:table.cell>
                        <flux:table.cell class="font-bold text-xs text-indigo-600">
                            Rp {{ number_format($p->price, 0, ',', '.') }}
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($p->stock <= $p->min_stock)
                                <flux:badge size="sm" color="rose">Stok Kritis: {{ $p->stock }}</flux:badge>
                            @else
                                <flux:badge size="sm" color="emerald">Stok Ready: {{ $p->stock }}</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-1">
                                <flux:button wire:click="updateStock({{ $p->id }}, {{ $p->stock - 1 }})" size="xs" variant="subtle">-</flux:button>
                                <span class="text-xs font-bold w-8 text-center font-mono">{{ $p->stock }}</span>
                                <flux:button wire:click="updateStock({{ $p->id }}, {{ $p->stock + 1 }})" size="xs" variant="subtle">+</flux:button>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:modal.trigger name="stock-adjustment-modal">
                                    <flux:button wire:click="openStockAdjustmentModal({{ $p->id }})" size="xs" variant="subtle" icon="arrows-up-down">
                                        Stok
                                    </flux:button>
                                </flux:modal.trigger>
                                <flux:modal.trigger name="edit-product-modal">
                                    <flux:button wire:click="editProduct({{ $p->id }})" size="xs" variant="subtle" icon="pencil-square">
                                        Edit
                                    </flux:button>
                                </flux:modal.trigger>
                                <flux:modal.trigger name="delete-product-modal">
                                    <flux:button wire:click="confirmDeleteProduct({{ $p->id }})" size="xs" variant="subtle" class="text-rose-600 hover:bg-rose-50" icon="trash">
                                        Hapus
                                    </flux:button>
                                </flux:modal.trigger>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center py-6 text-zinc-400 text-xs">
                            Belum ada produk retail terdaftar.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <!-- Audit Trail: Stock Movements History Card -->
    <flux:card class="p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-700">
            <div>
                <flux:heading size="lg">Audit Trail & Riwayat Pergerakan Stok</flux:heading>
                <flux:subheading>Log otomatis transaksi keluar-masuk stok barang retail.</flux:subheading>
            </div>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Waktu & Tanggal</flux:table.column>
                <flux:table.column>Produk</flux:table.column>
                <flux:table.column>Tipe Pergerakan</flux:table.column>
                <flux:table.column>Jumlah (Qty)</flux:table.column>
                <flux:table.column>Catatan / Keterangan</flux:table.column>
                <flux:table.column>Petugas</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($stockMovements as $sm)
                    <flux:table.row key="{{ $sm->id }}">
                        <flux:table.cell class="font-mono text-xs text-zinc-500">
                            {{ $sm->created_at->format('d/m/Y H:i') }}
                        </flux:table.cell>
                        <flux:table.cell class="font-bold text-xs">
                            {{ $sm->product->name ?? 'Produk Dihapus' }}
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($sm->type === 'purchase')
                                <flux:badge size="sm" color="emerald">Stok Masuk (Beli)</flux:badge>
                            @elseif($sm->type === 'sale')
                                <flux:badge size="sm" color="indigo">Penjualan POS</flux:badge>
                            @elseif($sm->type === 'return')
                                <flux:badge size="sm" color="amber">Retur Barang</flux:badge>
                            @else
                                <flux:badge size="sm" color="zinc">Adjustment Manual</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell class="font-mono font-bold text-xs">
                            {{ $sm->quantity > 0 ? '+'.$sm->quantity : $sm->quantity }}
                        </flux:table.cell>
                        <flux:table.cell class="text-xs text-zinc-500">
                            {{ $sm->notes ?? '-' }}
                        </flux:table.cell>
                        <flux:table.cell class="text-xs font-medium">
                            {{ $sm->creator->name ?? 'System' }}
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center py-6 text-zinc-400 text-xs">
                            Belum ada riwayat pergerakan stok.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <!-- Flux Popup Modal: Tambah Produk Baru -->
    <flux:modal name="create-product-modal" class="md:w-[480px] space-y-6">
        <div>
            <flux:heading size="lg">Tambah Produk Baru</flux:heading>
            <flux:subheading>Daftarkan produk retail pomade atau perawatan baru.</flux:subheading>
        </div>

        <form wire:submit.prevent="createProduct" class="space-y-4 text-xs">
            <div>
                <flux:label>Nama Produk</flux:label>
                <flux:input wire:model="name" required placeholder="Contoh: Oilbased Pomade Heavy 100g" />
            </div>

            <div>
                <flux:label>Kategori Produk</flux:label>
                <flux:select wire:model="category">
                    <option value="Pomade">Pomade & Clay</option>
                    <option value="Hair Tonic">Hair Tonic & Vitamin</option>
                    <option value="Shampoo">Shampoo & Conditioner</option>
                    <option value="Aksesoris">Aksesoris & Sisir</option>
                </flux:select>
            </div>

            <div>
                <flux:label>Harga Jual (Rp)</flux:label>
                <flux:input type="number" wire:model="price" required placeholder="85000" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <flux:label>Stok Awal</flux:label>
                    <flux:input type="number" wire:model="stock" required />
                </div>
                <div>
                    <flux:label>Min. Stok Alert</flux:label>
                    <flux:input type="number" wire:model="min_stock" required />
                </div>
            </div>

            <div>
                <flux:label>Deskripsi Produk</flux:label>
                <flux:textarea wire:model="description" rows="2" placeholder="Detail aroma, tingkat hold, kilau..." />
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">
                    Simpan Produk
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Flux Popup Modal: Edit Produk -->
    <flux:modal name="edit-product-modal" class="md:w-[480px] space-y-6">
        <div>
            <flux:heading size="lg">Edit Produk</flux:heading>
            <flux:subheading>Perbarui data harga, stok, dan deskripsi produk retail.</flux:subheading>
        </div>

        <form wire:submit.prevent="updateProduct" class="space-y-4 text-xs">
            <div>
                <flux:label>Nama Produk</flux:label>
                <flux:input wire:model="edit_name" required placeholder="Contoh: Oilbased Pomade Heavy 100g" />
            </div>

            <div>
                <flux:label>Kategori Produk</flux:label>
                <flux:select wire:model="edit_category">
                    <option value="Pomade">Pomade & Clay</option>
                    <option value="Hair Tonic">Hair Tonic & Vitamin</option>
                    <option value="Shampoo">Shampoo & Conditioner</option>
                    <option value="Aksesoris">Aksesoris & Sisir</option>
                </flux:select>
            </div>

            <div>
                <flux:label>Harga Jual (Rp)</flux:label>
                <flux:input type="number" wire:model="edit_price" required />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <flux:label>Jumlah Stok</flux:label>
                    <flux:input type="number" wire:model="edit_stock" required />
                </div>
                <div>
                    <flux:label>Min. Stok Alert</flux:label>
                    <flux:input type="number" wire:model="edit_min_stock" required />
                </div>
            </div>

            <div>
                <flux:label>Deskripsi Produk</flux:label>
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

    <!-- Flux Popup Modal: Konfirmasi Hapus Produk -->
    <flux:modal name="delete-product-modal" class="md:w-[420px] space-y-4 !pb-1">
        <div>
            <flux:heading size="lg">Konfirmasi Hapus Produk</flux:heading>
            <flux:subheading class="mt-2">
                Apakah Anda yakin ingin menghapus produk retail <strong class="text-zinc-900 dark:text-white underline">{{ $deleting_product_name ?? 'produk ini' }}</strong>? Tindakan ini tidak dapat dibatalkan.
            </flux:subheading>
        </div>

        <div class="flex items-center justify-end gap-2 pt-3">
            <flux:modal.close>
                <flux:button variant="ghost">Batal</flux:button>
            </flux:modal.close>
            <flux:modal.close>
                <flux:button wire:click="deleteProduct" variant="danger">
                    Hapus Produk
                </flux:button>
            </flux:modal.close>
        </div>
    </flux:modal>

    <!-- Flux Popup Modal: Penyesuaian & Restok Produk -->
    <flux:modal name="stock-adjustment-modal" class="md:w-[450px] space-y-6">
        <div>
            <flux:heading size="lg">Penyesuaian & Restok Barang</flux:heading>
            <flux:subheading>Input stok masuk dari supplier atau penyesuaian opname untuk <strong class="text-zinc-900 dark:text-white underline">{{ $adj_product_name }}</strong>.</flux:subheading>
        </div>

        <form wire:submit.prevent="submitStockAdjustment" class="space-y-4 text-xs">
            <div>
                <flux:label>Tipe Perubahan Stok</flux:label>
                <flux:select wire:model="adj_type">
                    <option value="purchase">Stok Masuk / Pembelian Supplier (+)</option>
                    <option value="adjustment">Penyesuaian / Stok Opname (+/-)</option>
                    <option value="return">Retur / Barang Rusak/Expired (-)</option>
                </flux:select>
            </div>

            <div>
                <flux:label>Jumlah Perubahan (Qty)</flux:label>
                <flux:input type="number" wire:model="adj_qty" required placeholder="Contoh: 10 atau -2" />
            </div>

            <div>
                <flux:label>Catatan / Keterangan</flux:label>
                <flux:input wire:model="adj_notes" placeholder="No. Invoice supplier atau alasan retur..." />
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:modal.close>
                    <flux:button type="submit" variant="primary">
                        Simpan Penyesuaian
                    </flux:button>
                </flux:modal.close>
            </div>
        </form>
    </flux:modal>
</div>
