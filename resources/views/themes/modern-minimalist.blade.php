@php
    $shopLogo = $tenant->logo ? asset($tenant->logo) : asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
    $heroBg = $tenant->hero_banner ? asset($tenant->hero_banner) : asset('images/hero_barbershop_bg.jpg');
    $tagline = $tenant->hero_tagline ?? 'Pangkas Presisi • Minimalis & Modern';
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

<div class="min-h-screen flex flex-col justify-between bg-[#F8F9FA] text-zinc-900 font-sans selection:bg-zinc-900 selection:text-white w-full overflow-x-hidden relative">
    
    <!-- Floating Scandinavian Header -->
    <header class="bg-white/80 border-b border-zinc-200/60 backdrop-blur-xl sticky top-0 z-40">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 sm:h-20 flex items-center justify-between gap-4">
            <a href="{{ url('/' . $tenant->slug) }}" class="flex items-center gap-3 min-w-0">
                <img src="{{ $shopLogo }}" alt="{{ $tenant->name }}" class="w-9 h-9 sm:w-10 sm:h-10 object-contain rounded-xl border border-zinc-200 bg-white p-1 shadow-2xs flex-shrink-0" />
                <div class="min-w-0">
                    <div class="font-extrabold text-sm sm:text-base text-zinc-950 tracking-tight truncate">{{ $tenant->name }}</div>
                    <div class="text-[10px] sm:text-[11px] text-zinc-400 font-medium truncate">Modern Minimalist Studio</div>
                </div>
            </a>
            
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-zinc-100 border border-zinc-200/80 rounded-full text-[10px] sm:text-xs font-semibold text-zinc-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="truncate">Buka Hari Ini</span>
                </span>
            </div>
        </div>
    </header>

    <main class="flex-1 w-full">
        <!-- Hero Section: Split Asymmetric Nordic Layout -->
        <section class="py-12 sm:py-20 max-w-5xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left Column: Big Clean Heading & Subtitle -->
                <div class="lg:col-span-7 space-y-5 text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-zinc-900 text-white rounded-full text-[11px] font-semibold tracking-wide">
                        <span>{{ $tagline }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-zinc-950 tracking-tight leading-[1.15]">
                        {{ $tenant->hero_title ?: $tenant->name }}
                    </h1>

                    <p class="text-xs sm:text-sm text-zinc-500 leading-relaxed max-w-lg">
                        {{ $tenant->hero_subtitle ?: ($tenant->description ?? 'Pengalaman potong rambut minimalis dengan fokus pada kenyamanan, higienis, dan ketelitian bentuk gaya rambut.') }}
                    </p>

                    <div class="pt-2 flex flex-wrap items-center gap-4 text-xs font-medium text-zinc-500">
                        <div class="flex items-center gap-1.5 bg-white border border-zinc-200/80 px-3 py-1.5 rounded-xl shadow-2xs">
                            <span class="font-extrabold text-zinc-900">4.9 / 5.0</span>
                            <span class="text-zinc-400">&bull; Rating Pelanggan</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-white border border-zinc-200/80 px-3 py-1.5 rounded-xl shadow-2xs">
                            <svg class="w-4 h-4 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-zinc-700 truncate max-w-[200px] sm:max-w-none">{{ $tenant->address ?? 'Alamat Outlet Barbershop' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Hero Visual Card -->
                <div class="lg:col-span-5">
                    <div class="relative rounded-3xl overflow-hidden border border-zinc-200/80 bg-white p-2 shadow-xl">
                        <div class="h-64 sm:h-72 rounded-2xl bg-cover bg-center relative flex items-end p-5 overflow-hidden" style="background-image: url('{{ $heroBg }}');">
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/80 via-zinc-950/20 to-transparent"></div>
                            <div class="relative z-10 text-white space-y-1">
                                <div class="font-extrabold text-lg text-white">{{ $tenant->name }}</div>
                                <div class="text-xs text-zinc-300">Siap Melayani Booking Online Hari Ini</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Main Interactive Booking & Catalog Body -->
        <section class="py-6 max-w-5xl mx-auto px-4 sm:px-6 space-y-12">
            @if($booking_success)
                <div class="p-8 sm:p-10 rounded-3xl bg-white border border-zinc-200 text-center space-y-4 max-w-md mx-auto shadow-xl">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 text-2xl font-bold mx-auto">✓</div>
                    <div class="space-y-1">
                        <h3 class="font-extrabold text-2xl text-zinc-950">Reservasi Berhasil!</h3>
                        <p class="text-xs text-zinc-500">Kode Booking Anda: <strong class="font-mono text-zinc-950 bg-zinc-100 px-3.5 py-1 rounded-xl border border-zinc-200 inline-block my-1 text-sm">{{ $created_reservation_code }}</strong></p>
                    </div>
                    <p class="text-xs text-zinc-500 leading-relaxed">
                        Terima kasih telah memesan di <strong>{{ $tenant->name }}</strong>. Kami akan segera menghubungi WhatsApp Anda untuk konfirmasi jadwal.
                    </p>
                    <button wire:click="$set('booking_success', false)" class="w-full py-3.5 bg-zinc-950 hover:bg-zinc-800 text-white font-extrabold text-xs rounded-2xl shadow-lg transition">
                        Buat Reservasi Baru
                    </button>
                </div>
            @else
                <div class="space-y-12">
                    @foreach($sectionOrder as $secKey)
                        
                        <!-- SECTION: SERVICES -->
                        @if($secKey === 'services' && $showServices)
                            <div class="space-y-6">
                                <div class="flex items-center justify-between border-b border-zinc-200/60 pb-4">
                                    <div>
                                        <h2 class="text-lg sm:text-xl font-black text-zinc-950">Katalog & Tarif Layanan</h2>
                                        <p class="text-xs text-zinc-400 mt-0.5">Pilih paket potongan atau perawatan sesuai kebutuhan Anda.</p>
                                    </div>
                                    <span class="text-xs font-semibold text-zinc-400 bg-white border border-zinc-200 px-3 py-1 rounded-full shadow-2xs hidden sm:block">{{ $services->count() }} Pilihan Layanan</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse($services as $srv)
                                        <div class="p-5 rounded-3xl bg-white border border-zinc-200/80 hover:border-zinc-400 transition-all shadow-2xs hover:shadow-md flex flex-col justify-between space-y-4 group">
                                            <div class="space-y-1.5">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span class="font-extrabold text-sm text-zinc-950 group-hover:text-black">{{ $srv->name }}</span>
                                                    <span class="text-[10px] font-semibold text-zinc-400 bg-zinc-100 px-2 py-0.5 rounded-full flex-shrink-0">{{ $srv->duration_minutes }} Mnt</span>
                                                </div>
                                                <p class="text-xs text-zinc-500 leading-relaxed">{{ $srv->description }}</p>
                                            </div>

                                            <div class="pt-3 border-t border-zinc-100 flex items-center justify-between">
                                                <span class="text-xs font-extrabold text-zinc-950 whitespace-nowrap flex-shrink-0">Rp {{ number_format($srv->price, 0, ',', '.') }}</span>
                                                <button wire:click="$set('service_id', '{{ $srv->id }}')" class="text-[11px] font-bold text-zinc-900 bg-zinc-100 hover:bg-zinc-900 hover:text-white px-3 py-1.5 rounded-xl transition">
                                                    Pilih Paket
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-zinc-400 py-8 text-center col-span-full">Katalog layanan belum tersedia.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <!-- SECTION: BOOKING FORM -->
                        @if($secKey === 'booking')
                            <div class="space-y-6">
                                <div class="flex items-center justify-between border-b border-zinc-200/60 pb-4">
                                    <div>
                                        <h2 class="text-lg sm:text-xl font-black text-zinc-950">Formulir Booking Online</h2>
                                        <p class="text-xs text-zinc-400 mt-0.5">Pilih tanggal, jam, dan konfirmasi reservasi Anda.</p>
                                    </div>
                                </div>

                                <div class="p-6 sm:p-8 rounded-3xl bg-white border border-zinc-200/80 shadow-lg space-y-6">
                                    <form wire:submit.prevent="createBooking" class="space-y-5 text-xs">
                                        
                                        <!-- Step 1: Customer Info -->
                                        <div class="space-y-3">
                                            <div class="text-xs font-extrabold text-zinc-900 flex items-center gap-2">
                                                <span class="w-5 h-5 rounded-full bg-zinc-900 text-white flex items-center justify-center text-[10px]">1</span>
                                                <span>Informasi Kontak Pelanggan</span>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block font-semibold text-zinc-700 mb-1.5">Nama Lengkap</label>
                                                    <input type="text" wire:model="customer_name" required placeholder="Contoh: Doni Setiawan" class="w-full bg-zinc-50 border border-zinc-200 rounded-2xl px-4 py-3 text-xs text-zinc-900 placeholder:text-zinc-400 focus:bg-white focus:border-zinc-900 focus:outline-none transition" />
                                                </div>
                                                <div>
                                                    <label class="block font-semibold text-zinc-700 mb-1.5">Nomor WhatsApp</label>
                                                    <input type="text" wire:model="customer_phone" required placeholder="081234567890" class="w-full bg-zinc-50 border border-zinc-200 rounded-2xl px-4 py-3 text-xs text-zinc-900 placeholder:text-zinc-400 focus:bg-white focus:border-zinc-900 focus:outline-none transition" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step 2: Service & Barber Choice -->
                                        <div class="space-y-3 pt-2">
                                            <div class="text-xs font-extrabold text-zinc-900 flex items-center gap-2">
                                                <span class="w-5 h-5 rounded-full bg-zinc-900 text-white flex items-center justify-center text-[10px]">2</span>
                                                <span>Pilih Paket & Barber Specialist</span>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block font-semibold text-zinc-700 mb-1.5">Paket Layanan Pangkas</label>
                                                    <select wire:model="service_id" required class="w-full bg-zinc-50 border border-zinc-200 rounded-2xl px-4 py-3 text-xs font-semibold text-zinc-900 focus:bg-white focus:border-zinc-900 focus:outline-none transition">
                                                        <option value="">-- Pilih Paket Layanan --</option>
                                                        @foreach($services as $s)
                                                            <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price, 0, ',', '.') }} ({{ $s->duration_minutes }} Mnt)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block font-semibold text-zinc-700 mb-1.5">Barber Workstation (Opsional)</label>
                                                    <select wire:model="barber_user_id" class="w-full bg-zinc-50 border border-zinc-200 rounded-2xl px-4 py-3 text-xs font-semibold text-zinc-900 focus:bg-white focus:border-zinc-900 focus:outline-none transition">
                                                        <option value="">-- Bebas (Barber Siapa Saja Ready) --</option>
                                                        @foreach($barbers as $b)
                                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step 3: Date & Time -->
                                        <div class="space-y-3 pt-2">
                                            <div class="text-xs font-extrabold text-zinc-900 flex items-center gap-2">
                                                <span class="w-5 h-5 rounded-full bg-zinc-900 text-white flex items-center justify-center text-[10px]">3</span>
                                                <span>Pilih Jadwal Kedatangan</span>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block font-semibold text-zinc-700 mb-1.5">Tanggal Reservasi</label>
                                                    <input type="date" wire:model="reservation_date" required class="w-full bg-zinc-50 border border-zinc-200 rounded-2xl px-4 py-3 text-xs text-zinc-900 focus:bg-white focus:border-zinc-900 focus:outline-none transition" />
                                                </div>
                                                <div>
                                                    <label class="block font-semibold text-zinc-700 mb-1.5">Jam Mulai</label>
                                                    <input type="time" wire:model="start_time" required class="w-full bg-zinc-50 border border-zinc-200 rounded-2xl px-4 py-3 text-xs text-zinc-900 focus:bg-white focus:border-zinc-900 focus:outline-none transition" />
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block font-semibold text-zinc-700 mb-1.5">Catatan Request Model Rambut (Opsional)</label>
                                            <textarea wire:model="notes" rows="2" placeholder="Contoh: Undercut Fade, Taper Fade..." class="w-full bg-zinc-50 border border-zinc-200 rounded-2xl px-4 py-3 text-xs text-zinc-900 placeholder:text-zinc-400 focus:bg-white focus:border-zinc-900 focus:outline-none transition"></textarea>
                                        </div>

                                        <button type="submit" class="w-full py-4 bg-zinc-950 hover:bg-zinc-800 text-white font-extrabold text-xs rounded-2xl shadow-lg transition uppercase tracking-wider">
                                            Kirim Booking Sekarang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <!-- SECTION: PRODUCTS -->
                        @if($secKey === 'products' && $showProducts && $products->count() > 0)
                            <div class="space-y-6">
                                <div class="flex items-center justify-between border-b border-zinc-200/60 pb-4">
                                    <div>
                                        <h2 class="text-lg sm:text-xl font-black text-zinc-950">Produk Retail & Pomade</h2>
                                        <p class="text-xs text-zinc-400 mt-0.5">Produk perawatan rambut resmi barbershop.</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($products as $prd)
                                        <div class="p-5 rounded-3xl bg-white border border-zinc-200/80 flex items-center justify-between shadow-2xs">
                                            <div>
                                                <div class="font-extrabold text-xs text-zinc-950">{{ $prd->name }}</div>
                                                <div class="text-[10px] text-zinc-400 mt-0.5">{{ $prd->category }}</div>
                                            </div>
                                            <span class="font-extrabold text-xs text-zinc-900 bg-zinc-100 border border-zinc-200 px-3 py-1.5 rounded-xl whitespace-nowrap flex-shrink-0">Rp {{ number_format($prd->price, 0, ',', '.') }}</span>
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

    <footer class="bg-white border-t border-zinc-200/60 py-8 text-zinc-400 text-xs">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <div>
                <strong class="text-zinc-950 font-extrabold text-sm">{{ $tenant->name }}</strong>
                <div class="text-[11px] text-zinc-400 mt-0.5">{{ $footerText }}</div>
            </div>
            <div class="text-zinc-400 text-[11px]">
                <span>{{ $footerCopyright }}</span> &bull; Powered by <span class="font-bold text-zinc-950">BarberSaaS™</span>
            </div>
        </div>
    </footer>
</div>
