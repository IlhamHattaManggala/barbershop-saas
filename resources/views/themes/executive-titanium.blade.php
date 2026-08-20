@php
    $shopLogo = $tenant->logo ? asset($tenant->logo) : asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
    $heroBg = $tenant->hero_banner ? asset($tenant->hero_banner) : asset('images/hero_barbershop_bg.jpg');
    $tagline = $tenant->hero_tagline ?? 'EXECUTIVE TITANIUM • VIP LOUNGE & PRIVATE GROOMING';
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

<div class="min-h-screen flex flex-col justify-between bg-stone-950 text-stone-100 font-sans selection:bg-stone-200 selection:text-stone-950 w-full overflow-x-hidden relative">
    
    <!-- Executive Titanium Header -->
    <header class="bg-stone-900/95 border-b border-stone-800 backdrop-blur-md sticky top-0 z-40 shadow-2xl">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between gap-4">
            <a href="{{ url('/' . $tenant->slug) }}" class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl border border-stone-600 bg-stone-950 p-1 shadow-lg flex-shrink-0">
                    <img src="{{ $shopLogo }}" alt="{{ $tenant->name }}" class="w-full h-full object-contain rounded-lg" />
                </div>
                <div class="min-w-0">
                    <div class="font-extrabold text-base sm:text-xl text-stone-100 tracking-tight uppercase font-mono truncate">{{ $tenant->name }}</div>
                    <div class="text-[10px] text-stone-400 font-mono tracking-widest uppercase">EXECUTIVE TITANIUM VIP</div>
                </div>
            </a>
            <div class="hidden sm:block">
                <span class="text-[10px] font-mono text-stone-300 uppercase tracking-widest border border-stone-600 px-3 py-1 bg-stone-800 rounded-lg">
                    PRIVATE LOUNGE
                </span>
            </div>
        </div>
    </header>

    <main class="flex-1 bg-stone-950 w-full">
        <!-- Hero Section -->
        <section class="relative py-16 sm:py-24 bg-stone-950 border-b border-stone-800 text-center overflow-hidden px-4">
            <div class="absolute inset-0 bg-cover bg-center opacity-30 grayscale" style="background-image: url('{{ $heroBg }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-stone-950/70 via-stone-950/90 to-stone-950"></div>

            <div class="max-w-3xl mx-auto relative z-10 space-y-4">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-stone-800/80 border border-stone-700 text-stone-300 text-[10px] sm:text-xs font-mono tracking-widest uppercase shadow-md">
                    <span>{{ $tagline }}</span>
                </div>

                <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white uppercase font-mono leading-tight">
                    {{ $tenant->hero_title ?: $tenant->name }}
                </h1>

                <p class="text-xs sm:text-sm text-stone-400 max-w-xl mx-auto leading-relaxed">
                    {{ $tenant->hero_subtitle ?: ($tenant->description ?? 'Layanan pangkas rambut & grooming eksekutif kelas VIP dengan standar kenyamanan dan privasi tertinggi.') }}
                </p>

                <div class="text-[11px] text-stone-400 font-mono tracking-wider pt-2 flex items-center justify-center gap-2">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $tenant->address ?? 'Alamat Outlet Barbershop' }}</span>
                    </span>
                </div>
            </div>
        </section>

        <!-- Main Body Content -->
        <section class="py-12 max-w-5xl mx-auto px-4 sm:px-6 space-y-12">
            @if($booking_success)
                <div class="p-8 rounded-3xl bg-stone-900 border border-stone-700 text-center space-y-4 max-w-md mx-auto shadow-2xl">
                    <div class="w-12 h-12 rounded-xl bg-stone-800 border border-stone-600 flex items-center justify-center text-stone-200 text-xl font-bold mx-auto">✓</div>
                    <div class="space-y-1">
                        <h3 class="font-extrabold text-xl text-white font-mono uppercase">VIP RESERVATION CONFIRMED</h3>
                        <p class="text-xs text-stone-400">Kode Booking Anda: <br><strong class="font-mono text-white bg-stone-800 px-4 py-1.5 border border-stone-600 inline-block my-2 text-sm rounded-xl">{{ $created_reservation_code }}</strong></p>
                    </div>
                    <p class="text-xs text-stone-400">Concierge VIP barbershop kami akan segera mengonfirmasi jadwal Anda.</p>
                    <button wire:click="$set('booking_success', false)" class="w-full py-3.5 bg-stone-100 hover:bg-white text-stone-950 font-extrabold text-xs uppercase tracking-widest rounded-xl shadow-lg transition">Buat Reservasi Baru</button>
                </div>
            @else
                <div class="space-y-12">
                    @foreach($sectionOrder as $secKey)
                        
                        <!-- SECTION: SERVICES -->
                        @if($secKey === 'services' && $showServices)
                            <div class="p-6 sm:p-8 rounded-3xl bg-stone-900/90 border border-stone-800 space-y-6 shadow-2xl">
                                <div class="flex items-center justify-between border-b border-stone-800 pb-3">
                                    <h3 class="text-base sm:text-lg font-bold text-stone-200 uppercase tracking-widest font-mono">KATALOG EXECUTIVE TITANIUM</h3>
                                    <span class="text-[10px] font-mono text-stone-500 uppercase">VIP MENU</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse($services as $srv)
                                        <div class="p-4 rounded-2xl bg-stone-950 border border-stone-800 hover:border-stone-600 transition-all space-y-2 flex flex-col justify-between group">
                                            <div class="space-y-1">
                                                <div class="font-bold text-xs text-stone-200 group-hover:text-white transition">{{ $srv->name }}</div>
                                                <p class="text-[11px] text-stone-400 leading-relaxed">{{ $srv->description }}</p>
                                            </div>
                                            <div class="flex items-center justify-between pt-2 border-t border-stone-900 font-mono text-xs">
                                                <span class="text-[10px] text-stone-500">{{ $srv->duration_minutes }} Mnt</span>
                                                <span class="font-bold text-stone-100 bg-stone-800 border border-stone-700 px-3 py-1 rounded-xl whitespace-nowrap flex-shrink-0">Rp {{ number_format($srv->price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-stone-500 py-6 text-center col-span-full font-sans">Belum ada layanan yang ditambahkan.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <!-- SECTION: BOOKING FORM -->
                        @if($secKey === 'booking')
                            <div class="p-6 sm:p-8 rounded-3xl bg-stone-900/90 border border-stone-800 space-y-6 shadow-2xl">
                                <div class="border-b border-stone-800 pb-3">
                                    <h2 class="text-base sm:text-lg font-bold text-stone-200 uppercase tracking-widest font-mono">FORMULIR BOOKING VIP LOUNGE</h2>
                                    <p class="text-xs text-stone-400 mt-1">Lengkapi data diri untuk pemesanan slot waktu executive.</p>
                                </div>

                                <form wire:submit.prevent="createBooking" class="space-y-4 text-xs font-sans">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-mono text-stone-400 uppercase tracking-wider mb-1.5">Nama Executive</label>
                                            <input type="text" wire:model="customer_name" required placeholder="Doni Setiawan" class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-xs text-stone-100 placeholder:text-stone-700 focus:border-stone-500 focus:outline-none transition" />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-mono text-stone-400 uppercase tracking-wider mb-1.5">Nomor WhatsApp</label>
                                            <input type="text" wire:model="customer_phone" required placeholder="081234567890" class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-xs text-stone-100 placeholder:text-stone-700 focus:border-stone-500 focus:outline-none transition" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-mono text-stone-400 uppercase tracking-wider mb-1.5">Paket Layanan</label>
                                            <select wire:model="service_id" required class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-xs font-semibold text-stone-100 focus:border-stone-500 focus:outline-none transition">
                                                <option value="">-- Pilih Paket Layanan --</option>
                                                @foreach($services as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price, 0, ',', '.') }} ({{ $s->duration_minutes }} Mnt)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-mono text-stone-400 uppercase tracking-wider mb-1.5">Barber Specialist</label>
                                            <select wire:model="barber_user_id" class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-xs font-semibold text-stone-100 focus:border-stone-500 focus:outline-none transition">
                                                <option value="">-- Bebas (Barber Ready) --</option>
                                                @foreach($barbers as $b)
                                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-mono text-stone-400 uppercase tracking-wider mb-1.5">Tanggal Kedatangan</label>
                                            <input type="date" wire:model="reservation_date" required class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-xs text-stone-100 focus:border-stone-500 focus:outline-none transition" />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-mono text-stone-400 uppercase tracking-wider mb-1.5">Jam Mulai</label>
                                            <input type="time" wire:model="start_time" required class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-xs text-stone-100 focus:border-stone-500 focus:outline-none transition" />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-mono text-stone-400 uppercase tracking-wider mb-1.5">Catatan Khusus</label>
                                        <textarea wire:model="notes" rows="2" placeholder="Catatan potongan rambut atau instruksi khusus..." class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-xs text-stone-100 placeholder:text-stone-700 focus:border-stone-500 focus:outline-none transition"></textarea>
                                    </div>

                                    <button type="submit" class="w-full py-4 bg-stone-100 hover:bg-white text-stone-950 font-black text-xs uppercase tracking-widest rounded-xl shadow-xl transition">
                                        SUBMIT EXECUTIVE RESERVATION
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- SECTION: PRODUCTS -->
                        @if($secKey === 'products' && $showProducts && $products->count() > 0)
                            <div class="p-6 sm:p-8 rounded-3xl bg-stone-900/90 border border-stone-800 space-y-6 shadow-2xl">
                                <div class="border-b border-stone-800 pb-3">
                                    <h3 class="text-base sm:text-lg font-bold text-stone-200 uppercase tracking-widest font-mono">TITANIUM GROOMING PRODUCTS</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 font-sans">
                                    @foreach($products as $prd)
                                        <div class="p-4 rounded-2xl bg-stone-950 border border-stone-800 flex justify-between items-center">
                                            <div>
                                                <div class="font-bold text-xs text-stone-200">{{ $prd->name }}</div>
                                                <div class="text-[10px] text-stone-500 mt-0.5">{{ $prd->category }}</div>
                                            </div>
                                            <span class="font-mono font-bold text-xs text-stone-100 bg-stone-800 border border-stone-700 px-3 py-1 rounded-xl whitespace-nowrap flex-shrink-0">Rp {{ number_format($prd->price, 0, ',', '.') }}</span>
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

    <footer class="bg-stone-950 border-t border-stone-800 py-8 text-stone-500 text-xs text-center font-sans">
        <div class="max-w-4xl mx-auto px-4 space-y-1">
            <div class="font-bold text-stone-200 uppercase tracking-wider font-mono">{{ $tenant->name }}</div>
            <div class="text-[11px] text-stone-500">{{ $footerText }}</div>
            <div class="text-[10px] text-stone-600 pt-2">{{ $footerCopyright }}</div>
        </div>
    </footer>
</div>
