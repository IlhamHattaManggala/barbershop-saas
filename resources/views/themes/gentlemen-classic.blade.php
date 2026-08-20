@php
    $shopLogo = $tenant->logo ? asset($tenant->logo) : asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
    $heroBg = $tenant->hero_banner ? asset($tenant->hero_banner) : asset('images/hero_barbershop_bg.jpg');
    $tagline = $tenant->hero_tagline ?? 'Toko Buka • Siap Menerima Reservasi Waktu Pangkas';
    $btnRadius = 'rounded-xl';
    $color = $tenant->primary_color ?? 'amber';

    $layoutPos = $tenant->layout_pos ?? 'left';
    $showServices = (bool)($tenant->show_services ?? true);
    $showProducts = (bool)($tenant->show_products ?? true);

    $rawOrder = $tenant->section_order;
    if (is_string($rawOrder)) {
        $rawOrder = json_decode($rawOrder, true);
    }
    $sectionOrder = is_array($rawOrder) && count($rawOrder) > 0 ? $rawOrder : ['services', 'booking', 'products'];

    $footerText = $tenant->footer_text ?? ($tenant->address ?? 'Alamat Outlet Barbershop');
    $footerCopyright = $tenant->footer_copyright ?? ('© ' . date('Y') . ' ' . $tenant->name . '. All rights reserved.');

    $colorMap = [
        'amber' => ['text' => 'text-amber-700', 'bg' => 'bg-amber-500', 'bg_hover' => 'hover:bg-amber-600', 'border' => 'border-amber-300', 'badge_bg' => 'bg-amber-50', 'badge_text' => 'text-amber-800', 'btn_text' => 'text-zinc-950'],
        'indigo' => ['text' => 'text-indigo-700', 'bg' => 'bg-indigo-600', 'bg_hover' => 'hover:bg-indigo-700', 'border' => 'border-indigo-200', 'badge_bg' => 'bg-indigo-50', 'badge_text' => 'text-indigo-800', 'btn_text' => 'text-white'],
        'emerald' => ['text' => 'text-emerald-700', 'bg' => 'bg-emerald-600', 'bg_hover' => 'hover:bg-emerald-700', 'border' => 'border-emerald-200', 'badge_bg' => 'bg-emerald-50', 'badge_text' => 'text-emerald-800', 'btn_text' => 'text-white'],
        'rose' => ['text' => 'text-rose-700', 'bg' => 'bg-rose-600', 'bg_hover' => 'hover:bg-rose-700', 'border' => 'border-rose-200', 'badge_bg' => 'bg-rose-50', 'badge_text' => 'text-rose-800', 'btn_text' => 'text-white'],
        'violet' => ['text' => 'text-violet-700', 'bg' => 'bg-violet-600', 'bg_hover' => 'hover:bg-violet-700', 'border' => 'border-violet-200', 'badge_bg' => 'bg-violet-50', 'badge_text' => 'text-violet-800', 'btn_text' => 'text-white'],
        'zinc' => ['text' => 'text-zinc-900', 'bg' => 'bg-zinc-900', 'bg_hover' => 'hover:bg-zinc-800', 'border' => 'border-zinc-300', 'badge_bg' => 'bg-zinc-100', 'badge_text' => 'text-zinc-900', 'btn_text' => 'text-white'],
    ];

    $c = $colorMap[$color] ?? $colorMap['amber'];
@endphp

<div class="min-h-screen flex flex-col justify-between bg-slate-50 text-slate-900 font-sans w-full overflow-x-hidden relative">
    <!-- Header Navigation Bar -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-40 shadow-2xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 sm:h-20 flex items-center justify-between gap-2">
            <a href="{{ url('/' . $tenant->slug) }}" class="flex items-center gap-2.5 min-w-0">
                <img src="{{ $shopLogo }}" alt="{{ $tenant->name }}" class="w-9 h-9 sm:w-10 sm:h-10 object-contain {{ $btnRadius }} border border-slate-200 bg-white p-1 shadow-xs flex-shrink-0" />
                <div class="min-w-0">
                    <div class="font-extrabold font-heading text-sm sm:text-lg text-slate-900 tracking-tight truncate">{{ $tenant->name }}</div>
                    <div class="text-[10px] sm:text-[11px] {{ $c['text'] }} font-bold truncate">GENTLEMEN CLASSIC BARBERSHOP</div>
                </div>
            </a>
        </div>
    </header>

    <main class="flex-1 w-full">
        <section class="relative py-12 sm:py-16 bg-slate-900 border-b border-slate-800 text-white overflow-hidden px-4">
            <div class="absolute inset-0 bg-cover bg-center opacity-55" style="background-image: url('{{ $heroBg }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-900/50 to-slate-950/30"></div>
            <div class="max-w-6xl mx-auto relative z-10 space-y-3">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 {{ $btnRadius }} bg-amber-500/20 border border-amber-500/40 text-amber-300 text-[10px] sm:text-xs font-semibold max-w-full truncate">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse flex-shrink-0"></span>
                    <span class="truncate">{{ $tagline }}</span>
                </div>
                <div class="flex items-center gap-3 sm:gap-4">
                    <img src="{{ $shopLogo }}" alt="{{ $tenant->name }}" class="w-12 h-12 sm:w-16 sm:h-16 object-contain rounded-2xl border border-slate-700 bg-white p-1.5 shadow-xl flex-shrink-0" />
                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-3xl md:text-4xl font-extrabold font-heading text-white tracking-tight break-words">
                            {{ $tenant->hero_title ?: $tenant->name }}
                        </h1>
                        <div class="text-[11px] sm:text-xs text-amber-300 font-medium flex items-center gap-1.5 pt-1 truncate">
                            <span class="truncate">{{ $tenant->address ?? 'Alamat Outlet Barbershop' }}</span>
                        </div>
                    </div>
                </div>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed pt-1">
                    {{ $tenant->hero_subtitle ?: ($tenant->description ?? 'Layanan potong rambut pria presisi, cukur klimis, coloring, dan perawatan jenggot berkualitas tinggi.') }}
                </p>
            </div>
        </section>

        <section class="py-8 sm:py-10 max-w-6xl mx-auto px-4 sm:px-6">
            @if($booking_success)
                <div class="p-6 sm:p-8 {{ $btnRadius }} bg-white border border-emerald-200 text-slate-900 space-y-4 shadow-xl max-w-2xl mx-auto text-center">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-emerald-100 border border-emerald-300 flex items-center justify-center text-emerald-600 text-xl sm:text-2xl font-bold mx-auto">✓</div>
                    <div class="space-y-1">
                        <h3 class="font-extrabold font-heading text-xl sm:text-2xl text-slate-900">Reservasi Berhasil Dibuat!</h3>
                        <p class="text-xs sm:text-sm text-slate-600">Kode Booking Anda: <strong class="font-mono {{ $c['text'] }} {{ $c['badge_bg'] }} px-3.5 py-1 {{ $btnRadius }} border {{ $c['border'] }} inline-block my-1">{{ $created_reservation_code }}</strong></p>
                    </div>
                    <button wire:click="$set('booking_success', false)" class="w-full sm:w-auto px-6 py-3 {{ $c['bg'] }} {{ $c['bg_hover'] }} {{ $c['btn_text'] }} font-extrabold text-xs {{ $btnRadius }} shadow-md transition">Buat Reservasi Baru</button>
                </div>
            @else
                <div class="space-y-8 sm:space-y-10">
                    @foreach($sectionOrder as $secKey)
                        @if($secKey === 'services' && $showServices)
                            <div class="p-5 sm:p-6 {{ $btnRadius }} bg-white border border-slate-200/80 shadow-md space-y-4">
                                <h3 class="text-base sm:text-lg font-extrabold font-heading text-slate-900 border-b border-slate-100 pb-3">Daftar Layanan & Tarif Pangkas</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4">
                                    @forelse($services as $srv)
                                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 flex flex-col justify-between space-y-2 transition">
                                            <div>
                                                <div class="font-bold text-xs text-slate-900">{{ $srv->name }}</div>
                                                <div class="text-[11px] text-slate-500 mt-0.5">{{ $srv->duration_minutes }} Menit &bull; {{ $srv->description }}</div>
                                            </div>
                                            <span class="font-mono font-bold text-xs {{ $c['text'] }} {{ $c['badge_bg'] }} border {{ $c['border'] }} px-2.5 py-1 rounded-lg w-fit whitespace-nowrap flex-shrink-0">Rp {{ number_format($srv->price, 0, ',', '.') }}</span>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400 py-4 text-center col-span-full">Belum ada katalog layanan.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        @if($secKey === 'booking')
                            <div class="p-5 sm:p-8 {{ $btnRadius }} bg-white border border-slate-200/80 shadow-md space-y-5 sm:space-y-6">
                                <div class="border-b border-slate-100 pb-3.5 sm:pb-4">
                                    <h2 class="text-lg sm:text-xl font-extrabold font-heading text-slate-900">Formulir Reservasi Online</h2>
                                    <p class="text-xs text-slate-500">Pilih paket pangkas & jadwal jam keberangkatan Anda.</p>
                                </div>
                                <form wire:submit.prevent="createBooking" class="space-y-4 text-xs">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1.5">Nama Lengkap Pelanggan</label>
                                            <input type="text" wire:model="customer_name" required placeholder="Contoh: Doni Setiawan" class="w-full bg-slate-50 border border-slate-200 {{ $btnRadius }} px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:outline-none" />
                                        </div>
                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1.5">Nomor WhatsApp (Aktif)</label>
                                            <input type="text" wire:model="customer_phone" required placeholder="081234567890" class="w-full bg-slate-50 border border-slate-200 {{ $btnRadius }} px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:outline-none" />
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1.5">Pilih Layanan Pangkas</label>
                                            <select wire:model="service_id" required class="w-full bg-slate-50 border border-slate-200 {{ $btnRadius }} px-3.5 py-2.5 text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none">
                                                <option value="">-- Pilih Paket Layanan --</option>
                                                @foreach($services as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price, 0, ',', '.') }} ({{ $s->duration_minutes }} Mnt)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1.5">Pilih Barber Workstation (Opsional)</label>
                                            <select wire:model="barber_user_id" class="w-full bg-slate-50 border border-slate-200 {{ $btnRadius }} px-3.5 py-2.5 text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none">
                                                <option value="">-- Bebas (Siapa Saja Ready) --</option>
                                                @foreach($barbers as $b)
                                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1.5">Tanggal Reservasi</label>
                                            <input type="date" wire:model="reservation_date" required class="w-full bg-slate-50 border border-slate-200 {{ $btnRadius }} px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:outline-none" />
                                        </div>
                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1.5">Jam Mulai</label>
                                            <input type="time" wire:model="start_time" required class="w-full bg-slate-50 border border-slate-200 {{ $btnRadius }} px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:outline-none" />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block font-semibold text-slate-700 mb-1.5">Catatan Request Model Rambut</label>
                                        <textarea wire:model="notes" rows="2" placeholder="Contoh: Undercut Fade, cuci ekstra..." class="w-full bg-slate-50 border border-slate-200 {{ $btnRadius }} px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:outline-none"></textarea>
                                    </div>
                                    <button type="submit" class="w-full py-3.5 px-4 {{ $c['bg'] }} {{ $c['bg_hover'] }} {{ $c['btn_text'] }} font-extrabold text-xs {{ $btnRadius }} shadow-lg transition uppercase tracking-wider">Kirim Booking Pangkas Sekarang</button>
                                </form>
                            </div>
                        @endif

                        @if($secKey === 'products' && $showProducts && $products->count() > 0)
                            <div class="p-5 sm:p-6 {{ $btnRadius }} bg-white border border-slate-200/80 shadow-md space-y-4">
                                <h3 class="text-base sm:text-lg font-extrabold font-heading text-slate-900 border-b border-slate-100 pb-3">Produk Retail & Pomade</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4">
                                    @foreach($products as $prd)
                                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 flex items-center justify-between transition gap-2">
                                            <div class="min-w-0">
                                                <div class="font-bold text-xs text-slate-900 truncate">{{ $prd->name }}</div>
                                                <div class="text-[11px] text-slate-500 mt-0.5 truncate">Kategori: {{ $prd->category }}</div>
                                            </div>
                                            <span class="font-mono font-bold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-lg flex-shrink-0 whitespace-nowrap">Rp {{ number_format($prd->price, 0, ',', '.') }}</span>
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

    <footer class="bg-white border-t border-slate-200 py-6 sm:py-8 text-slate-600 text-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <div>
                <strong class="text-slate-900 font-heading text-sm">{{ $tenant->name }}</strong>
                <div class="text-[11px] text-slate-500 mt-0.5">{{ $footerText }}</div>
            </div>
            <div class="text-slate-500 text-[11px]">
                <span>{{ $footerCopyright }}</span> &bull; Powered by <span class="font-bold {{ $c['text'] }}">BarberSaaS™</span>
            </div>
        </div>
    </footer>
</div>
