<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tenant->name }} - Portal Reservasi Online Barbershop</title>
    <link rel="icon" type="image/webp" href="{{ asset($tenant->logo ?? 'images/logos/Logo-BaberSaaS.webp') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="h-full bg-slate-900 text-slate-100 antialiased flex flex-col justify-between selection:bg-indigo-600 selection:text-white">

    <!-- Header Navigation Bar -->
    <header class="bg-slate-950/80 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
            <!-- Brand Logo & Name -->
            <a href="{{ url('/' . $tenant->slug) }}" class="flex items-center gap-3">
                @if($tenant->logo)
                    <img src="{{ asset($tenant->logo) }}" alt="{{ $tenant->name }}" class="w-10 h-10 object-contain rounded-xl border border-slate-700 bg-white p-1" />
                @else
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 border border-indigo-500 flex items-center justify-center font-extrabold text-white text-md font-heading">
                        {{ strtoupper(substr($tenant->name, 0, 2)) }}
                    </div>
                @endif

                <div>
                    <div class="font-extrabold font-heading text-lg text-white tracking-tight">{{ $tenant->name }}</div>
                    <div class="text-[11px] text-indigo-300 font-medium">Portal Booking Online</div>
                </div>
            </a>

            <!-- Contact & Login Actions -->
            <div class="flex items-center gap-4">
                @if($tenant->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->phone) }}" target="_blank" class="hidden sm:inline-flex items-center gap-2 text-xs font-bold text-emerald-400 hover:text-emerald-300 bg-emerald-950/60 border border-emerald-800/80 px-3.5 py-2 rounded-lg transition">
                        <span>💬 WA: {{ $tenant->phone }}</span>
                    </a>
                @endif
                <a href="/login" class="px-4 py-2 text-xs font-bold text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-lg border border-slate-700 transition">
                    Login Staf / Owner
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-1">

        <!-- Hero Section Header -->
        <section class="relative py-12 md:py-16 bg-slate-950 border-b border-slate-800 overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center opacity-20" style="background-image: url('{{ asset('images/hero_barbershop_bg.jpg') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent"></div>

            <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Toko Buka &bull; Siap Menerima Reservasi Slot Pangkas
                </div>

                <h1 class="text-3xl sm:text-4xl font-extrabold font-heading text-white tracking-tight">
                    {{ $tenant->name }}
                </h1>
                
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                    {{ $tenant->description ?? 'Layanan potong rambut pria presisi, cukur klimis, coloring, dan perawatan jenggot berkualitas tinggi.' }}
                </p>

                <div class="text-xs text-indigo-300 font-medium flex items-center gap-1.5 pt-1">
                    📍 <span>{{ $tenant->address ?? 'Alamat Outlet Barbershop' }}</span>
                </div>
            </div>
        </section>

        <!-- Main Booking & Catalog Section -->
        <section class="py-10 max-w-6xl mx-auto px-4 sm:px-6">
            
            @if($booking_success)
                <!-- Success Alert Card -->
                <div class="p-8 rounded-2xl bg-emerald-950/90 border border-emerald-800 text-white space-y-4 shadow-2xl max-w-2xl mx-auto text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center text-emerald-400 text-3xl font-bold mx-auto">✓</div>
                    
                    <div class="space-y-1">
                        <h3 class="font-extrabold font-heading text-2xl">Reservasi Berhasil Dibuat!</h3>
                        <p class="text-sm text-emerald-200">Kode Booking Anda: <strong class="font-mono text-white text-lg bg-emerald-900/80 px-3 py-1 rounded-lg border border-emerald-700 inline-block my-1">{{ $created_reservation_code }}</strong></p>
                    </div>

                    <p class="text-xs text-emerald-200/80 leading-relaxed max-w-md mx-auto">
                        Terima kasih telah memesan slot pangkas di <strong>{{ $tenant->name }}</strong>. Tim kasir kami akan segera mengonfirmasi reservasi Anda via WhatsApp.
                    </p>

                    <button wire:click="$set('booking_success', false)" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs rounded-xl shadow-lg transition">
                        Buat Reservasi Baru
                    </button>
                </div>
            @else
                <!-- 2-Column Grid: Left Booking Form, Right Catalog -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- Left 7 Cols: Online Booking Form -->
                    <div class="lg:col-span-7 space-y-4">
                        <div class="p-6 sm:p-8 rounded-2xl bg-slate-950 border border-slate-800 shadow-xl space-y-6">
                            <div class="border-b border-slate-800 pb-4">
                                <h2 class="text-xl font-extrabold font-heading text-white">Formulir Reservasi Online</h2>
                                <p class="text-xs text-slate-400">Pilih paket pangkas & jadwal jam keberangkatan Anda.</p>
                            </div>

                            <form wire:submit.prevent="createBooking" class="space-y-4 text-xs">
                                <div>
                                    <label class="block font-semibold text-slate-300 mb-1.5">Nama Lengkap Pelanggan</label>
                                    <input type="text" wire:model="customer_name" required placeholder="Contoh: Doni Setiawan" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white placeholder:text-slate-600 focus:border-indigo-500 focus:outline-none" />
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-300 mb-1.5">Nomor WhatsApp (Aktif)</label>
                                    <input type="text" wire:model="customer_phone" required placeholder="081234567890" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white placeholder:text-slate-600 focus:border-indigo-500 focus:outline-none" />
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-300 mb-1.5">Pilih Layanan Pangkas</label>
                                    <select wire:model="service_id" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-xs font-semibold text-white focus:border-indigo-500 focus:outline-none">
                                        <option value="">-- Pilih Paket Layanan --</option>
                                        @foreach($services as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price, 0, ',', '.') }} ({{ $s->duration_minutes }} Mnt)</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-300 mb-1.5">Pilih Barber Workstation (Opsional)</label>
                                    <select wire:model="barber_user_id" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-xs font-semibold text-white focus:border-indigo-500 focus:outline-none">
                                        <option value="">-- Bebas (Siapa Saja Ready) --</option>
                                        @foreach($barbers as $b)
                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-semibold text-slate-300 mb-1.5">Tanggal Reservasi</label>
                                        <input type="date" wire:model="reservation_date" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:border-indigo-500 focus:outline-none" />
                                    </div>
                                    <div>
                                        <label class="block font-semibold text-slate-300 mb-1.5">Jam Mulai</label>
                                        <input type="time" wire:model="start_time" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:border-indigo-500 focus:outline-none" />
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-300 mb-1.5">Catatan Request Model Rambut</label>
                                    <textarea wire:model="notes" rows="2" placeholder="Contoh: Undercut Fade, cuci ekstra..." class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white placeholder:text-slate-600 focus:border-indigo-500 focus:outline-none"></textarea>
                                </div>

                                <button type="submit" class="w-full py-4 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition uppercase tracking-wider">
                                    Kirim Booking Slot Sekarang
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right 5 Cols: Catalog & Retail -->
                    <div class="lg:col-span-5 space-y-6">
                        
                        <!-- Catalog Layanan Card -->
                        <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 space-y-4">
                            <h3 class="text-lg font-extrabold font-heading text-white border-b border-slate-800 pb-3">Daftar Layanan & Tarif</h3>
                            
                            <div class="space-y-3">
                                @forelse($services as $srv)
                                    <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800/80 flex items-center justify-between">
                                        <div>
                                            <div class="font-bold text-xs text-white">{{ $srv->name }}</div>
                                            <div class="text-[11px] text-slate-400 mt-0.5">{{ $srv->duration_minutes }} Menit &bull; {{ $srv->description }}</div>
                                        </div>
                                        <span class="font-mono font-bold text-xs text-indigo-400 bg-indigo-950/60 border border-indigo-800 px-2.5 py-1 rounded-lg">Rp {{ number_format($srv->price, 0, ',', '.') }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-500 py-4 text-center">Belum ada katalog layanan.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Catalog Produk Retail Card -->
                        @if($products->count() > 0)
                            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 space-y-4">
                                <h3 class="text-lg font-extrabold font-heading text-white border-b border-slate-800 pb-3">Produk Retail & Pomade</h3>
                                
                                <div class="space-y-3">
                                    @foreach($products as $prd)
                                        <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800/80 flex items-center justify-between">
                                            <div>
                                                <div class="font-bold text-xs text-white">{{ $prd->name }}</div>
                                                <div class="text-[11px] text-slate-400 mt-0.5">Kategori: {{ $prd->category }}</div>
                                            </div>
                                            <span class="font-mono font-bold text-xs text-emerald-400 bg-emerald-950/60 border border-emerald-800 px-2.5 py-1 rounded-lg">Rp {{ number_format($prd->price, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>

                </div>
            @endif

        </section>

    </main>

    <!-- Footer Section -->
    <footer class="bg-slate-950 border-t border-slate-800 py-8 text-slate-400 text-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <strong class="text-white font-heading text-sm">{{ $tenant->name }}</strong>
                <div class="text-[11px] text-slate-500 mt-0.5">{{ $tenant->address ?? 'Alamat Outlet Barbershop' }}</div>
            </div>
            <div class="text-slate-500 text-[11px]">
                Powered by <span class="font-bold text-white">BarberSaaS™</span> &bull; © {{ date('Y') }} All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
