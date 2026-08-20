@php
    $shopLogo = $tenant->logo ? asset($tenant->logo) : asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
    $heroBg = $tenant->hero_banner ? asset($tenant->hero_banner) : asset('images/hero_barbershop_bg.jpg');
    $tagline = $tenant->hero_tagline ?? 'ROYAL EMERALD • LUXURY SALON & PRECISION GROOMING';
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

<div class="min-h-screen flex flex-col justify-between bg-[#061A14] text-emerald-100 font-serif selection:bg-emerald-500 selection:text-white w-full overflow-x-hidden relative">
    
    <!-- Royal Emerald Header -->
    <header class="bg-[#061A14]/95 border-b border-emerald-500/30 backdrop-blur-md sticky top-0 z-40 shadow-2xl">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between gap-4">
            <a href="{{ url('/' . $tenant->slug) }}" class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-full border-2 border-emerald-400 p-0.5 bg-[#030E0B] shadow-[0_0_15px_rgba(52,211,153,0.3)] flex-shrink-0">
                    <img src="{{ $shopLogo }}" alt="{{ $tenant->name }}" class="w-full h-full object-contain rounded-full" />
                </div>
                <div class="min-w-0">
                    <div class="font-bold text-base sm:text-xl text-emerald-300 tracking-wider uppercase font-serif truncate">{{ $tenant->name }}</div>
                    <div class="text-[9px] text-emerald-400/80 font-mono tracking-widest uppercase">ROYAL EMERALD SALON</div>
                </div>
            </a>
            <div class="hidden sm:block">
                <span class="text-[10px] font-mono text-amber-300 uppercase tracking-widest border border-amber-400/40 px-3 py-1 bg-amber-400/10 rounded-full">
                    VIP ROYAL SUITE
                </span>
            </div>
        </div>
    </header>

    <main class="flex-1 bg-[#061A14] w-full">
        <!-- Hero Section -->
        <section class="relative py-16 sm:py-24 bg-[#061A14] border-b border-emerald-500/20 text-center overflow-hidden px-4">
            <div class="absolute inset-0 bg-cover bg-center opacity-35 grayscale" style="background-image: url('{{ $heroBg }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-[#061A14]/70 via-[#061A14]/90 to-[#061A14]"></div>

            <div class="max-w-3xl mx-auto relative z-10 space-y-4">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-[10px] sm:text-xs font-mono tracking-widest uppercase shadow-[0_0_15px_rgba(52,211,153,0.15)]">
                    <span>{{ $tagline }}</span>
                </div>

                <h1 class="text-3xl sm:text-5xl font-extrabold text-amber-200 tracking-wide uppercase font-serif leading-tight drop-shadow-md">
                    {{ $tenant->hero_title ?: $tenant->name }}
                </h1>

                <div class="w-24 h-0.5 bg-gradient-to-r from-transparent via-amber-300 to-transparent mx-auto"></div>

                <p class="text-xs sm:text-sm text-emerald-200/80 max-w-xl mx-auto leading-relaxed font-sans">
                    {{ $tenant->hero_subtitle ?: ($tenant->description ?? 'Sensasi perawatan rambut & grooming kelas bangsawan dengan produk organik terbaik.') }}
                </p>

                <div class="text-[11px] text-emerald-300/80 font-mono tracking-wider pt-2 flex items-center justify-center gap-2">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $tenant->address ?? 'Alamat Outlet Barbershop' }}</span>
                    </span>
                </div>
            </div>
        </section>

        <!-- Main Body Content -->
        <section class="py-12 max-w-5xl mx-auto px-4 sm:px-6 space-y-12">
            @if($booking_success)
                <div class="p-8 rounded-3xl bg-[#0B2E24] border-2 border-amber-300 text-center space-y-4 max-w-md mx-auto shadow-[0_0_30px_rgba(252,211,77,0.2)]">
                    <div class="text-xl font-bold text-amber-300 uppercase tracking-widest font-serif">ROYAL RESERVATION CONFIRMED</div>
                    <div class="text-xs text-emerald-200 font-sans">Kode Booking Anda: <br><strong class="font-mono text-amber-200 bg-amber-400/20 px-4 py-1.5 border border-amber-300/40 inline-block my-2 text-sm rounded-xl">{{ $created_reservation_code }}</strong></div>
                    <p class="text-xs text-emerald-300/80 font-sans">Concierge royal salon kami akan menghubungi nomor WhatsApp Anda.</p>
                    <button wire:click="$set('booking_success', false)" class="w-full py-3.5 bg-amber-300 hover:bg-amber-200 text-emerald-950 font-bold text-xs uppercase tracking-widest rounded-2xl shadow-lg transition">Buat Reservasi Baru</button>
                </div>
            @else
                <div class="space-y-12">
                    @foreach($sectionOrder as $secKey)
                        
                        <!-- SECTION: SERVICES -->
                        @if($secKey === 'services' && $showServices)
                            <div class="p-6 sm:p-8 rounded-3xl bg-[#0B2E24]/90 border border-emerald-500/30 space-y-6 shadow-2xl">
                                <div class="flex items-center justify-between border-b border-emerald-500/20 pb-3">
                                    <h3 class="text-base sm:text-lg font-bold text-amber-300 uppercase tracking-widest font-serif">KATALOG LAYANAN ROYAL</h3>
                                    <span class="text-[10px] font-mono text-emerald-300/60 uppercase">Menu Eksklusif</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse($services as $srv)
                                        <div class="p-4 rounded-2xl bg-[#04130E] border border-emerald-500/20 hover:border-amber-300/50 transition-all space-y-2 flex flex-col justify-between group">
                                            <div class="space-y-1 font-sans">
                                                <div class="font-bold text-xs text-emerald-200 group-hover:text-amber-200 transition">{{ $srv->name }}</div>
                                                <p class="text-[11px] text-emerald-400/70 leading-relaxed">{{ $srv->description }}</p>
                                            </div>
                                            <div class="flex items-center justify-between pt-2 border-t border-emerald-950 font-mono text-xs">
                                                <span class="text-[10px] text-emerald-400/60">{{ $srv->duration_minutes }} Mnt</span>
                                                <span class="font-bold text-amber-300 bg-amber-400/10 border border-amber-300/30 px-3 py-1 rounded-xl whitespace-nowrap flex-shrink-0">Rp {{ number_format($srv->price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-emerald-500 py-6 text-center col-span-full font-sans">Belum ada layanan yang ditambahkan.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <!-- SECTION: BOOKING FORM -->
                        @if($secKey === 'booking')
                            <div class="p-6 sm:p-8 rounded-3xl bg-[#0B2E24]/90 border border-emerald-500/30 space-y-6 shadow-2xl font-sans">
                                <div class="border-b border-emerald-500/20 pb-3">
                                    <h2 class="text-base sm:text-lg font-bold text-amber-300 uppercase tracking-widest font-serif">FORMULIR RESERVASI ROYAL SUITE</h2>
                                    <p class="text-xs text-emerald-300/80 mt-1">Lengkapi formulir untuk pemesanan jam kedatangan Anda.</p>
                                </div>

                                <form wire:submit.prevent="createBooking" class="space-y-4 text-xs">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-200 uppercase tracking-wider mb-1.5">Nama Tamu</label>
                                            <input type="text" wire:model="customer_name" required placeholder="Doni Setiawan" class="w-full bg-[#04130E] border border-emerald-500/30 rounded-2xl px-4 py-3 text-xs text-emerald-100 placeholder:text-emerald-700 focus:border-amber-300 focus:outline-none transition" />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-200 uppercase tracking-wider mb-1.5">Nomor WhatsApp</label>
                                            <input type="text" wire:model="customer_phone" required placeholder="081234567890" class="w-full bg-[#04130E] border border-emerald-500/30 rounded-2xl px-4 py-3 text-xs text-emerald-100 placeholder:text-emerald-700 focus:border-amber-300 focus:outline-none transition" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-200 uppercase tracking-wider mb-1.5">Paket Layanan</label>
                                            <select wire:model="service_id" required class="w-full bg-[#04130E] border border-emerald-500/30 rounded-2xl px-4 py-3 text-xs font-semibold text-emerald-100 focus:border-amber-300 focus:outline-none transition">
                                                <option value="">-- Pilih Paket Layanan --</option>
                                                @foreach($services as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price, 0, ',', '.') }} ({{ $s->duration_minutes }} Mnt)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-200 uppercase tracking-wider mb-1.5">Master Barber</label>
                                            <select wire:model="barber_user_id" class="w-full bg-[#04130E] border border-emerald-500/30 rounded-2xl px-4 py-3 text-xs font-semibold text-emerald-100 focus:border-amber-300 focus:outline-none transition">
                                                <option value="">-- Bebas (Barber Ready) --</option>
                                                @foreach($barbers as $b)
                                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-200 uppercase tracking-wider mb-1.5">Tanggal Kedatangan</label>
                                            <input type="date" wire:model="reservation_date" required class="w-full bg-[#04130E] border border-emerald-500/30 rounded-2xl px-4 py-3 text-xs text-emerald-100 focus:border-amber-300 focus:outline-none transition" />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-200 uppercase tracking-wider mb-1.5">Jam Mulai</label>
                                            <input type="time" wire:model="start_time" required class="w-full bg-[#04130E] border border-emerald-500/30 rounded-2xl px-4 py-3 text-xs text-emerald-100 focus:border-amber-300 focus:outline-none transition" />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-mono text-amber-200 uppercase tracking-wider mb-1.5">Catatan Khusus</label>
                                        <textarea wire:model="notes" rows="2" placeholder="Catatan potongan rambut atau permintaan khusus..." class="w-full bg-[#04130E] border border-emerald-500/30 rounded-2xl px-4 py-3 text-xs text-emerald-100 placeholder:text-emerald-700 focus:border-amber-300 focus:outline-none transition"></textarea>
                                    </div>

                                    <button type="submit" class="w-full py-4 bg-amber-300 hover:bg-amber-200 text-emerald-950 font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl transition">
                                        KIRIM RESERVASI ROYAL
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- SECTION: PRODUCTS -->
                        @if($secKey === 'products' && $showProducts && $products->count() > 0)
                            <div class="p-6 sm:p-8 rounded-3xl bg-[#0B2E24]/90 border border-emerald-500/30 space-y-6 shadow-2xl">
                                <div class="border-b border-emerald-500/20 pb-3">
                                    <h3 class="text-base sm:text-lg font-bold text-amber-300 uppercase tracking-widest font-serif">ROYAL GROOMING COLLECTION</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 font-sans">
                                    @foreach($products as $prd)
                                        <div class="p-4 rounded-2xl bg-[#04130E] border border-emerald-500/20 flex justify-between items-center">
                                            <div>
                                                <div class="font-bold text-xs text-emerald-200">{{ $prd->name }}</div>
                                                <div class="text-[10px] text-emerald-400/60 mt-0.5">{{ $prd->category }}</div>
                                            </div>
                                            <span class="font-mono font-bold text-xs text-amber-300 bg-amber-400/10 border border-amber-300/30 px-3 py-1 rounded-xl whitespace-nowrap flex-shrink-0">Rp {{ number_format($prd->price, 0, ',', '.') }}</span>
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

    <footer class="bg-[#030E0B] border-t border-emerald-500/20 py-8 text-emerald-300/60 text-xs text-center font-sans">
        <div class="max-w-4xl mx-auto px-4 space-y-1">
            <div class="font-bold text-amber-300 uppercase tracking-wider font-serif">{{ $tenant->name }}</div>
            <div class="text-[11px] text-emerald-400/70">{{ $footerText }}</div>
            <div class="text-[10px] text-emerald-500/50 pt-2">{{ $footerCopyright }}</div>
        </div>
    </footer>
</div>
