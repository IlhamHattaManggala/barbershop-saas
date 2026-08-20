@php
    $shopLogo = $tenant->logo ? asset($tenant->logo) : asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
    $heroBg = $tenant->hero_banner ? asset($tenant->hero_banner) : asset('images/hero_barbershop_bg.jpg');
    $tagline = $tenant->hero_tagline ?? '#URBAN #STREETWEAR #FRESHFADE';
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

<div class="min-h-screen flex flex-col justify-between bg-black text-white font-sans selection:bg-lime-400 selection:text-black w-full overflow-x-hidden relative">
    
    <!-- Neon Lime Industrial Top Bar -->
    <header class="bg-zinc-950 border-b-2 border-lime-400 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-18 flex items-center justify-between gap-4">
            <a href="{{ url('/' . $tenant->slug) }}" class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 border-2 border-lime-400 bg-black p-0.5 shadow-[0_0_15px_rgba(163,230,53,0.4)] flex-shrink-0">
                    <img src="{{ $shopLogo }}" alt="{{ $tenant->name }}" class="w-full h-full object-contain" />
                </div>
                <div class="min-w-0">
                    <div class="font-black italic text-base sm:text-xl text-lime-400 tracking-tighter uppercase truncate">{{ $tenant->name }}</div>
                    <div class="text-[10px] text-cyan-400 font-mono tracking-widest uppercase">URBAN STREETWEAR STUDIO</div>
                </div>
            </a>
            <div class="hidden sm:flex items-center gap-2">
                <span class="px-3 py-1 bg-lime-400 text-black font-black text-[10px] uppercase tracking-widest shadow-[0_0_10px_rgba(163,230,53,0.5)]">
                    OPEN NOW
                </span>
            </div>
        </div>
    </header>

    <main class="flex-1 bg-black w-full">
        <!-- Hero Section: Streetwear Graffiti Slanted Banner -->
        <section class="relative py-16 sm:py-24 bg-black border-b-2 border-zinc-800 text-center overflow-hidden px-4">
            <div class="absolute inset-0 bg-cover bg-center opacity-45 mix-blend-luminosity" style="background-image: url('{{ $heroBg }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/80 to-black"></div>

            <div class="max-w-4xl mx-auto relative z-10 space-y-5">
                <div class="inline-block transform -rotate-1">
                    <span class="px-4 py-1.5 bg-lime-400 text-black text-xs sm:text-sm font-black font-mono tracking-widest uppercase shadow-[4px_4px_0px_#000]">
                        {{ $tagline }}
                    </span>
                </div>

                <h1 class="text-4xl sm:text-6xl md:text-7xl font-black italic uppercase tracking-tighter text-white leading-none drop-shadow-[0_4px_10px_rgba(0,0,0,0.8)]">
                    {{ $tenant->hero_title ?: $tenant->name }}
                </h1>

                <p class="text-xs sm:text-sm text-zinc-300 max-w-xl mx-auto leading-relaxed font-mono uppercase tracking-wide">
                    {{ $tenant->hero_subtitle ?: ($tenant->description ?? 'Gaya potong rambut modern berani dengan karakter urban streetwear dan vibe industrial.') }}
                </p>

                <div class="pt-2 text-xs font-mono text-cyan-400 flex items-center justify-center gap-2">
                    <span class="px-3 py-1 bg-zinc-900 border border-cyan-400/50 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $tenant->address ?? 'Alamat Outlet Barbershop' }}</span>
                    </span>
                </div>
            </div>
        </section>

        <!-- Main Content Brutalist Grid -->
        <section class="py-12 max-w-6xl mx-auto px-4 sm:px-6 space-y-12">
            @if($booking_success)
                <div class="p-8 bg-zinc-950 border-2 border-lime-400 text-center space-y-4 max-w-md mx-auto shadow-[8px_8px_0px_#a3e635]">
                    <div class="text-2xl font-black italic text-lime-400 uppercase tracking-tighter">BOOKING SUCCESS</div>
                    <div class="text-xs text-zinc-300 font-mono">CODE: <br><strong class="text-base text-black bg-lime-400 px-4 py-1.5 inline-block my-2 font-black shadow-[3px_3px_0px_#fff]">{{ $created_reservation_code }}</strong></div>
                    <p class="text-xs text-zinc-400 font-mono">Tim barber kami akan langsung WhatsApp kamu!</p>
                    <button wire:click="$set('booking_success', false)" class="w-full py-3.5 bg-lime-400 hover:bg-lime-300 text-black font-black text-xs uppercase tracking-widest shadow-[4px_4px_0px_#fff]">Buat Reservasi Baru</button>
                </div>
            @else
                <div class="space-y-12">
                    @foreach($sectionOrder as $secKey)
                        
                        <!-- SECTION: SERVICES -->
                        @if($secKey === 'services' && $showServices)
                            <div class="p-6 sm:p-8 bg-zinc-950 border-2 border-zinc-800 space-y-6 shadow-[6px_6px_0px_#27272a]">
                                <div class="flex items-center justify-between border-b-2 border-lime-400 pb-3">
                                    <h3 class="text-base sm:text-xl font-black italic text-lime-400 uppercase tracking-tighter">#01 / KATALOG HAIR CUT & STYLE</h3>
                                    <span class="text-xs font-mono text-cyan-400 font-bold uppercase">URBAN MENU</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse($services as $srv)
                                        <div class="p-4 bg-black border-2 border-zinc-800 hover:border-lime-400 transition-all space-y-3 flex flex-col justify-between group">
                                            <div class="space-y-1">
                                                <div class="font-black italic text-sm text-white group-hover:text-lime-400 uppercase tracking-tight">{{ $srv->name }}</div>
                                                <p class="text-xs text-zinc-400 font-mono leading-relaxed">{{ $srv->description }}</p>
                                            </div>
                                            <div class="flex items-center justify-between pt-2 border-t border-zinc-900 font-mono text-xs">
                                                <span class="text-[10px] text-zinc-500">{{ $srv->duration_minutes }} Mins</span>
                                                <span class="font-black text-black bg-lime-400 px-3 py-1 shadow-[2px_2px_0px_#000] whitespace-nowrap flex-shrink-0">Rp {{ number_format($srv->price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-zinc-500 py-6 text-center col-span-full font-mono">Belum ada layanan yang ditambahkan.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <!-- SECTION: BOOKING FORM -->
                        @if($secKey === 'booking')
                            <div class="p-6 sm:p-8 bg-zinc-950 border-2 border-zinc-800 space-y-6 shadow-[6px_6px_0px_#27272a]">
                                <div class="border-b-2 border-lime-400 pb-3">
                                    <h2 class="text-base sm:text-xl font-black italic text-lime-400 uppercase tracking-tighter">#02 / FORMULIR BOOKING STREETWEAR</h2>
                                    <p class="text-xs text-zinc-400 font-mono mt-1">Kunci slot pangkas favoritmu sekarang.</p>
                                </div>

                                <form wire:submit.prevent="createBooking" class="space-y-4 text-xs font-mono">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] text-lime-400 font-bold uppercase tracking-wider mb-1.5">Nama Customer</label>
                                            <input type="text" wire:model="customer_name" required placeholder="Doni Setiawan" class="w-full bg-black border-2 border-zinc-800 px-4 py-3 text-xs text-white placeholder:text-zinc-600 focus:border-lime-400 focus:outline-none transition" />
                                        </div>
                                        <div>
                                            <label class="block text-[11px] text-lime-400 font-bold uppercase tracking-wider mb-1.5">WhatsApp Active</label>
                                            <input type="text" wire:model="customer_phone" required placeholder="081234567890" class="w-full bg-black border-2 border-zinc-800 px-4 py-3 text-xs text-white placeholder:text-zinc-600 focus:border-lime-400 focus:outline-none transition" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] text-lime-400 font-bold uppercase tracking-wider mb-1.5">Pilih Service</label>
                                            <select wire:model="service_id" required class="w-full bg-black border-2 border-zinc-800 px-4 py-3 text-xs font-bold text-white focus:border-lime-400 focus:outline-none transition">
                                                <option value="">-- Select Service --</option>
                                                @foreach($services as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price, 0, ',', '.') }} ({{ $s->duration_minutes }} Mnt)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] text-lime-400 font-bold uppercase tracking-wider mb-1.5">Barber Specialist</label>
                                            <select wire:model="barber_user_id" class="w-full bg-black border-2 border-zinc-800 px-4 py-3 text-xs font-bold text-white focus:border-lime-400 focus:outline-none transition">
                                                <option value="">-- Any Ready Barber --</option>
                                                @foreach($barbers as $b)
                                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] text-lime-400 font-bold uppercase tracking-wider mb-1.5">Tanggal Reservasi</label>
                                        <input type="date" wire:model.live="reservation_date" required class="w-full bg-black border-2 border-zinc-800 px-4 py-3 text-xs text-white focus:border-lime-400 focus:outline-none transition font-mono" />
                                    </div>

                                    <div class="space-y-1.5 font-mono">
                                        <label class="block text-[11px] text-lime-400 font-bold uppercase tracking-wider">Slot Jam Cukur (Live Available Slots)</label>
                                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 max-h-48 overflow-y-auto p-1 border-2 border-zinc-800 bg-black">
                                            @forelse($available_slots as $slot)
                                                <button 
                                                    type="button" 
                                                    wire:click="selectSlot('{{ $slot['time'] }}')" 
                                                    disabled="{{ !$slot['available'] }}"
                                                    class="py-2.5 px-2 text-xs font-black uppercase transition-all border-2 flex flex-col items-center justify-center cursor-pointer disabled:cursor-not-allowed {{ $start_time === $slot['time'] ? 'bg-lime-400 text-black border-lime-400 shadow-[3px_3px_0px_#fff]' : ($slot['available'] ? 'bg-zinc-900 border-zinc-800 text-white hover:border-lime-400' : 'bg-black border-zinc-900 text-zinc-700 line-through opacity-50') }}"
                                                    title="{{ $slot['reason'] }}"
                                                >
                                                    <span>{{ $slot['time'] }}</span>
                                                    <span class="text-[9px] font-normal {{ $start_time === $slot['time'] ? 'text-black' : ($slot['available'] ? 'text-lime-400' : 'text-zinc-700') }}">{{ $slot['available'] ? 'Tersedia' : $slot['reason'] }}</span>
                                                </button>
                                            @empty
                                                <div class="col-span-full text-center text-xs text-zinc-600 py-3">
                                                    Tidak ada slot waktu tersedia.
                                                </div>
                                            @endforelse
                                        </div>
                                        @error('start_time')
                                            <span class="text-[11px] font-semibold text-rose-500 mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-[11px] text-lime-400 font-bold uppercase tracking-wider mb-1.5">Request Style / Notes</label>
                                        <textarea wire:model="notes" rows="2" placeholder="Skin Fade, Low Taper, Cuci ekstra..." class="w-full bg-black border-2 border-zinc-800 px-4 py-3 text-xs text-white placeholder:text-zinc-600 focus:border-lime-400 focus:outline-none transition"></textarea>
                                    </div>

                                    <button type="submit" class="w-full py-4 bg-lime-400 hover:bg-lime-300 text-black font-black text-sm uppercase tracking-widest shadow-[4px_4px_0px_#fff] transition flex items-center justify-center gap-2">
                                        <span>SUBMIT BOOKING NOW</span>
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- SECTION: PRODUCTS -->
                        @if($secKey === 'products' && $showProducts && $products->count() > 0)
                            <div class="p-6 sm:p-8 bg-zinc-950 border-2 border-zinc-800 space-y-6 shadow-[6px_6px_0px_#27272a]">
                                <div class="border-b-2 border-lime-400 pb-3">
                                    <h3 class="text-base sm:text-xl font-black italic text-lime-400 uppercase tracking-tighter">#03 / STREETWEAR PRODUCTS</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 font-mono">
                                    @foreach($products as $prd)
                                        <div class="p-4 bg-black border-2 border-zinc-800 flex justify-between items-center">
                                            <div>
                                                <div class="font-bold text-xs text-white uppercase">{{ $prd->name }}</div>
                                                <div class="text-[10px] text-cyan-400 mt-0.5">{{ $prd->category }}</div>
                                            </div>
                                            <span class="font-black text-black bg-lime-400 px-3 py-1 shadow-[2px_2px_0px_#000] whitespace-nowrap flex-shrink-0">Rp {{ number_format($prd->price, 0, ',', '.') }}</span>
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

    <footer class="bg-zinc-950 border-t-2 border-zinc-800 py-8 text-zinc-400 text-xs text-center font-mono">
        <div class="max-w-4xl mx-auto px-4 space-y-1">
            <div class="font-black text-lime-400 uppercase tracking-widest text-sm">{{ $tenant->name }}</div>
            <div class="text-[11px] text-zinc-500">{{ $footerText }}</div>
            <div class="text-[10px] text-zinc-600 pt-2">{{ $footerCopyright }}</div>
        </div>
    </footer>
</div>
