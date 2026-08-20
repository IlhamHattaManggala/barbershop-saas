@php
    $shopLogo = $tenant->logo ? asset($tenant->logo) : asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
    $heroBg = $tenant->hero_banner ? asset($tenant->hero_banner) : asset('images/themes/japanese_barbershop_sakura_bg.jpg');
    $cornerSakura = asset('images/themes/sakura_corner_pattern.png');
    $tagline = $tenant->hero_tagline ?? '東京理髪 • STUDIO PANGKAS JEPANG SAKURA & PRESI WABI-SABI';
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

<div class="min-h-screen flex flex-col justify-between bg-[#FFFDF9] text-zinc-900 font-sans selection:bg-rose-600 selection:text-white w-full overflow-x-hidden relative">
    
    <!-- JAPANESE KANJI WATERMARK BACKGROUND -->
    <div class="fixed top-24 right-4 text-[120px] sm:text-[180px] font-black text-rose-500/5 select-none pointer-events-none z-0 leading-none">
        東京
    </div>
    <div class="fixed bottom-24 left-4 text-[120px] sm:text-[180px] font-black text-rose-500/5 select-none pointer-events-none z-0 leading-none">
        理髪
    </div>

    <!-- Header Navigation (Japanese Hinomaru Red Sun & Shoji Screen Aesthetics) -->
    <header class="bg-[#FFFDF9]/95 border-b-2 border-rose-600/30 backdrop-blur-md sticky top-0 z-40 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between gap-4">
            <a href="{{ url('/' . $tenant->slug) }}" class="flex items-center gap-3.5 min-w-0">
                <!-- Red Japanese Sun Emblem (Hinomaru Seal) -->
                <div class="w-11 h-11 rounded-full bg-rose-600 text-white flex items-center justify-center font-black text-base shadow-md border-2 border-white flex-shrink-0 relative overflow-hidden">
                    <span class="relative z-10">印</span>
                </div>
                <div class="min-w-0">
                    <div class="font-black text-base sm:text-xl text-zinc-950 tracking-tight truncate">{{ $tenant->name }}</div>
                    <div class="text-[10px] text-rose-600 font-bold tracking-widest uppercase">東京理髪店 &bull; STUDIO SAKURA JEPANG</div>
                </div>
            </a>
            
            <div class="hidden sm:flex items-center gap-2">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-rose-50 border border-rose-200 rounded-full text-xs font-bold text-rose-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-pulse"></span>
                    <span>営業中 • Studio Buka Hari Ini</span>
                </span>
            </div>
        </div>
    </header>

    <main class="flex-1 w-full relative z-10">
        
        <!-- Hero Section: High-End Sakura & Mount Fuji Japanese Studio Banner -->
        <section class="relative py-16 sm:py-24 border-b-2 border-rose-200 text-center px-4 bg-zinc-950 text-white overflow-hidden shadow-2xl">
            
            <!-- High-Resolution Japanese Sakura Artwork Background -->
            <div class="absolute inset-0 bg-cover bg-center opacity-65 mix-blend-luminosity scale-105" style="background-image: url('{{ $heroBg }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/70 to-zinc-950/40"></div>

            <div class="max-w-4xl mx-auto relative z-10 space-y-5">
                
                <!-- Torii Gate Japanese Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-rose-600 text-white text-xs font-black rounded-full shadow-lg border border-white/20 tracking-wider">
                    <svg class="w-4 h-4 text-white fill-current" viewBox="0 0 24 24">
                        <path d="M2 4h20v2H2V4zm2 3h16v2H4V7zm2 3h2v10H6V10zm10 0h2v10h-2V10z"/>
                    </svg>
                    <span>{{ $tagline }}</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight text-white uppercase leading-tight drop-shadow-[0_4px_20px_rgba(0,0,0,0.9)]">
                    {{ $tenant->hero_title ?: $tenant->name }}
                </h1>

                <!-- Red Accent Japanese Line -->
                <div class="flex items-center justify-center gap-3">
                    <div class="w-16 h-0.5 bg-rose-600"></div>
                    <div class="w-3 h-3 rounded-full bg-rose-600 border-2 border-white"></div>
                    <div class="w-16 h-0.5 bg-rose-600"></div>
                </div>

                <p class="text-xs sm:text-base text-rose-100 max-w-xl mx-auto leading-relaxed font-sans font-medium drop-shadow-md">
                    {{ $tenant->hero_subtitle ?: ($tenant->description ?? 'Sensasi potong rambut presisi ala studio Jepang dalam suasana tenang yang dihiasi pemandangan indahnya bunga Sakura.') }}
                </p>

                <div class="pt-3 flex flex-wrap items-center justify-center gap-3 text-xs font-bold text-white">
                    <span class="px-4 py-2 bg-zinc-900/90 border border-rose-500/50 rounded-xl flex items-center gap-2 backdrop-blur-md shadow-md">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="p-8 sm:p-10 rounded-3xl bg-white border-2 border-rose-600 text-center space-y-4 max-w-md mx-auto shadow-2xl relative overflow-hidden">
                    <!-- EXACT SAKURA CORNER PATTERNS (TOP LEFT & TOP RIGHT) -->
                    <img src="{{ $cornerSakura }}" alt="Sakura Corner" class="absolute top-0 left-0 w-24 h-24 pointer-events-none opacity-90 z-10 select-none" />
                    <img src="{{ $cornerSakura }}" alt="Sakura Corner" class="absolute top-0 right-0 w-24 h-24 transform scale-x-[-1] pointer-events-none opacity-90 z-10 select-none" />

                    <div class="w-16 h-16 rounded-full bg-rose-600 text-white flex items-center justify-center text-3xl font-black mx-auto shadow-lg relative z-20">
                        ✓
                    </div>
                    <div class="space-y-1 relative z-20">
                        <div class="text-xs font-mono font-bold text-rose-600 tracking-widest uppercase">予約完了 &bull; RESERVASI BERHASIL</div>
                        <h3 class="font-black text-2xl text-zinc-950">Reservasi Berhasil Buat!</h3>
                        <p class="text-xs text-zinc-500">Kode Booking Anda: <strong class="font-mono text-rose-700 bg-rose-50 px-3.5 py-1.5 rounded-xl border border-rose-200 inline-block my-1 text-sm font-black">{{ $created_reservation_code }}</strong></p>
                    </div>
                    <p class="text-xs text-zinc-600 leading-relaxed relative z-20">
                        Terima kasih telah melakukan pemesanan di <strong>{{ $tenant->name }}</strong>. Tim studio kami akan segera mengonfirmasi jadwal via WhatsApp.
                    </p>
                    <button wire:click="$set('booking_success', false)" class="w-full py-4 bg-rose-600 hover:bg-rose-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow-lg transition relative z-20">
                        Buat Reservasi Baru
                    </button>
                </div>
            @else
                <div class="space-y-14">
                    @foreach($sectionOrder as $secKey)
                        
                        <!-- SECTION: SERVICES -->
                        @if($secKey === 'services' && $showServices)
                            <div class="p-6 sm:p-8 rounded-3xl bg-white border border-rose-200 space-y-6 shadow-sm relative overflow-hidden">
                                
                                <!-- EXACT SAKURA CORNER PATTERNS (TOP LEFT & TOP RIGHT OF SERVICE CONTAINER) -->
                                <img src="{{ $cornerSakura }}" alt="Sakura Corner" class="absolute top-0 left-0 w-28 h-28 sm:w-40 sm:h-40 pointer-events-none opacity-85 z-10 select-none" />
                                <img src="{{ $cornerSakura }}" alt="Sakura Corner" class="absolute top-0 right-0 w-28 h-28 sm:w-40 sm:h-40 transform scale-x-[-1] pointer-events-none opacity-85 z-10 select-none" />

                                <div class="flex items-center justify-between border-b-2 border-rose-600/30 pb-4 relative z-20">
                                    <div>
                                        <div class="text-[11px] font-mono font-bold text-rose-600 uppercase tracking-widest">サービスメニュー</div>
                                        <h3 class="text-lg sm:text-2xl font-black text-zinc-950">Katalog Layanan & Tarif Pangkas</h3>
                                        <p class="text-xs text-zinc-500 mt-0.5">Pilihan gaya potongan rambut presisi dan perawatan pria ala Jepang.</p>
                                    </div>
                                    <span class="text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-full hidden sm:block">Katalog Resmi</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 relative z-20">
                                    @forelse($services as $srv)
                                        <div class="p-5 rounded-2xl bg-[#FFFDF9] border-2 border-rose-100 hover:border-rose-600 transition-all shadow-xs hover:shadow-xl flex flex-col justify-between space-y-4 group relative overflow-hidden">
                                            
                                            <!-- EXACT SAKURA CORNER PATTERNS (TOP LEFT & TOP RIGHT OF INDIVIDUAL CARD) -->
                                            <img src="{{ $cornerSakura }}" alt="Sakura Corner" class="absolute top-0 left-0 w-16 h-16 pointer-events-none opacity-80 z-10 select-none" />
                                            <img src="{{ $cornerSakura }}" alt="Sakura Corner" class="absolute top-0 right-0 w-16 h-16 transform scale-x-[-1] pointer-events-none opacity-80 z-10 select-none" />

                                            <div class="space-y-2 pt-3 relative z-20">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span class="font-extrabold text-sm text-zinc-950 group-hover:text-rose-600 transition">{{ $srv->name }}</span>
                                                    <span class="text-[10px] font-bold text-zinc-500 bg-rose-50 px-2 py-0.5 rounded-md flex-shrink-0">{{ $srv->duration_minutes }} Mnt</span>
                                                </div>
                                                <p class="text-xs text-zinc-500 leading-relaxed font-sans">{{ $srv->description }}</p>
                                            </div>

                                            <div class="pt-3 border-t border-rose-100 flex items-center justify-between relative z-20">
                                                <span class="font-black text-xs text-white bg-rose-600 px-3 py-1.5 rounded-lg shadow-xs whitespace-nowrap flex-shrink-0">Rp {{ number_format($srv->price, 0, ',', '.') }}</span>
                                                <button wire:click="$set('service_id', '{{ $srv->id }}')" class="text-[11px] font-bold text-zinc-900 bg-stone-100 hover:bg-zinc-950 hover:text-white px-3 py-1.5 rounded-lg transition">
                                                    Pilih Paket
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-zinc-400 py-6 text-center col-span-full font-mono">Belum ada layanan yang ditambahkan.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <!-- SECTION: BOOKING FORM -->
                        @if($secKey === 'booking')
                            <div class="p-6 sm:p-8 rounded-3xl bg-white border border-rose-200 space-y-6 shadow-sm relative overflow-hidden">
                                
                                <!-- EXACT SAKURA CORNER PATTERNS (TOP LEFT & TOP RIGHT OF BOOKING CARD) -->
                                <img src="{{ $cornerSakura }}" alt="Sakura Corner" class="absolute top-0 left-0 w-28 h-28 sm:w-40 sm:h-40 pointer-events-none opacity-85 z-10 select-none" />
                                <img src="{{ $cornerSakura }}" alt="Sakura Corner" class="absolute top-0 right-0 w-28 h-28 sm:w-40 sm:h-40 transform scale-x-[-1] pointer-events-none opacity-85 z-10 select-none" />

                                <div class="border-b-2 border-rose-600/30 pb-4 pt-2 relative z-20">
                                    <div class="text-[11px] font-mono font-bold text-rose-600 uppercase tracking-widest">オンライン予約</div>
                                    <h2 class="text-lg sm:text-2xl font-black text-zinc-950">Formulir Reservasi Online Studio</h2>
                                    <p class="text-xs text-zinc-500 mt-0.5">Isi data di bawah ini untuk memilih jadwal waktu pangkas favorit Anda.</p>
                                </div>

                                <form wire:submit.prevent="createBooking" class="space-y-4 text-xs relative z-20">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-zinc-800 mb-1.5">Nama Lengkap Customer</label>
                                            <input type="text" wire:model="customer_name" required placeholder="Doni Setiawan" class="w-full bg-[#FFFDF9] border border-rose-200 rounded-xl px-4 py-3 text-xs text-zinc-950 placeholder:text-zinc-400 focus:bg-white focus:border-rose-600 focus:outline-none transition font-medium" />
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-zinc-800 mb-1.5">Nomor WhatsApp Active</label>
                                            <input type="text" wire:model="customer_phone" required placeholder="081234567890" class="w-full bg-[#FFFDF9] border border-rose-200 rounded-xl px-4 py-3 text-xs text-zinc-950 placeholder:text-zinc-400 focus:bg-white focus:border-rose-600 focus:outline-none transition font-medium" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-zinc-800 mb-1.5">Pilih Paket Layanan</label>
                                            <select wire:model="service_id" required class="w-full bg-[#FFFDF9] border border-rose-200 rounded-xl px-4 py-3 text-xs font-bold text-zinc-950 focus:bg-white focus:border-rose-600 focus:outline-none transition">
                                                <option value="">-- Pilih Paket Layanan --</option>
                                                @foreach($services as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price, 0, ',', '.') }} ({{ $s->duration_minutes }} Mnt)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-zinc-800 mb-1.5">Barber Specialist</label>
                                            <select wire:model="barber_user_id" class="w-full bg-[#FFFDF9] border border-rose-200 rounded-xl px-4 py-3 text-xs font-bold text-zinc-950 focus:bg-white focus:border-rose-600 focus:outline-none transition">
                                                <option value="">-- Bebas (Ready Barber) --</option>
                                                @foreach($barbers as $b)
                                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-zinc-800 mb-1.5">Tanggal Kedatangan</label>
                                            <input type="date" wire:model="reservation_date" required class="w-full bg-[#FFFDF9] border border-rose-200 rounded-xl px-4 py-3 text-xs text-zinc-950 focus:bg-white focus:border-rose-600 focus:outline-none transition font-medium" />
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-zinc-800 mb-1.5">Jam Mulai</label>
                                            <input type="time" wire:model="start_time" required class="w-full bg-[#FFFDF9] border border-rose-200 rounded-xl px-4 py-3 text-xs text-zinc-950 focus:bg-white focus:border-rose-600 focus:outline-none transition font-medium" />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-zinc-800 mb-1.5">Catatan Khusus Model Rambut</label>
                                        <textarea wire:model="notes" rows="2" placeholder="Contoh: Japanese Crop Fade, Taper Fade..." class="w-full bg-[#FFFDF9] border border-rose-200 rounded-xl px-4 py-3 text-xs text-zinc-950 placeholder:text-zinc-400 focus:bg-white focus:border-rose-600 focus:outline-none transition font-medium"></textarea>
                                    </div>

                                    <button type="submit" class="w-full py-4 bg-rose-600 hover:bg-rose-700 text-white font-black text-xs rounded-xl shadow-lg transition uppercase tracking-widest">
                                        Kirim Reservasi Sekarang
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- SECTION: PRODUCTS -->
                        @if($secKey === 'products' && $showProducts && $products->count() > 0)
                            <div class="p-6 sm:p-8 rounded-3xl bg-white border border-rose-200 space-y-6 shadow-sm relative overflow-hidden">
                                
                                <!-- EXACT SAKURA CORNER PATTERNS (TOP LEFT & TOP RIGHT OF PRODUCTS CONTAINER) -->
                                <img src="{{ $cornerSakura }}" alt="Sakura Corner" class="absolute top-0 left-0 w-24 h-24 pointer-events-none opacity-85 z-10 select-none" />
                                <img src="{{ $cornerSakura }}" alt="Sakura Corner" class="absolute top-0 right-0 w-24 h-24 transform scale-x-[-1] pointer-events-none opacity-85 z-10 select-none" />

                                <div class="border-b-2 border-rose-600/30 pb-4 relative z-20">
                                    <div class="text-[11px] font-mono font-bold text-rose-600 uppercase tracking-widest">ヘアケア製品</div>
                                    <h3 class="text-lg sm:text-2xl font-black text-zinc-950">Produk Grooming & Pomade</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 relative z-20">
                                    @foreach($products as $prd)
                                        <div class="p-5 rounded-2xl bg-[#FFFDF9] border-2 border-rose-100 flex justify-between items-center shadow-xs relative overflow-hidden">
                                            <div>
                                                <div class="font-extrabold text-xs text-zinc-950">{{ $prd->name }}</div>
                                                <div class="text-[10px] text-zinc-400 mt-0.5 font-medium">{{ $prd->category }}</div>
                                            </div>
                                            <span class="font-black text-xs text-white bg-rose-600 px-3 py-1.5 rounded-lg whitespace-nowrap flex-shrink-0">Rp {{ number_format($prd->price, 0, ',', '.') }}</span>
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

    <footer class="bg-zinc-950 border-t-2 border-rose-600 text-zinc-400 py-10 text-xs text-center relative z-10">
        <div class="max-w-4xl mx-auto px-4 space-y-2">
            <div class="font-black text-white text-base tracking-wide">{{ $tenant->name }}</div>
            <div class="text-[11px] text-rose-400 font-mono">東京理髪店 &bull; JAPANESE SAKURA BARBERSHOP STUDIO</div>
            <div class="text-[11px] text-zinc-400 pt-1">{{ $footerText }}</div>
            <div class="text-[10px] text-zinc-600 pt-3 border-t border-zinc-800 max-w-xs mx-auto">{{ $footerCopyright }}</div>
        </div>
    </footer>
</div>
