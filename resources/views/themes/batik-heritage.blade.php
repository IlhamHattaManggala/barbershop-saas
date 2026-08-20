@php
    $shopLogo = $tenant->logo ? asset($tenant->logo) : asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
    $heroBg = $tenant->hero_banner ? asset($tenant->hero_banner) : asset('images/themes/indonesian_batik_light_bg.jpg');
    $cornerBatik = asset('images/themes/batik_corner_pattern.png');
    $tagline = $tenant->hero_tagline ?? 'BATIK HERITAGE • ESTETIKA CUKUR TRADISIONAL KERATON NUSANTARA';
    $btnRadius = 'rounded-2xl';

    $showServices = (bool)($tenant->show_services ?? true);
    $showProducts = (bool)($tenant->show_products ?? true);

    $rawOrder = $tenant->section_order;
    if (is_string($rawOrder)) {
        $rawOrder = json_decode($rawOrder, true);
    }
    $sectionOrder = is_array($rawOrder) && count($rawOrder) > 0 ? $rawOrder : ['services', 'booking', 'products'];

    $footerText = $tenant->footer_text ?? ($tenant->address ?? 'Alamat Outlet Barbershop');
    $footerCopyright = $tenant->footer_copyright ?? ('© ' . date('Y') . ' ' . $tenant->name . '. All rights reserved.');
@endphp

<div class="min-h-screen flex flex-col justify-between bg-[#FAF6F0] text-stone-900 font-serif selection:bg-amber-700 selection:text-white w-full overflow-x-hidden relative">
    
    <!-- LIGHT BATIK PARANG PATTERN OVERLAY -->
    <div class="fixed inset-0 opacity-[0.03] pointer-events-none z-0 bg-repeat" style="background-image: radial-gradient(#78350F 1px, transparent 1px); background-size: 20px 20px;"></div>

    <!-- Light Mode Header Navigation (Batik Heritage Aesthetics) -->
    <header class="bg-[#FAF6F0]/95 border-b border-amber-900/15 backdrop-blur-md sticky top-0 z-40 shadow-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between gap-4">
            <a href="{{ url('/' . $tenant->slug) }}" class="flex items-center gap-3.5 min-w-0">
                <!-- Batik Gunungan Seal Emblem -->
                <div class="w-11 h-11 rounded-2xl bg-amber-800 border-2 border-amber-600 p-0.5 shadow-md flex-shrink-0 flex items-center justify-center text-amber-50 font-bold">
                    <svg class="w-6 h-6 text-amber-100 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2L4 18h16L12 2zm0 4l5.5 11h-11L12 6z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="font-extrabold text-base sm:text-xl text-amber-950 tracking-wider uppercase font-serif truncate">{{ $tenant->name }}</div>
                    <div class="text-[10px] text-amber-800 font-mono tracking-widest uppercase font-bold">BATIK HERITAGE &bull; TRADISI NUSANTARA</div>
                </div>
            </a>
            
            <div class="hidden sm:flex items-center gap-2">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-amber-100/80 border border-amber-900/15 rounded-full text-xs font-mono font-bold text-amber-900">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-700 animate-pulse"></span>
                    <span>KERATON SUITE • READY</span>
                </span>
            </div>
        </div>
    </header>

    <main class="flex-1 w-full relative z-10">
        
        <!-- Hero Section: Bright Light-Mode Indonesian Batik Banner -->
        <section class="relative py-16 sm:py-24 border-b border-amber-900/15 text-center px-4 bg-[#F5EFE6] overflow-hidden shadow-sm">
            
            <div class="absolute inset-0 bg-cover bg-center opacity-30 mix-blend-multiply scale-105" style="background-image: url('{{ $heroBg }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#FAF6F0] via-[#FAF6F0]/80 to-[#FAF6F0]/40"></div>

            <div class="max-w-4xl mx-auto relative z-10 space-y-5">
                
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-900/10 border border-amber-900/20 text-amber-950 text-xs font-mono font-bold rounded-full shadow-xs tracking-widest uppercase">
                    <span>{{ $tagline }}</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-wide text-amber-950 uppercase font-serif leading-tight">
                    {{ $tenant->hero_title ?: $tenant->name }}
                </h1>

                <!-- Batik Golden Ornament Line Accent -->
                <div class="flex items-center justify-center gap-3">
                    <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-amber-800 to-transparent"></div>
                    <div class="w-3 h-3 rotate-45 border border-amber-800 bg-amber-600"></div>
                    <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-amber-800 to-transparent"></div>
                </div>

                <p class="text-xs sm:text-base text-amber-900 max-w-xl mx-auto leading-relaxed font-sans font-medium">
                    {{ $tenant->hero_subtitle ?: ($tenant->description ?? 'Pengalaman perawatan pangkas rambut & grooming kehormatan budaya Jawa Keraton dalam suasana terang, bersih, dan dihiasi Batik Nusantara.') }}
                </p>

                <div class="pt-3 flex flex-wrap items-center justify-center gap-3 text-xs font-mono font-bold text-amber-900">
                    <span class="px-4 py-2 bg-white/90 border border-amber-900/15 rounded-2xl flex items-center gap-2 shadow-2xs">
                        <svg class="w-4 h-4 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $tenant->address ?? 'Alamat Outlet Barbershop' }}</span>
                    </span>
                </div>
            </div>
        </section>

        <!-- Main Body Grid -->
        <section class="py-12 max-w-5xl mx-auto px-4 sm:px-6 space-y-14">
            @if($booking_success)
                <div class="p-8 sm:p-10 rounded-3xl bg-white border-2 border-amber-800 text-center space-y-4 max-w-md mx-auto shadow-xl relative overflow-hidden">
                    <!-- EXACT BATIK CORNER ORNAMENTS (TOP LEFT & TOP RIGHT) -->
                    <img src="{{ $cornerBatik }}" alt="Batik Corner" class="absolute top-0 left-0 w-24 h-24 pointer-events-none opacity-85 z-10 select-none" />
                    <img src="{{ $cornerBatik }}" alt="Batik Corner" class="absolute top-0 right-0 w-24 h-24 transform scale-x-[-1] pointer-events-none opacity-85 z-10 select-none" />

                    <div class="w-16 h-16 rounded-full bg-amber-800 text-amber-50 flex items-center justify-center text-3xl font-black mx-auto shadow-md relative z-20">
                        ✓
                    </div>
                    <div class="space-y-1 relative z-20">
                        <div class="text-xs font-mono font-bold text-amber-800 tracking-widest uppercase">RESERVASI KERATON BERHASIL</div>
                        <h3 class="font-black text-2xl text-amber-950 font-serif">Pemesanan Diterima!</h3>
                        <p class="text-xs text-amber-900 font-sans">Kode Booking Anda: <strong class="font-mono text-amber-950 bg-amber-100 px-3.5 py-1.5 rounded-xl border border-amber-300 inline-block my-1 text-sm font-bold">{{ $created_reservation_code }}</strong></p>
                    </div>
                    <p class="text-xs text-amber-900 leading-relaxed font-sans relative z-20">
                        Matur nuwun telah melakukan pemesanan di <strong>{{ $tenant->name }}</strong>. Tim barbershop kami akan segera menghubungi WhatsApp Anda.
                    </p>
                    <button wire:click="$set('booking_success', false)" class="w-full py-4 bg-amber-800 hover:bg-amber-900 text-amber-50 font-extrabold text-xs uppercase tracking-widest rounded-2xl shadow-md transition relative z-20">
                        Buat Reservasi Baru
                    </button>
                </div>
            @else
                <div class="space-y-14">
                    @foreach($sectionOrder as $secKey)
                        
                        <!-- SECTION: SERVICES (KATALOG GROOMING) -->
                        @if($secKey === 'services' && $showServices)
                            <div class="p-6 sm:p-8 rounded-3xl bg-white border border-amber-900/15 space-y-6 shadow-sm relative overflow-hidden">
                                
                                <!-- EXACT BATIK CORNER ORNAMENTS (TOP LEFT & TOP RIGHT OF CONTAINER) -->
                                <img src="{{ $cornerBatik }}" alt="Batik Corner" class="absolute top-0 left-0 w-24 h-24 sm:w-36 sm:h-36 pointer-events-none opacity-80 z-10 select-none" />
                                <img src="{{ $cornerBatik }}" alt="Batik Corner" class="absolute top-0 right-0 w-24 h-24 sm:w-36 sm:h-36 transform scale-x-[-1] pointer-events-none opacity-80 z-10 select-none" />

                                <div class="flex items-center justify-between border-b border-amber-900/10 pb-4 relative z-20">
                                    <div>
                                        <div class="text-[10px] font-mono font-bold text-amber-800 uppercase tracking-widest">KATALOG GROOMING</div>
                                        <h3 class="text-base sm:text-xl font-bold text-amber-950 uppercase tracking-widest font-serif">KATALOG LAYANAN BATIK HERITAGE</h3>
                                    </div>
                                    <span class="text-[10px] font-mono text-amber-900 uppercase border border-amber-900/15 px-3 py-1 bg-amber-50 rounded-full hidden sm:block">Tarif Resmi</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 relative z-20">
                                    @forelse($services as $srv)
                                        <div class="p-5 rounded-2xl bg-[#FAF6F0] border border-amber-900/15 hover:border-amber-800 transition-all space-y-4 flex flex-col justify-between group shadow-2xs relative overflow-hidden">
                                            
                                            <!-- EXACT BATIK CORNER ORNAMENT (TOP LEFT & TOP RIGHT OF INDIVIDUAL CARD) -->
                                            <img src="{{ $cornerBatik }}" alt="Batik Corner" class="absolute top-0 left-0 w-16 h-16 pointer-events-none opacity-75 z-10 select-none" />
                                            <img src="{{ $cornerBatik }}" alt="Batik Corner" class="absolute top-0 right-0 w-16 h-16 transform scale-x-[-1] pointer-events-none opacity-75 z-10 select-none" />

                                            <div class="space-y-1.5 font-sans pt-3 relative z-20">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span class="font-bold text-xs text-amber-950 group-hover:text-amber-800 transition">{{ $srv->name }}</span>
                                                    <span class="text-[10px] font-mono text-amber-800 font-bold bg-amber-100 px-2 py-0.5 rounded-full flex-shrink-0">{{ $srv->duration_minutes }} Mnt</span>
                                                </div>
                                                <p class="text-[11px] text-amber-900/80 leading-relaxed">{{ $srv->description }}</p>
                                            </div>

                                            <div class="pt-3 border-t border-amber-900/10 flex items-center justify-between font-mono text-xs relative z-20">
                                                <span class="font-bold text-amber-50 bg-amber-800 px-3 py-1.5 rounded-xl whitespace-nowrap flex-shrink-0">Rp {{ number_format($srv->price, 0, ',', '.') }}</span>
                                                <button wire:click="$set('service_id', '{{ $srv->id }}')" class="text-[10px] font-bold text-amber-950 bg-amber-200/60 hover:bg-amber-900 hover:text-amber-50 px-3 py-1.5 rounded-xl transition">
                                                    Pilih Layanan
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-amber-900/60 py-6 text-center col-span-full font-sans">Belum ada layanan yang ditambahkan.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <!-- SECTION: BOOKING FORM (RESERVASI JADWAL) -->
                        @if($secKey === 'booking')
                            <div class="p-6 sm:p-8 rounded-3xl bg-white border border-amber-900/15 space-y-6 shadow-sm font-sans relative overflow-hidden">
                                
                                <!-- EXACT BATIK CORNER ORNAMENTS (TOP LEFT & TOP RIGHT OF RESERVASI JADWAL CARD) -->
                                <img src="{{ $cornerBatik }}" alt="Batik Corner" class="absolute top-0 left-0 w-24 h-24 sm:w-36 sm:h-36 pointer-events-none opacity-80 z-10 select-none" />
                                <img src="{{ $cornerBatik }}" alt="Batik Corner" class="absolute top-0 right-0 w-24 h-24 sm:w-36 sm:h-36 transform scale-x-[-1] pointer-events-none opacity-80 z-10 select-none" />

                                <div class="border-b border-amber-900/10 pb-4 pt-2 relative z-20">
                                    <div class="text-[10px] font-mono font-bold text-amber-800 uppercase tracking-widest">RESERVASI JADWAL</div>
                                    <h2 class="text-base sm:text-xl font-bold text-amber-950 uppercase tracking-widest font-serif">FORMULIR RESERVASI KERATON</h2>
                                    <p class="text-xs text-amber-900/80 mt-1">Lengkapi data Anda untuk memesan jam pangkas khusus.</p>
                                </div>

                                <form wire:submit.prevent="createBooking" class="space-y-4 text-xs relative z-20">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-900 uppercase font-bold tracking-wider mb-1.5">Nama Tamu</label>
                                            <input type="text" wire:model="customer_name" required placeholder="Doni Setiawan" class="w-full bg-[#FAF6F0] border border-amber-900/20 rounded-2xl px-4 py-3 text-xs text-stone-950 placeholder:text-stone-400 focus:border-amber-800 focus:bg-white focus:outline-none transition font-medium" />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-900 uppercase font-bold tracking-wider mb-1.5">Nomor WhatsApp</label>
                                            <input type="text" wire:model="customer_phone" required placeholder="081234567890" class="w-full bg-[#FAF6F0] border border-amber-900/20 rounded-2xl px-4 py-3 text-xs text-stone-950 placeholder:text-stone-400 focus:border-amber-800 focus:bg-white focus:outline-none transition font-medium" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-900 uppercase font-bold tracking-wider mb-1.5">Paket Layanan</label>
                                            <select wire:model="service_id" required class="w-full bg-[#FAF6F0] border border-amber-900/20 rounded-2xl px-4 py-3 text-xs font-bold text-stone-950 focus:border-amber-800 focus:bg-white focus:outline-none transition">
                                                <option value="">-- Pilih Paket Layanan --</option>
                                                @foreach($services as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price, 0, ',', '.') }} ({{ $s->duration_minutes }} Mnt)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-900 uppercase font-bold tracking-wider mb-1.5">Master Barber</label>
                                            <select wire:model="barber_user_id" class="w-full bg-[#FAF6F0] border border-amber-900/20 rounded-2xl px-4 py-3 text-xs font-bold text-stone-950 focus:border-amber-800 focus:bg-white focus:outline-none transition">
                                                <option value="">-- Bebas (Barber Ready) --</option>
                                                @foreach($barbers as $b)
                                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-900 uppercase font-bold tracking-wider mb-1.5">Tanggal Kedatangan</label>
                                            <input type="date" wire:model="reservation_date" required class="w-full bg-[#FAF6F0] border border-amber-900/20 rounded-2xl px-4 py-3 text-xs text-stone-950 focus:border-amber-800 focus:bg-white focus:outline-none transition font-medium" />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-900 uppercase font-bold tracking-wider mb-1.5">Jam Mulai</label>
                                            <input type="time" wire:model="start_time" required class="w-full bg-[#FAF6F0] border border-amber-900/20 rounded-2xl px-4 py-3 text-xs text-stone-950 focus:border-amber-800 focus:bg-white focus:outline-none transition font-medium" />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-mono text-amber-900 uppercase font-bold tracking-wider mb-1.5">Catatan Khusus</label>
                                        <textarea wire:model="notes" rows="2" placeholder="Catatan gaya potongan atau kebutuhan khusus..." class="w-full bg-[#FAF6F0] border border-amber-900/20 rounded-2xl px-4 py-3 text-xs text-stone-950 placeholder:text-stone-400 focus:border-amber-800 focus:bg-white focus:outline-none transition font-medium"></textarea>
                                    </div>

                                    <button type="submit" class="w-full py-4 bg-amber-800 hover:bg-amber-900 text-amber-50 font-extrabold text-xs uppercase tracking-widest rounded-2xl shadow-md transition">
                                        KIRIM RESERVASI KERATON
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- SECTION: PRODUCTS -->
                        @if($secKey === 'products' && $showProducts && $products->count() > 0)
                            <div class="p-6 sm:p-8 rounded-3xl bg-white border border-amber-900/15 space-y-6 shadow-sm relative overflow-hidden">
                                
                                <!-- EXACT BATIK CORNER ORNAMENTS (TOP LEFT & TOP RIGHT OF PRODUCTS CARD) -->
                                <img src="{{ $cornerBatik }}" alt="Batik Corner" class="absolute top-0 left-0 w-24 h-24 pointer-events-none opacity-80 z-10 select-none" />
                                <img src="{{ $cornerBatik }}" alt="Batik Corner" class="absolute top-0 right-0 w-24 h-24 transform scale-x-[-1] pointer-events-none opacity-80 z-10 select-none" />

                                <div class="border-b border-amber-900/10 pb-4 relative z-20">
                                    <h3 class="text-base sm:text-xl font-bold text-amber-950 uppercase tracking-widest font-serif">MINYAK & POMADE NUSANTARA</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 font-sans relative z-20">
                                    @foreach($products as $prd)
                                        <div class="p-4 rounded-2xl bg-[#FAF6F0] border border-amber-900/10 flex justify-between items-center relative overflow-hidden">
                                            <div>
                                                <div class="font-bold text-xs text-amber-950">{{ $prd->name }}</div>
                                                <div class="text-[10px] text-amber-800 font-mono mt-0.5">{{ $prd->category }}</div>
                                            </div>
                                            <span class="font-mono font-bold text-xs text-amber-50 bg-amber-800 px-3 py-1 rounded-xl whitespace-nowrap flex-shrink-0">Rp {{ number_format($prd->price, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    @endforeach
                </div>
            @endif
        </section>
    </main>

    <footer class="bg-white border-t border-amber-900/15 py-10 text-amber-900 text-xs text-center font-sans relative z-10">
        <div class="max-w-4xl mx-auto px-4 space-y-2">
            <div class="font-bold text-amber-950 uppercase tracking-wider font-serif text-sm">{{ $tenant->name }}</div>
            <div class="text-[11px] text-amber-800 font-mono">{{ $footerText }}</div>
            <div class="text-[10px] text-amber-900/60 pt-2">{{ $footerCopyright }}</div>
        </div>
    </footer>
</div>
