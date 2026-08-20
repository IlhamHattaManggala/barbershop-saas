@php
    $shopLogo = $tenant->logo ? asset($tenant->logo) : asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
    $heroBg = $tenant->hero_banner ? asset($tenant->hero_banner) : asset('images/hero_barbershop_bg.jpg');
    $tagline = $tenant->hero_tagline ?? '1920s CLASSIC GENTLEMAN\'S CLUB & SPEAKEASY';
    $btnRadius = 'rounded-none';
    $color = $tenant->primary_color ?? 'amber';

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

<div class="min-h-screen flex flex-col justify-between bg-zinc-950 text-amber-100 font-serif selection:bg-amber-400 selection:text-zinc-950 w-full overflow-x-hidden relative">
    
    <!-- Gold Ornamental Header -->
    <header class="bg-zinc-950/95 border-b border-amber-500/30 backdrop-blur-md sticky top-0 z-40 shadow-2xl">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between gap-4">
            <a href="{{ url('/' . $tenant->slug) }}" class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-full border-2 border-amber-400 p-0.5 bg-zinc-900 shadow-[0_0_15px_rgba(251,191,36,0.25)] flex-shrink-0">
                    <img src="{{ $shopLogo }}" alt="{{ $tenant->name }}" class="w-full h-full object-contain rounded-full" />
                </div>
                <div class="min-w-0">
                    <div class="font-bold text-base sm:text-xl text-amber-400 tracking-widest uppercase font-serif truncate">{{ $tenant->name }}</div>
                    <div class="text-[9px] text-amber-200/70 font-mono tracking-widest uppercase">EST. 1920 &bull; VINTAGE NOIR SPEAKEASY</div>
                </div>
            </a>
            <div class="hidden sm:block text-right">
                <span class="text-[10px] font-mono text-amber-400/80 uppercase tracking-widest border border-amber-500/30 px-3 py-1 bg-amber-500/5">MEMBERSHIP CLUB</span>
            </div>
        </div>
    </header>

    <main class="flex-1 bg-zinc-950 w-full">
        <!-- Hero Section -->
        <section class="relative py-16 sm:py-24 bg-zinc-950 border-b border-amber-500/20 text-center overflow-hidden px-4">
            <div class="absolute inset-0 bg-cover bg-center opacity-40 grayscale" style="background-image: url('{{ $heroBg }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-zinc-950/70 via-zinc-950/90 to-zinc-950"></div>

            <div class="max-w-3xl mx-auto relative z-10 space-y-4">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-500/10 border border-amber-500/40 text-amber-400 text-[10px] sm:text-xs font-mono tracking-widest uppercase shadow-[0_0_20px_rgba(251,191,36,0.15)]">
                    <span>{{ $tagline }}</span>
                </div>

                <h1 class="text-3xl sm:text-5xl font-extrabold text-amber-100 tracking-wide uppercase font-serif leading-tight">
                    {{ $tenant->hero_title ?: $tenant->name }}
                </h1>

                <div class="w-24 h-0.5 bg-gradient-to-r from-transparent via-amber-400 to-transparent mx-auto"></div>

                <p class="text-xs sm:text-sm text-zinc-300 max-w-xl mx-auto leading-relaxed font-sans">
                    {{ $tenant->hero_subtitle ?: ($tenant->description ?? 'Nikmati kemewahan potong rambut klasik 1920-an, cukur klimis pisau lipat steril, dan perawatan jenggot berkualitas tinggi.') }}
                </p>

                <div class="text-[11px] text-amber-300/80 font-mono tracking-wider pt-2 flex items-center justify-center gap-2">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $tenant->address ?? 'Alamat Outlet Barbershop' }}</span>
                    </span>
                </div>
            </div>
        </section>

        <!-- Main Body Content -->
        <section class="py-12 max-w-5xl mx-auto px-4 sm:px-6 space-y-12">
            @if($booking_success)
                <div class="p-8 bg-zinc-900 border-2 border-amber-400 text-center space-y-4 max-w-md mx-auto shadow-[0_0_30px_rgba(251,191,36,0.2)]">
                    <div class="text-xl font-bold text-amber-400 uppercase tracking-widest font-serif">RESERVASI BERHASIL</div>
                    <div class="text-xs text-zinc-300 font-sans">Kode Booking Anda: <br><strong class="font-mono text-amber-300 bg-amber-500/20 px-4 py-1.5 border border-amber-400/50 inline-block my-2 text-sm">{{ $created_reservation_code }}</strong></div>
                    <p class="text-xs text-zinc-400 font-sans">Tim concierge barbershop kami akan menghubungi WhatsApp Anda.</p>
                    <button wire:click="$set('booking_success', false)" class="w-full py-3.5 bg-amber-400 hover:bg-amber-300 text-zinc-950 font-bold text-xs uppercase tracking-widest transition shadow-lg">Buat Reservasi Baru</button>
                </div>
            @else
                <div class="space-y-12">
                    @foreach($sectionOrder as $secKey)
                        
                        <!-- SECTION: SERVICES -->
                        @if($secKey === 'services' && $showServices)
                            <div class="p-6 sm:p-8 bg-zinc-900/90 border border-amber-500/30 space-y-6 shadow-xl relative">
                                <div class="flex items-center justify-between border-b border-amber-500/20 pb-3">
                                    <h3 class="text-base sm:text-lg font-bold text-amber-400 uppercase tracking-widest font-serif">KATALOG LAYANAN SPEAKEASY</h3>
                                    <span class="text-[10px] font-mono text-amber-300/60 uppercase">Tarif Cukur Klasik</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse($services as $srv)
                                        <div class="p-4 bg-zinc-950 border border-amber-500/20 hover:border-amber-400/50 transition-all space-y-2 flex flex-col justify-between group">
                                            <div class="space-y-1 font-sans">
                                                <div class="font-bold text-xs text-amber-200 group-hover:text-amber-400 transition">{{ $srv->name }}</div>
                                                <p class="text-[11px] text-zinc-400 leading-relaxed">{{ $srv->description }}</p>
                                            </div>
                                            <div class="flex items-center justify-between pt-2 border-t border-zinc-900 font-mono text-xs">
                                                <span class="text-[10px] text-zinc-500">{{ $srv->duration_minutes }} Mnt</span>
                                                <span class="font-bold text-amber-400 bg-amber-500/10 border border-amber-500/30 px-3 py-1 whitespace-nowrap flex-shrink-0">Rp {{ number_format($srv->price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-zinc-500 py-6 text-center col-span-full font-sans">Belum ada layanan yang ditambahkan.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <!-- SECTION: BOOKING FORM -->
                        @if($secKey === 'booking')
                            <div class="p-6 sm:p-8 bg-zinc-900/90 border border-amber-500/30 space-y-6 shadow-xl font-sans">
                                <div class="border-b border-amber-500/20 pb-3">
                                    <h2 class="text-base sm:text-lg font-bold text-amber-400 uppercase tracking-widest font-serif">RESERVASI JADWAL SPEAKEASY</h2>
                                    <p class="text-xs text-zinc-400 mt-1">Pilih jadwal kedatangan & paket grooming khusus Anda.</p>
                                </div>

                                <form wire:submit.prevent="createBooking" class="space-y-4 text-xs">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-300 uppercase tracking-wider mb-1.5">Nama Tamu</label>
                                            <input type="text" wire:model="customer_name" required placeholder="Doni Setiawan" class="w-full bg-zinc-950 border border-amber-500/30 px-4 py-3 text-xs text-amber-100 placeholder:text-zinc-600 focus:border-amber-400 focus:outline-none transition" />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-300 uppercase tracking-wider mb-1.5">Nomor WhatsApp</label>
                                            <input type="text" wire:model="customer_phone" required placeholder="081234567890" class="w-full bg-zinc-950 border border-amber-500/30 px-4 py-3 text-xs text-amber-100 placeholder:text-zinc-600 focus:border-amber-400 focus:outline-none transition" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-300 uppercase tracking-wider mb-1.5">Paket Layanan</label>
                                            <select wire:model="service_id" required class="w-full bg-zinc-950 border border-amber-500/30 px-4 py-3 text-xs font-semibold text-amber-100 focus:border-amber-400 focus:outline-none transition">
                                                <option value="">-- Pilih Paket Layanan --</option>
                                                @foreach($services as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price, 0, ',', '.') }} ({{ $s->duration_minutes }} Mnt)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-300 uppercase tracking-wider mb-1.5">Master Barber</label>
                                            <select wire:model="barber_user_id" class="w-full bg-zinc-950 border border-amber-500/30 px-4 py-3 text-xs font-semibold text-amber-100 focus:border-amber-400 focus:outline-none transition">
                                                <option value="">-- Bebas (Ready Barber) --</option>
                                                @foreach($barbers as $b)
                                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-300 uppercase tracking-wider mb-1.5">Tanggal Kedatangan</label>
                                            <input type="date" wire:model="reservation_date" required class="w-full bg-zinc-950 border border-amber-500/30 px-4 py-3 text-xs text-amber-100 focus:border-amber-400 focus:outline-none transition" />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-mono text-amber-300 uppercase tracking-wider mb-1.5">Jam Mulai</label>
                                            <input type="time" wire:model="start_time" required class="w-full bg-zinc-950 border border-amber-500/30 px-4 py-3 text-xs text-amber-100 focus:border-amber-400 focus:outline-none transition" />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-mono text-amber-300 uppercase tracking-wider mb-1.5">Catatan Khusus</label>
                                        <textarea wire:model="notes" rows="2" placeholder="Catatan gaya potongan atau kebutuhan khusus..." class="w-full bg-zinc-950 border border-amber-500/30 px-4 py-3 text-xs text-amber-100 placeholder:text-zinc-600 focus:border-amber-400 focus:outline-none transition"></textarea>
                                    </div>

                                    <button type="submit" class="w-full py-4 bg-amber-400 hover:bg-amber-300 text-zinc-950 font-bold text-xs uppercase tracking-widest transition">
                                        KIRIM RESERVASI GENTLEMAN
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- SECTION: PRODUCTS -->
                        @if($secKey === 'products' && $showProducts && $products->count() > 0)
                            <div class="p-6 sm:p-8 bg-zinc-900/90 border border-amber-500/30 space-y-6 shadow-xl">
                                <div class="border-b border-amber-500/20 pb-3">
                                    <h3 class="text-base sm:text-lg font-bold text-amber-400 uppercase tracking-widest font-serif">GROOMING & POMADE COLLECTION</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 font-sans">
                                    @foreach($products as $prd)
                                        <div class="p-4 bg-zinc-950 border border-amber-500/20 flex justify-between items-center">
                                            <div>
                                                <div class="font-bold text-xs text-amber-200">{{ $prd->name }}</div>
                                                <div class="text-[10px] text-zinc-400 mt-0.5">{{ $prd->category }}</div>
                                            </div>
                                            <span class="font-mono font-bold text-xs text-amber-400 bg-amber-500/10 border border-amber-500/30 px-3 py-1 whitespace-nowrap flex-shrink-0">Rp {{ number_format($prd->price, 0, ',', '.') }}</span>
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

    <footer class="bg-zinc-950 border-t border-amber-500/20 py-8 text-amber-300/60 text-xs text-center font-sans">
        <div class="max-w-4xl mx-auto px-4 space-y-1">
            <div class="font-bold text-amber-400 uppercase tracking-wider font-serif">{{ $tenant->name }}</div>
            <div class="text-[11px] text-zinc-400">{{ $footerText }}</div>
            <div class="text-[10px] text-zinc-500 pt-2">{{ $footerCopyright }}</div>
        </div>
    </footer>
</div>
