<div class="space-y-6 pb-12">
    <!-- Seamless Page Header (No detached floating card wrapper) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Title & Subtitle with back button -->
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('owner.theme.edit') }}" class="p-2.5 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-600 transition-colors flex-shrink-0" title="Kembali ke Pengaturan Tema">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="min-w-0">
                <h1 class="text-xl font-extrabold text-zinc-900 leading-tight truncate">Kustomisasi Tema Web</h1>
                <p class="text-xs text-zinc-500 mt-0.5 truncate">Pengaturan tampilan portal publik toko: <span class="font-medium text-zinc-700">/{{ $slug }}</span></p>
            </div>
        </div>

        <!-- Right Controls: Mode Switcher, Device Switcher, & Save Action -->
        <div class="flex items-center gap-2.5 sm:gap-3 flex-shrink-0 overflow-x-auto pb-1 sm:pb-0 scrollbar-none">
            <!-- Builder Mode Switcher -->
            <div class="inline-flex items-center p-1 bg-zinc-100 border border-zinc-200/80 rounded-xl text-xs font-semibold flex-shrink-0">
                <button 
                    type="button"
                    wire:click="setBuilderMode('block')" 
                    class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer {{ $builder_mode === 'block' ? 'bg-white text-zinc-900 shadow-2xs border border-zinc-200/80 font-bold' : 'text-zinc-500 hover:text-zinc-900' }}"
                >
                    <svg class="w-4 h-4 text-zinc-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Block Builder</span>
                </button>
                
                <button 
                    type="button"
                    wire:click="setBuilderMode('classic')" 
                    class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer {{ $builder_mode === 'classic' ? 'bg-white text-zinc-900 shadow-2xs border border-zinc-200/80 font-bold' : 'text-zinc-500 hover:text-zinc-900' }}"
                >
                    <svg class="w-4 h-4 text-zinc-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    <span>Classic Builder</span>
                </button>
            </div>

            <!-- Device View Switcher -->
            <div class="hidden xl:flex items-center bg-zinc-100 p-1 rounded-xl border border-zinc-200/80 text-xs font-semibold flex-shrink-0">
                <button 
                    type="button"
                    wire:click="setDeviceView('desktop')" 
                    class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 whitespace-nowrap cursor-pointer {{ $device_view === 'desktop' ? 'bg-white text-zinc-900 font-bold shadow-2xs' : 'text-zinc-500 hover:text-zinc-800' }}"
                >
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Desktop</span>
                </button>
                <button 
                    type="button"
                    wire:click="setDeviceView('mobile')" 
                    class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 whitespace-nowrap cursor-pointer {{ $device_view === 'mobile' ? 'bg-white text-zinc-900 font-bold shadow-2xs' : 'text-zinc-500 hover:text-zinc-800' }}"
                >
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Mobile</span>
                </button>
            </div>

            <!-- Save Action Button -->
            <button 
                type="button"
                wire:click="saveCustomization"
                wire:loading.attr="disabled"
                wire:target="saveCustomization"
                class="px-4 py-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded-xl font-semibold text-xs shadow-2xs transition-all flex items-center gap-2 whitespace-nowrap flex-shrink-0 disabled:opacity-50 cursor-pointer"
            >
                <svg wire:loading wire:target="saveCustomization" class="w-4 h-4 animate-spin text-white flex-shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span wire:loading.remove wire:target="saveCustomization" class="whitespace-nowrap">Simpan & Publikasikan</span>
                <span wire:loading wire:target="saveCustomization" class="whitespace-nowrap">Menyimpan...</span>
            </button>
        </div>
    </div>

    <!-- Alert Notification -->
    @if ($success_message)
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

    <!-- Main Grid Area -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Active Editor Panel (Width: 5 cols on lg) -->
        <div class="lg:col-span-5 bg-white border border-zinc-200/80 rounded-2xl p-5 sm:p-6 shadow-2xs space-y-6">

            <!-- MODE 1: BLOCK BUILDER -->
            @if ($builder_mode === 'block')
                <div class="space-y-5">
                    <div class="flex items-center justify-between gap-3 border-b border-zinc-100 pb-4">
                        <div class="min-w-0">
                            <h2 class="text-sm font-bold text-zinc-950 flex items-center gap-2 min-w-0">
                                <span class="truncate">Struktur Section</span>
                                <span class="px-2 py-0.5 bg-zinc-100 border border-zinc-200 text-zinc-700 text-[10px] rounded-full font-semibold whitespace-nowrap flex-shrink-0">{{ count($blocks) }} Block</span>
                            </h2>
                            <p class="text-xs text-zinc-500 mt-0.5 truncate">Atur urutan, edit, atau aktifkan section.</p>
                        </div>

                        <button 
                            type="button" 
                            wire:click="openAddBlockModal" 
                            class="px-3.5 py-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded-xl text-xs font-semibold shadow-2xs transition-colors flex items-center gap-1.5 whitespace-nowrap flex-shrink-0 cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Tambah Block</span>
                        </button>
                    </div>

                    <!-- Block Cards List (Drag & Drop Reordering) -->
                    <div class="space-y-2.5">
                        @forelse($blocks as $index => $block)
                            @php
                                $typeInfo = $available_block_types[$block['type']] ?? ['name' => ucfirst($block['type'])];
                                $isEnabled = $block['enabled'] ?? true;
                            @endphp
                            <div 
                                draggable="true"
                                x-data="{ isDragging: false }"
                                @dragstart="event.dataTransfer.setData('text/plain', '{{ $index }}'); isDragging = true"
                                @dragend="isDragging = false"
                                @dragover.prevent
                                @drop.prevent="$wire.reorderBlocks(parseInt(event.dataTransfer.getData('text/plain')), {{ $index }})"
                                class="border rounded-xl p-3.5 transition-all duration-150 select-none cursor-grab active:cursor-grabbing {{ $isEnabled ? 'bg-white border-zinc-200 hover:border-zinc-300 shadow-2xs' : 'bg-zinc-50 border-zinc-200/60 opacity-60' }}"
                                :class="{ 'opacity-40 border-dashed border-zinc-400 bg-zinc-100': isDragging }"
                            >
                                <div class="flex items-center justify-between gap-3">
                                    
                                    <!-- Drag Handle & Block Info -->
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="text-zinc-400 hover:text-zinc-700 flex-shrink-0 cursor-grab" title="Tahan & geser untuk mengubah urutan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                        </div>
                                        <div class="w-6 h-6 rounded-md bg-zinc-100 border border-zinc-200/80 flex items-center justify-center text-zinc-700 font-bold text-[11px] flex-shrink-0">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-xs text-zinc-900 truncate">{{ $block['name'] ?? $typeInfo['name'] }}</span>
                                                <span class="px-1.5 py-0.5 bg-zinc-100 text-zinc-500 text-[9px] font-mono rounded uppercase tracking-wider whitespace-nowrap flex-shrink-0">{{ $block['type'] }}</span>
                                            </div>
                                            <p class="text-[11px] text-zinc-400 truncate mt-0.5">
                                                {{ $block['config']['title'] ?? 'Judul standar' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Action Controls -->
                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <!-- Toggle Hide/Show -->
                                        <button 
                                            type="button" 
                                            wire:click="toggleBlockEnabled({{ $index }})" 
                                            class="p-1.5 rounded-lg text-zinc-400 hover:text-zinc-800 hover:bg-zinc-100 cursor-pointer"
                                            title="{{ $isEnabled ? 'Sembunyikan Section' : 'Tampilkan Section' }}"
                                        >
                                            @if($isEnabled)
                                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            @else
                                                <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.03 10.03 0 013.682-.763c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m-9.56-9.561l9.56 9.56"/></svg>
                                            @endif
                                        </button>

                                        <!-- Edit Config -->
                                        <button 
                                            type="button" 
                                            wire:click="editBlockConfig({{ $index }})" 
                                            class="p-1.5 rounded-lg text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 cursor-pointer"
                                            title="Edit Konfigurasi"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <!-- Delete -->
                                        <button 
                                            type="button" 
                                            wire:click="removeBlock({{ $index }})" 
                                            class="p-1.5 rounded-lg text-zinc-400 hover:text-red-600 hover:bg-red-50 cursor-pointer"
                                            title="Hapus Block"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center border-2 border-dashed border-zinc-200 rounded-xl space-y-3">
                                <div class="w-10 h-10 rounded-xl bg-zinc-100 flex items-center justify-center text-zinc-400 mx-auto">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <div class="text-xs text-zinc-500 font-medium">Belum ada block yang ditambahkan.</div>
                                <button type="button" wire:click="openAddBlockModal" class="px-3.5 py-1.5 bg-zinc-900 text-white rounded-xl font-semibold text-xs cursor-pointer whitespace-nowrap">
                                    + Tambah Block Pertama
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>

            <!-- MODE 2: CLASSIC BUILDER -->
            @else
                <div class="space-y-5">
                    <div class="border-b border-zinc-100 pb-3">
                        <h2 class="text-sm font-bold text-zinc-950">Pengaturan Tema Klasik</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Konfigurasi visual dan opsi tata letak berbasis tab.</p>
                    </div>

                    <!-- Sub Tab Pills -->
                    <div class="flex items-center gap-1 overflow-x-auto pb-2 border-b border-zinc-100 text-xs font-semibold scrollbar-none">
                        @foreach([
                            'colors' => 'Warna',
                            'typography' => 'Font',
                            'header' => 'Header',
                            'hero' => 'Hero Banner',
                            'sections' => 'Sections',
                            'layout' => 'Layout',
                            'footer' => 'Footer',
                        ] as $tabKey => $tabLabel)
                            <button 
                                type="button" 
                                wire:click="setClassicTab('{{ $tabKey }}')" 
                                class="px-3 py-1.5 rounded-lg whitespace-nowrap transition-colors flex-shrink-0 cursor-pointer {{ $classic_tab === $tabKey ? 'bg-zinc-900 text-white font-bold' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}"
                            >
                                {{ $tabLabel }}
                            </button>
                        @endforeach
                    </div>

                    <!-- TAB 1: WARNA -->
                    @if($classic_tab === 'colors')
                        <div class="space-y-3 pt-1">
                            <label class="block text-xs font-bold text-zinc-900">Warna Aksen Utama (Accent Color)</label>
                            <div class="grid grid-cols-5 gap-2.5">
                                @foreach([
                                    'amber' => ['bg' => 'bg-amber-500', 'label' => 'Amber Gold'],
                                    'emerald' => ['bg' => 'bg-emerald-500', 'label' => 'Emerald Green'],
                                    'indigo' => ['bg' => 'bg-indigo-600', 'label' => 'Indigo Blue'],
                                    'rose' => ['bg' => 'bg-rose-500', 'label' => 'Rose Crimson'],
                                    'zinc' => ['bg' => 'bg-zinc-900', 'label' => 'Monochrome'],
                                ] as $colorKey => $colorData)
                                    <button 
                                        type="button" 
                                        wire:click="$set('settings.primary_color', '{{ $colorKey }}')" 
                                        class="p-2.5 rounded-xl border flex flex-col items-center gap-1.5 transition-all cursor-pointer {{ ($settings['primary_color'] ?? 'amber') === $colorKey ? 'border-zinc-900 bg-zinc-50 ring-2 ring-zinc-900/20' : 'border-zinc-200 bg-white hover:border-zinc-300' }}"
                                    >
                                        <span class="w-5 h-5 rounded-full {{ $colorData['bg'] }} shadow-xs"></span>
                                        <span class="text-[10px] font-bold text-zinc-700 truncate w-full text-center">{{ $colorData['label'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                    <!-- TAB 2: TYPOGRAPHY -->
                    @elseif($classic_tab === 'typography')
                        <div class="space-y-3 pt-1">
                            <label class="block text-xs font-bold text-zinc-900">Font Typeface Utama</label>
                            <div class="space-y-2">
                                @foreach([
                                    'Plus Jakarta Sans' => 'Modern Sans-Serif (Standard Clean)',
                                    'Inter' => 'Crisp Functional Technical',
                                    'Outfit' => 'Geometric Clean & Bold',
                                    'Playfair Display' => 'Classic Elegance & Vintage Hairdresser',
                                ] as $fontName => $fontDesc)
                                    <label class="p-3 rounded-xl border flex items-center justify-between cursor-pointer transition-all {{ ($settings['font_family'] ?? 'Plus Jakarta Sans') === $fontName ? 'border-zinc-900 bg-zinc-50' : 'border-zinc-200 bg-white hover:border-zinc-300' }}">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <input type="radio" name="font_family" value="{{ $fontName }}" wire:model.live="settings.font_family" class="text-zinc-900 focus:ring-zinc-900 flex-shrink-0" />
                                            <div class="min-w-0">
                                                <div class="text-xs font-bold text-zinc-900 truncate" style="font-family: '{{ $fontName }}', sans-serif;">{{ $fontName }}</div>
                                                <div class="text-[11px] text-zinc-400 mt-0.5 truncate">{{ $fontDesc }}</div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    <!-- TAB 3: HEADER -->
                    @elseif($classic_tab === 'header')
                        <div class="space-y-3 pt-1">
                            <label class="flex items-center justify-between p-3 rounded-xl border border-zinc-200 bg-white cursor-pointer">
                                <div>
                                    <div class="text-xs font-bold text-zinc-900">Tampilkan Logo Toko di Header</div>
                                    <div class="text-[11px] text-zinc-500">Gunakan logo resmi outlet di bar navigasi atas.</div>
                                </div>
                                <input type="checkbox" wire:model.live="settings.header.show_logo" class="rounded text-zinc-900 focus:ring-zinc-900 w-4 h-4 flex-shrink-0" />
                            </label>

                            <label class="flex items-center justify-between p-3 rounded-xl border border-zinc-200 bg-white cursor-pointer">
                                <div>
                                    <div class="text-xs font-bold text-zinc-900">Tampilkan Badge Status Buka</div>
                                    <div class="text-[11px] text-zinc-500">Indikator 'Buka Hari Ini' di header.</div>
                                </div>
                                <input type="checkbox" wire:model.live="settings.header.show_status_badge" class="rounded text-zinc-900 focus:ring-zinc-900 w-4 h-4 flex-shrink-0" />
                            </label>
                        </div>

                    <!-- TAB 4: HERO BANNER -->
                    @elseif($classic_tab === 'hero')
                        <div class="space-y-4 pt-1">
                            <div>
                                <label class="block text-xs font-bold text-zinc-900 mb-1">Judul Utama (Hero Title)</label>
                                <input type="text" wire:model.live.debounce.300ms="settings.hero_title" class="w-full px-3 py-2 rounded-xl border border-zinc-200 text-xs focus:ring-2 focus:ring-zinc-900 font-medium" />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-zinc-900 mb-1">Tagline Status</label>
                                <input type="text" wire:model.live.debounce.300ms="settings.hero_tagline" class="w-full px-3 py-2 rounded-xl border border-zinc-200 text-xs focus:ring-2 focus:ring-zinc-900 font-medium" />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-zinc-900 mb-1">Deskripsi Singkat (Hero Subtitle)</label>
                                <textarea rows="3" wire:model.live.debounce.300ms="settings.hero_subtitle" class="w-full px-3 py-2 rounded-xl border border-zinc-200 text-xs focus:ring-2 focus:ring-zinc-900 font-medium"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-zinc-900 mb-1">Gambar Hero Banner Studio</label>
                                @if($current_hero_banner)
                                    <div class="mb-2 h-20 rounded-xl bg-cover bg-center border border-zinc-200" style="background-image: url('{{ asset($current_hero_banner) }}');"></div>
                                @endif
                                <input type="file" wire:model.live="new_hero_banner" accept="image/*" class="w-full text-xs text-zinc-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200" />
                            </div>
                        </div>

                    <!-- TAB 5: SECTIONS -->
                    @elseif($classic_tab === 'sections')
                        <div class="space-y-2.5 pt-1">
                            <label class="flex items-center justify-between p-3 rounded-xl border border-zinc-200 bg-white cursor-pointer">
                                <span class="text-xs font-bold text-zinc-900">Tampilkan Section Katalog Layanan</span>
                                <input type="checkbox" wire:model.live="settings.show_services" class="rounded text-zinc-900 focus:ring-zinc-900 w-4 h-4 flex-shrink-0" />
                            </label>

                            <label class="flex items-center justify-between p-3 rounded-xl border border-zinc-200 bg-white cursor-pointer">
                                <span class="text-xs font-bold text-zinc-900">Tampilkan Section Katalog Produk Pomade</span>
                                <input type="checkbox" wire:model.live="settings.show_products" class="rounded text-zinc-900 focus:ring-zinc-900 w-4 h-4 flex-shrink-0" />
                            </label>

                            <label class="flex items-center justify-between p-3 rounded-xl border border-zinc-200 bg-white cursor-pointer">
                                <span class="text-xs font-bold text-zinc-900">Tampilkan Floating Button WhatsApp</span>
                                <input type="checkbox" wire:model.live="settings.show_wa_button" class="rounded text-zinc-900 focus:ring-zinc-900 w-4 h-4 flex-shrink-0" />
                            </label>
                        </div>

                    <!-- TAB 6: LAYOUT & BUTTONS -->
                    @elseif($classic_tab === 'layout')
                        <div class="space-y-3 pt-1">
                            <div>
                                <label class="block text-xs font-bold text-zinc-900 mb-2">Bentuk Sudut Tombol (Button Corner Radius)</label>
                                <div class="grid grid-cols-3 gap-2.5">
                                    @foreach([
                                        'rounded-xl' => 'Slightly Rounded',
                                        'rounded-2xl' => 'Pill Smooth',
                                        'rounded-full' => 'Full Capsule',
                                    ] as $btnStyle => $btnLabel)
                                        <button 
                                            type="button" 
                                            wire:click="$set('settings.button_style', '{{ $btnStyle }}')" 
                                            class="p-2.5 border rounded-xl text-center text-xs font-semibold transition-all cursor-pointer whitespace-nowrap {{ ($settings['button_style'] ?? 'rounded-xl') === $btnStyle ? 'border-zinc-900 bg-zinc-900 text-white' : 'border-zinc-200 bg-white text-zinc-700 hover:border-zinc-300' }}"
                                        >
                                            {{ $btnLabel }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    <!-- TAB 7: FOOTER -->
                    @elseif($classic_tab === 'footer')
                        <div class="space-y-3.5 pt-1">
                            <div>
                                <label class="block text-xs font-bold text-zinc-900 mb-1">Teks Informasi Outlet di Footer</label>
                                <input type="text" wire:model.live.debounce.300ms="settings.footer.text" class="w-full px-3 py-2 rounded-xl border border-zinc-200 text-xs focus:ring-2 focus:ring-zinc-900 font-medium" />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-zinc-900 mb-1">Teks Hak Cipta (Copyright)</label>
                                <input type="text" wire:model.live.debounce.300ms="settings.footer.copyright" class="w-full px-3 py-2 rounded-xl border border-zinc-200 text-xs focus:ring-2 focus:ring-zinc-900 font-medium" />
                            </div>
                        </div>
                    @endif

                </div>
            @endif

        </div>

        <!-- Right Column: Interactive Live Preview Sandbox Frame (Width: 7 cols on lg) -->
        <div class="lg:col-span-7 sticky top-6">
            <div class="bg-white border border-zinc-200/80 rounded-2xl p-4 shadow-2xs space-y-3">
                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
                        <span class="text-xs font-bold text-zinc-900">Live Preview Sandbox</span>
                        <span class="text-[10px] text-zinc-500 bg-zinc-100 px-2 py-0.5 rounded font-mono uppercase whitespace-nowrap">{{ $builder_mode }} MODE</span>
                    </div>

                    <a href="{{ url('/' . $slug) }}" target="_blank" class="text-[11px] font-semibold text-zinc-700 hover:text-zinc-950 flex items-center gap-1 whitespace-nowrap">
                        <span>Buka Toko Asli</span>
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>

                <!-- Iframe Preview Wrapper -->
                <div class="w-full flex justify-center bg-zinc-100 rounded-xl p-2 min-h-[680px] overflow-hidden border border-zinc-200/60">
                    <div class="transition-all duration-300 w-full h-[660px] bg-white rounded-lg shadow-2xs overflow-hidden border border-zinc-200 {{ $device_view === 'mobile' ? 'max-w-[375px] h-[660px] rounded-3xl border-4 border-zinc-800' : 'w-full' }}">
                        <iframe 
                            src="{{ $previewUrl }}" 
                            class="w-full h-full border-none"
                            title="Live Theme Preview"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL 1: ADD BLOCK MODAL -->
    @if ($show_add_block_modal)
        <div class="fixed inset-0 z-50 bg-zinc-950/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl max-w-lg w-full p-5 space-y-4 shadow-xl border border-zinc-200 my-8">
                <div class="flex items-center justify-between border-b border-zinc-100 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950">Pilih Block Section Baru</h3>
                        <p class="text-xs text-zinc-500">Pilih jenis konten yang ingin ditambahkan ke halaman toko.</p>
                    </div>
                    <button type="button" wire:click="closeAddBlockModal" class="p-1 text-zinc-400 hover:text-zinc-800 rounded-lg cursor-pointer">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-[380px] overflow-y-auto pr-1">
                    @foreach($available_block_types as $bType => $bInfo)
                        <button 
                            type="button" 
                            wire:click="addBlock('{{ $bType }}')" 
                            class="p-3.5 rounded-xl border border-zinc-200 hover:border-zinc-900 hover:bg-zinc-50 text-left transition-all group space-y-1 cursor-pointer"
                        >
                            <div class="font-bold text-xs text-zinc-900 group-hover:text-zinc-950 flex items-center justify-between">
                                <span>{{ $bInfo['name'] }}</span>
                                <svg class="w-3.5 h-3.5 text-zinc-400 group-hover:text-zinc-900 transition-transform group-hover:translate-x-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </div>
                            <p class="text-[11px] text-zinc-500 leading-relaxed">{{ $bInfo['desc'] }}</p>
                        </button>
                    @endforeach
                </div>

                <div class="pt-2 flex justify-end border-t border-zinc-100">
                    <button type="button" wire:click="closeAddBlockModal" class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-semibold text-xs rounded-xl cursor-pointer">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL 2: EDIT BLOCK CONFIG MODAL -->
    @if ($editing_block_index !== null && isset($blocks[$editing_block_index]))
        <div class="fixed inset-0 z-50 bg-zinc-950/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl max-w-md w-full p-5 space-y-4 shadow-xl border border-zinc-200 my-8">
                <div class="flex items-center justify-between border-b border-zinc-100 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-950">Edit Konfigurasi Block</h3>
                        <p class="text-xs text-zinc-500">Block: <span class="font-bold text-zinc-800">{{ $blocks[$editing_block_index]['name'] ?? 'Section' }}</span></p>
                    </div>
                    <button type="button" wire:click="closeEditBlockModal" class="p-1 text-zinc-400 hover:text-zinc-800 rounded-lg cursor-pointer">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-bold text-zinc-900 mb-1">Judul Section (Title)</label>
                        <input type="text" wire:model="editing_block_config.title" class="w-full px-3 py-2 rounded-xl border border-zinc-200 text-xs focus:ring-2 focus:ring-zinc-900 font-medium" />
                    </div>

                    @if(isset($editing_block_config['subtitle']))
                        <div>
                            <label class="block text-xs font-bold text-zinc-900 mb-1">Sub Judul / Deskripsi (Subtitle)</label>
                            <textarea rows="3" wire:model="editing_block_config.subtitle" class="w-full px-3 py-2 rounded-xl border border-zinc-200 text-xs focus:ring-2 focus:ring-zinc-900 font-medium"></textarea>
                        </div>
                    @endif

                    @if(isset($editing_block_config['tagline']))
                        <div>
                            <label class="block text-xs font-bold text-zinc-900 mb-1">Tagline Status</label>
                            <input type="text" wire:model="editing_block_config.tagline" class="w-full px-3 py-2 rounded-xl border border-zinc-200 text-xs focus:ring-2 focus:ring-zinc-900 font-medium" />
                        </div>
                    @endif
                </div>

                <div class="pt-2 flex justify-end gap-2 border-t border-zinc-100">
                    <button type="button" wire:click="closeEditBlockModal" class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-semibold text-xs rounded-xl cursor-pointer">
                        Batal
                    </button>
                    <button type="button" wire:click="saveBlockConfig" class="px-4 py-2 bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs rounded-xl shadow-2xs cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
