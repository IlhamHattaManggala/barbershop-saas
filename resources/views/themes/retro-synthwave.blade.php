@php
    $shopLogo = $tenant->logo ? asset($tenant->logo) : asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
    $heroBg = $tenant->hero_banner ? asset($tenant->hero_banner) : asset('images/hero_barbershop_bg.jpg');
    $tagline = $tenant->hero_tagline ?? 'RETRO 80S SYNTHWAVE • NEON NIGHTS & VAPOR CUTS';
    $btnRadius = 'rounded-xl';

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

<div class="min-h-screen flex flex-col justify-between bg-purple-950 text-fuchsia-100 font-sans selection:bg-fuchsia-500 selection:text-white w-full overflow-x-hidden relative">
    
    <!-- 80s Synthwave Header -->
    <header class="bg-purple-950/90 border-b border-fuchsia-500/50 backdrop-blur-md sticky top-0 z-40 shadow-[0_0_20px_rgba(217,70,239,0.3)]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-18 flex items-center justify-between gap-4">
            <a href="{{ url('/' . $tenant->slug) }}" class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl border-2 border-cyan-400 bg-purple-900 p-1 shadow-[0_0_15px_rgba(34,211,238,0.5)] flex-shrink-0">
                    <img src="{{ $shopLogo }}" alt="{{ $tenant->name }}" class="w-full h-full object-contain rounded" />
                </div>
                <div class="min-w-0">
                    <div class="font-black italic text-base sm:text-xl text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-fuchsia-400 to-amber-300 tracking-wider uppercase truncate">{{ $tenant->name }}</div>
                    <div class="text-[10px] text-cyan-300 font-mono tracking-widest uppercase">RETRO 80S SYNTHWAVE</div>
                </div>
            </a>
            <div class="hidden sm:block">
                <span class="px-3 py-1 bg-fuchsia-500/20 border border-fuchsia-400/50 text-fuchsia-300 font-mono text-[10px] uppercase tracking-widest shadow-[0_0_10px_rgba(217,70,239,0.3)]">
                    ARCADE VIBES
                </span>
            </div>
        </div>
    </header>

    <main class="flex-1 bg-purple-950 w-full">
        <!-- Hero Section -->
        <section class="relative py-16 sm:py-24 bg-gradient-to-b from-purple-950 via-slate-950 to-purple-950 border-b border-fuchsia-500/30 text-center overflow-hidden px-4">
            <div class="absolute inset-0 bg-cover bg-center opacity-35 mix-blend-screen" style="background-image: url('{{ $heroBg }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-purple-950/60 via-purple-950/80 to-purple-950"></div>

            <div class="max-w-4xl mx-auto relative z-10 space-y-4">
                <div class="inline-block">
                    <span class="px-4 py-1.5 bg-fuchsia-500/20 border border-fuchsia-400/50 text-fuchsia-300 text-xs font-mono font-bold tracking-widest uppercase rounded-full shadow-[0_0_15px_rgba(217,70,239,0.3)]">
                        {{ $tagline }}
                    </span>
                </div>

                <h1 class="text-4xl sm:text-6xl md:text-7xl font-black italic uppercase tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-fuchsia-400 to-amber-300 drop-shadow-[0_0_30px_rgba(217,70,239,0.8)]">
                    {{ $tenant->hero_title ?: $tenant->name }}
                </h1>

                <p class="text-xs sm:text-sm text-cyan-200 max-w-xl mx-auto font-mono leading-relaxed">
                    {{ $tenant->hero_subtitle ?: ($tenant->description ?? 'Sensasi cukur retro 80s dengan musik synthwave dan hasil potongan rambut trendy.') }}
                </p>

                <div class="pt-2 text-xs font-mono text-cyan-300 flex items-center justify-center gap-2">
                    <span class="px-3 py-1 bg-purple-900/80 border border-cyan-400/40 rounded-xl flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $tenant->address ?? 'Alamat Outlet Barbershop' }}</span>
                    </span>
                </div>
            </div>
        </section>

        <!-- Main Body Grid -->
        <section class="py-12 max-w-6xl mx-auto px-4 sm:px-6 space-y-12">
            @if($booking_success)
                <div class="p-8 rounded-3xl bg-purple-900 border-2 border-cyan-400 text-center space-y-4 max-w-md mx-auto shadow-[0_0_30px_rgba(34,211,238,0.4)]">
                    <div class="text-2xl font-black italic text-cyan-300 uppercase tracking-tighter">80S BOOKING SUCCESS</div>
                    <div class="text-xs text-fuchsia-200 font-mono">CODE: <br><strong class="text-base text-cyan-300 bg-purple-950 px-4 py-1.5 border border-cyan-400/50 inline-block my-2 font-mono font-bold rounded-xl">{{ $created_reservation_code }}</strong></div>
                    <p class="text-xs text-fuchsia-300/80 font-mono">Konfirmasi reservasi akan dikirim via WhatsApp.</p>
                    <button wire:click="$set('booking_success', false)" class="w-full py-3.5 bg-gradient-to-r from-cyan-400 to-fuchsia-500 hover:from-cyan-300 hover:to-fuchsia-400 text-purple-950 font-black text-xs uppercase tracking-widest rounded-xl shadow-lg">Buat Reservasi Baru</button>
                </div>
            @else
                <div class="space-y-12">
                    @foreach($sectionOrder as $secKey)
                        
                        <!-- SECTION: SERVICES -->
                        @if($secKey === 'services' && $showServices)
                            <div class="p-6 sm:p-8 rounded-3xl bg-purple-900/80 border border-fuchsia-500/40 space-y-6 shadow-2xl">
                                <div class="flex items-center justify-between border-b border-fuchsia-500/30 pb-3">
                                    <h3 class="text-base sm:text-xl font-black italic text-cyan-300 uppercase tracking-wider">KATALOG RETRO SYNTHWAVE</h3>
                                    <span class="text-xs font-mono text-fuchsia-400 uppercase">80S MENU</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse($services as $srv)
                                        <div class="p-4 rounded-2xl bg-purple-950 border border-fuchsia-500/30 hover:border-cyan-400 transition-all space-y-3 flex flex-col justify-between group">
                                            <div class="space-y-1 font-sans">
                                                <div class="font-black italic text-sm text-fuchsia-200 group-hover:text-cyan-300 uppercase tracking-tight">{{ $srv->name }}</div>
                                                <p class="text-xs text-fuchsia-300/70 font-mono leading-relaxed">{{ $srv->description }}</p>
                                            </div>
                                            <div class="flex items-center justify-between pt-2 border-t border-purple-900 font-mono text-xs">
                                                <span class="text-[10px] text-cyan-400">{{ $srv->duration_minutes }} Mins</span>
                                                <span class="font-bold text-cyan-300 bg-cyan-400/10 border border-cyan-400/40 px-3 py-1 rounded-xl whitespace-nowrap flex-shrink-0">Rp {{ number_format($srv->price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-fuchsia-400 py-6 text-center col-span-full font-mono">Belum ada layanan yang ditambahkan.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <!-- SECTION: BOOKING FORM -->
                        @if($secKey === 'booking')
                            <div class="p-6 sm:p-8 rounded-3xl bg-purple-900/80 border border-fuchsia-500/40 space-y-6 shadow-2xl font-mono">
                                <div class="border-b border-fuchsia-500/30 pb-3">
                                    <h2 class="text-base sm:text-xl font-black italic text-cyan-300 uppercase tracking-wider">FORMULIR BOOKING RETRO 80S</h2>
                                    <p class="text-xs text-fuchsia-300/80 mt-1">Pilih jam kedatangan pangkas Anda.</p>
                                </div>

                                <form wire:submit.prevent="createBooking" class="space-y-4 text-xs">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] text-cyan-300 font-bold uppercase tracking-wider mb-1.5">Nama Customer</label>
                                            <input type="text" wire:model="customer_name" required placeholder="Doni Setiawan" class="w-full bg-purple-950 border border-fuchsia-500/40 rounded-xl px-4 py-3 text-xs text-fuchsia-100 placeholder:text-purple-600 focus:border-cyan-400 focus:outline-none transition" />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] text-cyan-300 font-bold uppercase tracking-wider mb-1.5">WhatsApp Active</label>
                                            <input type="text" wire:model="customer_phone" required placeholder="081234567890" class="w-full bg-purple-950 border border-fuchsia-500/40 rounded-xl px-4 py-3 text-xs text-fuchsia-100 placeholder:text-purple-600 focus:border-cyan-400 focus:outline-none transition" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] text-cyan-300 font-bold uppercase tracking-wider mb-1.5">Pilih Service</label>
                                            <select wire:model="service_id" required class="w-full bg-purple-950 border border-fuchsia-500/40 rounded-xl px-4 py-3 text-xs font-bold text-fuchsia-100 focus:border-cyan-400 focus:outline-none transition">
                                                <option value="">-- Select Service --</option>
                                                @foreach($services as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price, 0, ',', '.') }} ({{ $s->duration_minutes }} Mnt)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] text-cyan-300 font-bold uppercase tracking-wider mb-1.5">Barber Specialist</label>
                                            <select wire:model="barber_user_id" class="w-full bg-purple-950 border border-fuchsia-500/40 rounded-xl px-4 py-3 text-xs font-bold text-fuchsia-100 focus:border-cyan-400 focus:outline-none transition">
                                                <option value="">-- Any Ready Barber --</option>
                                                @foreach($barbers as $b)
                                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] text-cyan-300 font-bold uppercase tracking-wider mb-1.5">Tanggal</label>
                                            <input type="date" wire:model="reservation_date" required class="w-full bg-purple-950 border border-fuchsia-500/40 rounded-xl px-4 py-3 text-xs text-fuchsia-100 focus:border-cyan-400 focus:outline-none transition" />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] text-cyan-300 font-bold uppercase tracking-wider mb-1.5">Jam Mulai</label>
                                            <input type="time" wire:model="start_time" required class="w-full bg-purple-950 border border-fuchsia-500/40 rounded-xl px-4 py-3 text-xs text-fuchsia-100 focus:border-cyan-400 focus:outline-none transition" />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] text-cyan-300 font-bold uppercase tracking-wider mb-1.5">Catatan Request Model</label>
                                        <textarea wire:model="notes" rows="2" placeholder="Mullet, Pompadour 80s..." class="w-full bg-purple-950 border border-fuchsia-500/40 rounded-xl px-4 py-3 text-xs text-fuchsia-100 placeholder:text-purple-600 focus:border-cyan-400 focus:outline-none transition"></textarea>
                                    </div>

                                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-cyan-400 to-fuchsia-500 hover:from-cyan-300 hover:to-fuchsia-400 text-purple-950 font-black text-xs uppercase tracking-widest rounded-xl shadow-[0_0_20px_rgba(34,211,238,0.5)] transition">
                                        TRANSMIT RESERVATION
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- SECTION: PRODUCTS -->
                        @if($secKey === 'products' && $showProducts && $products->count() > 0)
                            <div class="p-6 sm:p-8 rounded-3xl bg-purple-900/80 border border-fuchsia-500/40 space-y-6 shadow-2xl">
                                <div class="border-b border-fuchsia-500/30 pb-3">
                                    <h3 class="text-base sm:text-xl font-black italic text-cyan-300 uppercase tracking-wider">PRODUCTS & POMADE</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 font-mono">
                                    @foreach($products as $prd)
                                        <div class="p-4 rounded-2xl bg-purple-950 border border-fuchsia-500/30 flex justify-between items-center">
                                            <div>
                                                <div class="font-bold text-xs text-fuchsia-200 uppercase">{{ $prd->name }}</div>
                                                <div class="text-[10px] text-cyan-400 mt-0.5">{{ $prd->category }}</div>
                                            </div>
                                            <span class="font-bold text-xs text-cyan-300 bg-cyan-400/10 border border-cyan-400/40 px-3 py-1 rounded-xl whitespace-nowrap flex-shrink-0">Rp {{ number_format($prd->price, 0, ',', '.') }}</span>
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

    <footer class="bg-purple-950 border-t border-fuchsia-500/40 py-8 text-cyan-300 text-xs text-center font-mono">
        <div class="max-w-4xl mx-auto px-4 space-y-1">
            <div class="font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-fuchsia-400 uppercase tracking-widest text-sm">{{ $tenant->name }}</div>
            <div class="text-[11px] text-fuchsia-300/70">{{ $footerText }}</div>
            <div class="text-[10px] text-fuchsia-400/50 pt-2">{{ $footerCopyright }}</div>
        </div>
    </footer>
</div>
