@php
    $shopLogo = $tenant->logo ? asset($tenant->logo) : asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
    $heroBg = $tenant->hero_banner ? asset($tenant->hero_banner) : asset('images/hero_barbershop_bg.jpg');
    $tagline = $tenant->hero_tagline ?? 'FUTURE BARBER 2088 • NEON CUTS & MATRIX FADE';
    $btnRadius = 'rounded-none';

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

<div class="min-h-screen flex flex-col justify-between bg-slate-950 text-rose-100 font-sans selection:bg-rose-500 selection:text-white w-full overflow-x-hidden relative">
    
    <!-- Cyber Neon Header -->
    <header class="bg-slate-950/90 border-b border-rose-500/40 backdrop-blur-md sticky top-0 z-40 shadow-[0_0_20px_rgba(244,63,94,0.3)]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-18 flex items-center justify-between gap-4">
            <a href="{{ url('/' . $tenant->slug) }}" class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 border-2 border-rose-500 bg-slate-900 p-1 shadow-[0_0_15px_rgba(244,63,94,0.5)] flex-shrink-0">
                    <img src="{{ $shopLogo }}" alt="{{ $tenant->name }}" class="w-full h-full object-contain" />
                </div>
                <div class="min-w-0">
                    <div class="font-black italic text-base sm:text-xl text-rose-500 tracking-wider uppercase truncate">{{ $tenant->name }}</div>
                    <div class="text-[10px] text-cyan-400 font-mono tracking-widest uppercase">CYBERPUNK BARBER STUDIO</div>
                </div>
            </a>
            <div class="hidden sm:block">
                <span class="px-3 py-1 bg-rose-500/10 border border-rose-500/40 text-rose-400 font-mono text-[10px] uppercase tracking-widest shadow-[0_0_10px_rgba(244,63,94,0.2)]">
                    SYS: ONLINE
                </span>
            </div>
        </div>
    </header>

    <main class="flex-1 bg-slate-950 w-full">
        <!-- Hero Section -->
        <section class="relative py-16 sm:py-24 bg-slate-950 border-b border-rose-500/30 text-center overflow-hidden px-4">
            <div class="absolute inset-0 bg-cover bg-center opacity-40 mix-blend-color-dodge" style="background-image: url('{{ $heroBg }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/60 via-slate-950/85 to-slate-950"></div>

            <div class="max-w-4xl mx-auto relative z-10 space-y-4">
                <div class="inline-block">
                    <span class="px-4 py-1.5 bg-rose-500/20 border border-rose-500/60 text-rose-400 text-xs font-mono font-bold tracking-widest uppercase rounded-full shadow-[0_0_15px_rgba(244,63,94,0.4)]">
                        {{ $tagline }}
                    </span>
                </div>

                <h1 class="text-3xl sm:text-5xl md:text-6xl font-black italic uppercase tracking-tighter text-white drop-shadow-[0_0_25px_rgba(244,63,94,0.6)]">
                    {{ $tenant->hero_title ?: $tenant->name }}
                </h1>

                <p class="text-xs sm:text-sm text-cyan-300/80 max-w-xl mx-auto font-mono leading-relaxed">
                    {{ $tenant->hero_subtitle ?: ($tenant->description ?? 'Gaya potong futuristik dengan presisi tinggi dan vibes neon cyberpunk.') }}
                </p>

                <div class="pt-2 text-xs font-mono text-cyan-400 flex items-center justify-center gap-2">
                    <span class="px-3 py-1 bg-slate-900 border border-cyan-500/40 rounded-lg flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $tenant->address ?? 'Alamat Outlet Barbershop' }}</span>
                    </span>
                </div>
            </div>
        </section>

        <!-- Main Body Grid -->
        <section class="py-12 max-w-6xl mx-auto px-4 sm:px-6 space-y-12">
            @if($booking_success)
                <div class="p-8 bg-slate-900 border-2 border-rose-500 text-center space-y-4 max-w-md mx-auto shadow-[0_0_30px_rgba(244,63,94,0.4)] relative" style="clip-path: polygon(0 16px, 16px 0, calc(100% - 16px) 0, 100% 16px, 100% calc(100% - 16px), calc(100% - 16px) 100%, 16px 100%, 0 calc(100% - 16px));">
                    <div class="text-2xl font-black italic text-rose-500 uppercase tracking-tighter">SYSTEM RESERVATION COMPLETE</div>
                    <div class="text-xs text-slate-300 font-mono">CODE: <br><strong class="text-base text-cyan-300 bg-rose-500/20 px-4 py-1.5 border border-rose-500/40 inline-block my-2 font-mono font-bold">{{ $created_reservation_code }}</strong></div>
                    <p class="text-xs text-slate-400 font-mono">Konfirmasi akan langsung dikirim via WhatsApp.</p>
                    <button wire:click="$set('booking_success', false)" class="w-full py-3.5 bg-rose-600 hover:bg-rose-500 text-white font-black text-xs uppercase tracking-widest shadow-lg">Buat Reservasi Baru</button>
                </div>
            @else
                <div class="space-y-12">
                    @foreach($sectionOrder as $secKey)
                        
                        <!-- SECTION: SERVICES -->
                        @if($secKey === 'services' && $showServices)
                            <!-- CYBERPUNK 2077 HUD CHAMFERED CONTAINER -->
                            <div class="relative bg-slate-900/90 border-2 border-rose-500/60 p-6 sm:p-8 space-y-6 shadow-[0_0_30px_rgba(244,63,94,0.25)]" style="clip-path: polygon(0 20px, 20px 0, calc(100% - 20px) 0, 100% 20px, 100% calc(100% - 20px), calc(100% - 20px) 100%, 20px 100%, 0 calc(100% - 20px));">
                                
                                <!-- Slanted HUD Header Tab -->
                                <div class="flex items-center justify-between border-b-2 border-rose-500/40 pb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 bg-rose-500 transform rotate-45 shadow-[0_0_8px_#f43f5e]"></div>
                                        <h3 class="text-base sm:text-xl font-black italic text-rose-500 uppercase tracking-wider">KATALOG LAYANAN CYBERPUNK</h3>
                                    </div>
                                    <span class="text-xs font-mono text-cyan-400 uppercase font-bold tracking-widest">SYS.MENU // 2088</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse($services as $srv)
                                        <!-- CYBERPUNK CHAMFERED CARD -->
                                        <div class="p-5 bg-slate-950 border border-rose-500/30 hover:border-cyan-400 transition-all space-y-4 flex flex-col justify-between group relative" style="clip-path: polygon(0 12px, 12px 0, 100% 0, 100% calc(100% - 12px), calc(100% - 12px) 100%, 0 100%);">
                                            
                                            <div class="space-y-1">
                                                <div class="font-black italic text-sm text-white group-hover:text-cyan-300 uppercase tracking-tight transition">{{ $srv->name }}</div>
                                                <p class="text-xs text-slate-400 font-mono leading-relaxed">{{ $srv->description }}</p>
                                            </div>

                                            <div class="flex items-center justify-between pt-3 border-t border-slate-900 font-mono text-xs">
                                                <span class="text-[10px] text-cyan-400">{{ $srv->duration_minutes }} Mins</span>
                                                <span class="font-bold text-rose-400 bg-rose-500/10 border border-rose-500/50 px-3 py-1 whitespace-nowrap flex-shrink-0">Rp {{ number_format($srv->price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-500 py-6 text-center col-span-full font-mono">Belum ada layanan yang ditambahkan.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <!-- SECTION: BOOKING FORM -->
                        @if($secKey === 'booking')
                            <!-- CYBERPUNK 2077 HUD CHAMFERED FORM CONTAINER -->
                            <div class="relative bg-slate-900/90 border-2 border-rose-500/60 p-6 sm:p-8 space-y-6 shadow-[0_0_30px_rgba(244,63,94,0.25)] font-mono" style="clip-path: polygon(0 20px, 20px 0, calc(100% - 20px) 0, 100% 20px, 100% calc(100% - 20px), calc(100% - 20px) 100%, 20px 100%, 0 calc(100% - 20px));">
                                
                                <div class="flex items-center justify-between border-b-2 border-rose-500/40 pb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 bg-cyan-400 transform rotate-45 shadow-[0_0_8px_#22d3ee]"></div>
                                        <h2 class="text-base sm:text-xl font-black italic text-rose-500 uppercase tracking-wider">FORMULIR RESERVATION CYBER</h2>
                                    </div>
                                    <span class="text-xs font-mono text-cyan-400 font-bold uppercase">RESERVATION // ONLINE</span>
                                </div>

                                <form wire:submit.prevent="createBooking" class="space-y-4 text-xs">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] text-rose-400 font-bold uppercase tracking-wider mb-1.5">Nama Customer</label>
                                            <input type="text" wire:model="customer_name" required placeholder="Doni Setiawan" class="w-full bg-slate-950 border border-rose-500/40 px-4 py-3 text-xs text-white placeholder:text-slate-600 focus:border-cyan-400 focus:outline-none transition" />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] text-rose-400 font-bold uppercase tracking-wider mb-1.5">WhatsApp Active</label>
                                            <input type="text" wire:model="customer_phone" required placeholder="081234567890" class="w-full bg-slate-950 border border-rose-500/40 px-4 py-3 text-xs text-white placeholder:text-slate-600 focus:border-cyan-400 focus:outline-none transition" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] text-rose-400 font-bold uppercase tracking-wider mb-1.5">Pilih Service</label>
                                            <select wire:model="service_id" required class="w-full bg-slate-950 border border-rose-500/40 px-4 py-3 text-xs font-bold text-white focus:border-cyan-400 focus:outline-none transition">
                                                <option value="">-- Select Service --</option>
                                                @foreach($services as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price, 0, ',', '.') }} ({{ $s->duration_minutes }} Mnt)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] text-rose-400 font-bold uppercase tracking-wider mb-1.5">Barber Specialist</label>
                                            <select wire:model="barber_user_id" class="w-full bg-slate-950 border border-rose-500/40 px-4 py-3 text-xs font-bold text-white focus:border-cyan-400 focus:outline-none transition">
                                                <option value="">-- Any Ready Barber --</option>
                                                @foreach($barbers as $b)
                                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] text-rose-400 font-bold uppercase tracking-wider mb-1.5">Tanggal</label>
                                            <input type="date" wire:model="reservation_date" required class="w-full bg-slate-950 border border-rose-500/40 px-4 py-3 text-xs text-white focus:border-cyan-400 focus:outline-none transition" />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] text-rose-400 font-bold uppercase tracking-wider mb-1.5">Jam Mulai</label>
                                            <input type="time" wire:model="start_time" required class="w-full bg-slate-950 border border-rose-500/40 px-4 py-3 text-xs text-white focus:border-cyan-400 focus:outline-none transition" />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] text-rose-400 font-bold uppercase tracking-wider mb-1.5">Catatan Request Model</label>
                                        <textarea wire:model="notes" rows="2" placeholder="Cyber Fade, Color Highlights..." class="w-full bg-slate-950 border border-rose-500/40 px-4 py-3 text-xs text-white placeholder:text-slate-600 focus:border-cyan-400 focus:outline-none transition"></textarea>
                                    </div>

                                    <button type="submit" class="w-full py-4 bg-rose-600 hover:bg-rose-500 text-white font-black text-xs uppercase tracking-widest shadow-[0_0_20px_rgba(244,63,94,0.5)] transition" style="clip-path: polygon(0 8px, 8px 0, 100% 0, 100% calc(100% - 8px), calc(100% - 8px) 100%, 0 100%);">
                                        TRANSMIT RESERVATION
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- SECTION: PRODUCTS -->
                        @if($secKey === 'products' && $showProducts && $products->count() > 0)
                            <div class="relative bg-slate-900/90 border-2 border-rose-500/60 p-6 sm:p-8 space-y-6 shadow-[0_0_30px_rgba(244,63,94,0.25)]" style="clip-path: polygon(0 20px, 20px 0, calc(100% - 20px) 0, 100% 20px, 100% calc(100% - 20px), calc(100% - 20px) 100%, 20px 100%, 0 calc(100% - 20px));">
                                <div class="flex items-center justify-between border-b-2 border-rose-500/40 pb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 bg-rose-500 transform rotate-45 shadow-[0_0_8px_#f43f5e]"></div>
                                        <h3 class="text-base sm:text-xl font-black italic text-rose-500 uppercase tracking-wider">PRODUCTS & POMADE</h3>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 font-mono">
                                    @foreach($products as $prd)
                                        <div class="p-4 bg-slate-950 border border-rose-500/30 flex justify-between items-center" style="clip-path: polygon(0 10px, 10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%);">
                                            <div>
                                                <div class="font-bold text-xs text-white uppercase">{{ $prd->name }}</div>
                                                <div class="text-[10px] text-cyan-400 mt-0.5">{{ $prd->category }}</div>
                                            </div>
                                            <span class="font-bold text-xs text-rose-400 bg-rose-500/10 border border-rose-500/40 px-3 py-1 whitespace-nowrap flex-shrink-0">Rp {{ number_format($prd->price, 0, ',', '.') }}</span>
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

    <footer class="bg-slate-950 border-t border-rose-500/30 py-8 text-cyan-400 text-xs text-center font-mono">
        <div class="max-w-4xl mx-auto px-4 space-y-1">
            <div class="font-black text-rose-500 uppercase tracking-widest text-sm">{{ $tenant->name }}</div>
            <div class="text-[11px] text-slate-400">{{ $footerText }}</div>
            <div class="text-[10px] text-slate-600 pt-2">{{ $footerCopyright }}</div>
        </div>
    </footer>
</div>
