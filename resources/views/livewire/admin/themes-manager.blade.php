<div class="space-y-6">
    <!-- Header with Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Kelola Tema Web Barbershop</flux:heading>
            <flux:subheading>Atur dan tambahkan katalog tema (Free & Premium) untuk landing page tenant.</flux:subheading>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <flux:button wire:click="togglePreviewSetting" variant="{{ $theme_preview_enabled ? 'primary' : 'subtle' }}" icon="eye" class="cursor-pointer">
                <span>Preview Mode: {{ $theme_preview_enabled ? 'AKTIF (Publik)' : 'NONAKTIF (Khusus Dev)' }}</span>
            </flux:button>

            <a href="{{ route('superadmin.themes.guide') }}" wire:navigate>
                <flux:button variant="subtle" icon="book-open" class="cursor-pointer">
                    Panduan Buat Tema
                </flux:button>
            </a>

            <flux:button wire:click="openCreateModal" variant="primary" icon="plus" class="bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white text-white cursor-pointer">
                Tambah Tema Baru
            </flux:button>
        </div>
    </div>

    @if(!empty($successMessage))
        <flux:badge color="zinc" size="lg" class="w-full justify-between p-3 border border-zinc-200 dark:border-zinc-700">
            <span>{{ $successMessage }}</span>
        </flux:badge>
    @endif

    <!-- Catalog Table & Filters -->
    <flux:card class="p-6 space-y-4 border border-zinc-200 dark:border-zinc-800">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-zinc-200 dark:border-zinc-800">
            <div class="flex items-center gap-2">
                <flux:button wire:click="$set('typeFilter', 'all')" size="sm" :variant="$typeFilter === 'all' ? 'primary' : 'subtle'">
                    Semua ({{ $themes->count() }})
                </flux:button>
                <flux:button wire:click="$set('typeFilter', 'free')" size="sm" :variant="$typeFilter === 'free' ? 'primary' : 'subtle'">
                    Free
                </flux:button>
                <flux:button wire:click="$set('typeFilter', 'premium')" size="sm" icon="sparkles" :variant="$typeFilter === 'premium' ? 'primary' : 'subtle'">
                    Premium
                </flux:button>
            </div>

            <flux:input wire:model.live="search" placeholder="Cari nama atau slug..." icon="magnifying-glass" class="w-full sm:w-64" />
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nama Tema</flux:table.column>
                <flux:table.column>Slug & View Path</flux:table.column>
                <flux:table.column>Tipe Kategori</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($themes as $t)
                    <flux:table.row key="{{ $t->id }}">
                        <flux:table.cell class="font-bold text-xs">
                            <div class="flex items-center gap-3">
                                @if($t->thumbnail)
                                    <img src="{{ asset($t->thumbnail) }}" alt="{{ $t->name }}" class="w-10 h-10 rounded object-cover border border-zinc-200 dark:border-zinc-700" />
                                @else
                                    <div class="w-10 h-10 rounded bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-400 text-xs font-mono font-bold">
                                        {{ strtoupper(substr($t->slug, 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-sm text-zinc-900 dark:text-zinc-100">{{ $t->name }}</div>
                                    <div class="text-[11px] text-zinc-500 max-w-xs truncate">{{ $t->description ?? 'Tidak ada deskripsi' }}</div>
                                </div>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell class="text-xs">
                            <div class="font-mono text-zinc-900 dark:text-zinc-100 font-bold">/{{ $t->slug }}</div>
                            <div class="text-[10px] text-zinc-400 font-mono">resources/views/{{ str_replace('.', '/', $t->blade_view) }}.blade.php</div>
                        </flux:table.cell>

                        <flux:table.cell class="text-xs font-bold">
                            @if($t->type === 'free')
                                <flux:badge size="sm" color="zinc" icon="check-circle">FREE</flux:badge>
                            @else
                                <flux:badge size="sm" color="zinc" icon="sparkles">PREMIUM</flux:badge>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            @if($t->is_active)
                                <flux:badge size="sm" color="zinc">Aktif</flux:badge>
                            @else
                                <flux:badge size="sm" color="zinc" class="opacity-50">Nonaktif</flux:badge>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('theme.preview', $t->slug) }}" target="_blank" class="px-2 py-1 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-900 dark:text-zinc-100 font-bold text-[11px] rounded-lg transition inline-flex items-center gap-1">
                                    <flux:icon icon="eye" class="size-3" />
                                    <span>Preview</span>
                                </a>

                                <flux:button wire:click="editTheme({{ $t->id }})" size="xs" variant="subtle" icon="pencil-square">
                                    Edit
                                </flux:button>

                                <flux:button wire:click="toggleStatus({{ $t->id }})" size="xs" variant="subtle">
                                    {{ $t->is_active ? 'Matikan' : 'Aktifkan' }}
                                </flux:button>

                                <flux:button wire:click="deleteTheme({{ $t->id }})" wire:confirm="Yakin ingin menghapus tema ini?" size="xs" variant="subtle" class="text-red-600 hover:text-red-700">
                                    Hapus
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center py-8 text-zinc-400 text-xs">
                            Belum ada tema yang terdaftar. Klik <strong>Tambah Tema Baru</strong> di atas.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <!-- Modal Form Add/Edit Theme -->
    @if($showModal)
        <div class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl max-w-lg w-full p-6 space-y-6 shadow-2xl">
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-800 pb-3">
                    <div>
                        <flux:heading size="lg">{{ $editingThemeId ? 'Edit Tema' : 'Tambah Tema Baru' }}</flux:heading>
                        <flux:subheading>Atur informasi tema dan klasifikasi Free / Premium.</flux:subheading>
                    </div>
                    <button wire:click="closeModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 text-xl font-bold">&times;</button>
                </div>

                <form wire:submit.prevent="saveTheme" class="space-y-4 text-xs">
                    <div>
                        <flux:label>Nama Tema</flux:label>
                        <flux:input wire:model.live="name" required placeholder="Contoh: Urban Streetwear" />
                        @error('name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:label>Slug Theme Identifier</flux:label>
                            <flux:input wire:model="slug" required placeholder="urban-street" />
                            @error('slug') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <flux:label>Tipe Kategori</flux:label>
                            <select wire:model.live="type" class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 text-xs p-2.5">
                                <option value="free">FREE (Semua Tenant)</option>
                                <option value="premium">PREMIUM (Berbayar)</option>
                            </select>
                            @error('type') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if($type === 'premium')
                        <div>
                            <flux:label>Harga Tema (Rp)</flux:label>
                            <flux:input type="number" wire:model="price" placeholder="99000" />
                            @error('price') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div>
                        <flux:label>Blade View Path</flux:label>
                        <flux:input wire:model="blade_view" required placeholder="themes.urban-street" />
                        <span class="text-[10px] text-zinc-400">Lokasi file Blade template di `resources/views/themes/...`</span>
                        @error('blade_view') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <flux:label>Deskripsi Tema</flux:label>
                        <flux:textarea wire:model="description" rows="2" placeholder="Jelaskan karakteristik desain dan fitur tema ini..." />
                        @error('description') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <flux:label>Gambar Thumbnail Preview (Opsional)</flux:label>
                        <flux:input type="file" wire:model="new_thumbnail" accept="image/*" />
                        @if($current_thumbnail && !$new_thumbnail)
                            <div class="mt-2 text-[11px] text-zinc-500">Thumbnail saat ini: <a href="{{ asset($current_thumbnail) }}" target="_blank" class="text-zinc-900 dark:text-zinc-100 underline font-semibold">Lihat Gambar</a></div>
                        @endif
                        @error('new_thumbnail') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="is_active" wire:model="is_active" class="rounded border-zinc-300 dark:border-zinc-700 text-zinc-900" />
                        <label for="is_active" class="text-xs font-medium text-zinc-700 dark:text-zinc-300">Aktifkan tema untuk dipilih tenant</label>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                        <flux:button wire:click="closeModal" type="button" variant="ghost">Batal</flux:button>
                        <flux:button type="submit" variant="primary" class="bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 text-white">
                            {{ $editingThemeId ? 'Simpan Perubahan' : 'Tambah Tema' }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
