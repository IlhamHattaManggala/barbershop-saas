@php
    $siteName = \App\Models\SiteSetting::get('app_name', 'BarberSaaS');
    $siteLogo = asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
    $siteFavicon = asset(\App\Models\SiteSetting::get('app_favicon', 'images/logos/Logo-BaberSaaS.webp'));
@endphp

<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteName }} - Platform Barbershop Multi-Tenant #1</title>
    <link rel="icon" type="image/webp" href="{{ $siteFavicon }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased selection:bg-indigo-600 selection:text-white flex flex-col justify-between">

    <!-- Header Navigation -->
    <header class="bg-white border-b border-slate-100 sticky top-0 z-50 shadow-2xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2.5">
                <img src="{{ $siteLogo }}" alt="{{ $siteName }} Logo" class="w-10 h-10 object-contain rounded-xl shadow-md shadow-indigo-200" />
                <span class="font-bold font-heading text-xl text-slate-900 tracking-tight">{{ $siteName }}</span>
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-8 text-xs font-semibold uppercase tracking-wider text-slate-600">
                <a href="/" class="text-indigo-600 font-bold border-b-2 border-indigo-600 pb-1">Beranda</a>
                <a href="#fitur" class="hover:text-indigo-600 transition">Fitur Utama</a>
                <a href="#solusi" class="hover:text-indigo-600 transition">Solusi POS</a>
                <a href="#harga" class="hover:text-indigo-600 transition">Harga MVP</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <a href="/login" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-md shadow-indigo-200 transition">
                    Mulai Sekarang
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1">

        <!-- Hero Section (Centered Layout with Atmospheric Barbershop Background Image) -->
        <section class="py-20 md:py-28 bg-slate-900 relative overflow-hidden text-white">
            <!-- Background Image Layer -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-25 scale-105 transition transform duration-1000" style="background-image: url('{{ asset('images/hero_barbershop_bg.jpg') }}');"></div>
            
            <!-- Dark Indigo Overlay Gradient for Contrast & Premium Look -->
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/90 via-slate-900/80 to-slate-950"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl h-96 rounded-full bg-indigo-600/20 blur-3xl pointer-events-none"></div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 relative z-10 text-center flex flex-col items-center justify-center space-y-6">

                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white leading-tight tracking-tight max-w-3xl font-heading">
                    Kami Hadirkan Solusi <br class="hidden sm:inline" />untuk <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-indigo-300 to-cyan-400">Bisnis Barbershop Anda</span>
                </h1>

                <p class="text-sm md:text-base text-indigo-100/90 leading-relaxed font-normal max-w-xl">
                    Kelola operasional pangkas rambut Anda secara profesional. Lengkap dengan kasir POS cepat, jam kerja barber, stok produk, dan landing page online untuk setiap cabang.
                </p>

                <div class="pt-2 flex flex-wrap items-center justify-center gap-4 text-xs font-bold">
                    <a href="/login" class="px-8 py-3.5 text-slate-950 bg-cyan-400 hover:bg-cyan-300 rounded-xl shadow-lg shadow-cyan-400/20 transition inline-flex items-center gap-2 text-sm font-extrabold">
                        <span>Mulai Sekarang (Gratis)</span>
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </a>
                </div>

            </div>
        </section>

        <!-- Services Section (Kami Menyediakan Layanan Terbaik) -->
        <section id="fitur" class="py-16 md:py-24 bg-slate-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                
                <div class="text-center max-w-xl mx-auto mb-14">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Kami Menyediakan Layanan Terbaik</h2>
                    <p class="text-xs text-slate-500 mt-2">Solusi operasional terpadu yang dirancang khusus memenuhi kebutuhan barbershop modern.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Card 1 -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 text-center space-y-4 hover:-translate-y-1 transition duration-200">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 mx-auto flex items-center justify-center">
                            <x-icon name="store" class="w-6 h-6" />
                        </div>
                        <h3 class="font-heading font-bold text-base text-slate-900">Multi-Tenant Portal</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Setiap cabang memiliki URL slug mandiri (/slug) dan kustomisasi tema visual.
                        </p>
                        <a href="#demo" class="inline-block text-xs font-bold text-indigo-600 hover:underline uppercase tracking-wider">BACA SELENGKAPNYA</a>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 text-center space-y-4 hover:-translate-y-1 transition duration-200">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 mx-auto flex items-center justify-center">
                            <x-icon name="shopping-bag" class="w-6 h-6" />
                        </div>
                        <h3 class="font-heading font-bold text-base text-slate-900">Kasir POS Cepat</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Input jasa cukur & produk retail, kalkulator kembalian, QRIS & Tunai.
                        </p>
                        <a href="#solusi" class="inline-block text-xs font-bold text-indigo-600 hover:underline uppercase tracking-wider">BACA SELENGKAPNYA</a>
                    </div>

                    <!-- Card 3 (Active Highlighted Card) -->
                    <div class="bg-indigo-600 rounded-xl p-6 shadow-lg shadow-indigo-200 text-center space-y-4 text-white hover:-translate-y-1 transition duration-200">
                        <div class="w-12 h-12 rounded-xl bg-white/20 text-white mx-auto flex items-center justify-center backdrop-blur-xs">
                            <x-icon name="calendar" class="w-6 h-6 text-white" />
                        </div>
                        <h3 class="font-heading font-bold text-base text-white">Workstation Barber</h3>
                        <p class="text-xs text-indigo-100 leading-relaxed">
                            Penjadwalan waktu antrean barber otomatis tanpa risiko bentrok.
                        </p>
                        <a href="#solusi" class="inline-block px-4 py-1.5 rounded-full bg-white text-indigo-600 text-xs font-bold uppercase tracking-wider hover:bg-indigo-50 transition">BACA SELENGKAPNYA</a>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 text-center space-y-4 hover:-translate-y-1 transition duration-200">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 mx-auto flex items-center justify-center">
                            <x-icon name="phone" class="w-6 h-6" />
                        </div>
                        <h3 class="font-heading font-bold text-base text-slate-900">Struk WhatsApp</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Kirim bukti transaksi & rincian booking otomatis langsung ke WA pelanggan.
                        </p>
                        <a href="#solusi" class="inline-block text-xs font-bold text-indigo-600 hover:underline uppercase tracking-wider">BACA SELENGKAPNYA</a>
                    </div>

                </div>

            </div>
        </section>

        <!-- Deep Royal Blue Section (Solusi Praktis & Mudah!) -->
        <section id="solusi" class="py-16 md:py-24 bg-indigo-950 text-white relative overflow-hidden">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <!-- Left Circle Graphic (Laptop Preview in Circular Mask) -->
                    <div class="lg:col-span-5 flex justify-center">
                        <div class="relative w-72 h-72 sm:w-88 sm:h-88 rounded-full border-8 border-indigo-800/50 bg-indigo-900 p-2 shadow-2xl flex items-center justify-center">
                            <div class="w-full h-full rounded-full bg-slate-900 p-6 flex flex-col justify-center items-center text-center">
                                <x-icon name="laptop" class="w-16 h-16 text-indigo-400 mb-3" />
                                <div class="font-heading font-bold text-lg text-white">Kasir POS & Reservasi</div>
                                <div class="text-xs text-indigo-200 mt-1">Responsif & Mudah Digunakan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content (Simple Solutions Steps) -->
                    <div class="lg:col-span-7 space-y-6">
                        <h2 class="text-3xl font-extrabold font-heading text-white">Solusi Praktis & Mudah!</h2>
                        <p class="text-xs sm:text-sm text-indigo-200 leading-relaxed max-w-lg">
                            Empat langkah mudah untuk memodernisasi manajemen dan pengalaman pelanggan di barbershop Anda.
                        </p>

                        <!-- Process Steps List -->
                        <div class="space-y-4 pt-2">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-heading font-bold flex items-center justify-center shrink-0">1</div>
                                <div>
                                    <h4 class="font-bold text-sm text-white">Registrasi Barbershop (Tenant)</h4>
                                    <p class="text-xs text-indigo-200">Dapatkan slug URL khusus (/slug) dan atur profil toko Anda.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-heading font-bold flex items-center justify-center shrink-0">2</div>
                                <div>
                                    <h4 class="font-bold text-sm text-white">Tambahkan Staf Barber & Layanan</h4>
                                    <p class="text-xs text-indigo-200">Input daftar jasa pangkas rambut, produk retail, dan jadwal staf.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-heading font-bold flex items-center justify-center shrink-0">3</div>
                                <div>
                                    <h4 class="font-bold text-sm text-white">Gunakan Kasir POS & Reservasi</h4>
                                    <p class="text-xs text-indigo-200">Proses transaksi kasir di tempat atau terima booking online.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-heading font-bold flex items-center justify-center shrink-0">4</div>
                                <div>
                                    <h4 class="font-bold text-sm text-white">Kirim Struk Digital via WhatsApp</h4>
                                    <p class="text-xs text-indigo-200">Bukti pembayaran dikirim otomatis ke nomor WhatsApp pelanggan.</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex items-center gap-3">
                            <a href="/login" class="px-6 py-2.5 text-xs font-bold text-indigo-950 bg-white hover:bg-indigo-50 rounded-lg transition shadow-md">
                                Mulai Sekarang
                            </a>
                            <a href="#demo" class="px-6 py-2.5 text-xs font-bold text-white border border-indigo-700 hover:bg-indigo-900 rounded-lg transition">
                                Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Testimonial Section (Apa Kata Klien & Owner Barbershop - Only Dynamic Real Feedbacks) -->
        @php
            $realFeedbacks = \App\Models\AppFeedback::with(['user.tenant'])->latest()->take(6)->get();
        @endphp

        @if($realFeedbacks->count() > 0)
            <section id="demo" class="py-16 md:py-24 bg-white relative">
                <div class="max-w-6xl mx-auto px-4 sm:px-6">
                    
                    <div class="text-center max-w-xl mx-auto mb-14 space-y-2">
                        <span class="text-xs font-mono font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                            Pengalaman Pengelola
                        </span>
                        <h2 class="text-3xl font-extrabold text-slate-900 font-heading">Apa Kata Klien & Owner Barbershop</h2>
                        <p class="text-xs sm:text-sm text-slate-500">Pengalaman langsung dari para owner dan staf yang telah menggunakan sistem BarberSaaS.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($realFeedbacks as $fb)
                            <!-- Dynamic Real Feedback Card -->
                            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-lg shadow-slate-100/50 hover:shadow-xl hover:border-indigo-200 transition duration-300 flex flex-col justify-between space-y-6">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex text-amber-500 gap-0.5">
                                            @for($star = 1; $star <= 5; $star++)
                                                <svg class="size-4 fill-current {{ $star <= $fb->rating ? 'text-amber-500' : 'text-slate-200' }}" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-[11px] font-bold text-slate-400 font-mono">{{ number_format($fb->rating, 1) }} / 5.0</span>
                                    </div>
                                    
                                    <p class="text-xs text-slate-700 leading-relaxed font-normal italic">
                                        "{{ $fb->feedback_text ?: 'Sistem aplikasi manajemen barbershop yang sangat membantu operasional outlet.' }}"
                                    </p>
                                </div>

                                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-indigo-800 text-white font-bold font-heading flex items-center justify-center text-sm shadow-md shadow-indigo-200 shrink-0">
                                        {{ $fb->user->initials() }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-xs">{{ $fb->user->name }}</h4>
                                        <p class="text-[11px] text-slate-500 font-medium capitalize">{{ str_replace('_', ' ', $fb->user->role) }} &bull; {{ $fb->user->tenant->name ?? 'BarberSaaS Outlet' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </section>
        @endif

        <!-- Pricing Section (Paket Open-Core MVP Free) -->
        <section id="harga" class="py-16 md:py-24 bg-slate-50 border-t border-slate-200/80 relative overflow-hidden">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                
                <div class="text-center max-w-xl mx-auto mb-12">
                    <span class="text-xs font-mono font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                        Skema Akses Open-Core
                    </span>
                    <h2 class="text-3xl font-extrabold text-slate-900 font-heading mt-3">Paket Open-Core MVP Free</h2>
                    <p class="text-xs md:text-sm text-slate-500 mt-2">Seluruh fitur utama kasir POS, papan reservasi, dan portal cabang tersedia gratis tanpa biaya awal.</p>
                </div>

                <!-- Main Pricing Card -->
                <div class="max-w-2xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden relative">
                    <!-- Top Gradient Header Accent -->
                    <div class="h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-blue-500"></div>

                    <div class="p-8 md:p-10 space-y-8">
                        <!-- Card Header -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                            <div>
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold uppercase tracking-wider mb-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>AKSES MVP TANPA BATAS</span>
                                </div>
                                <h3 class="font-heading font-extrabold text-xl text-slate-900">Paket Pemula MVP</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Solusi lengkap untuk 1 barbershop hingga multi-cabang.</p>
                            </div>
                            
                            <div class="text-left sm:text-right">
                                <div class="text-4xl font-extrabold text-indigo-600 font-heading tracking-tight">
                                    Rp 0
                                </div>
                                <div class="text-xs font-semibold text-slate-400 mt-0.5">gratis selamanya</div>
                            </div>
                        </div>

                        <!-- Feature Checklist Grid (2 Columns) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-medium text-slate-700">
                            <div class="flex items-center gap-3 p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <x-icon name="check" class="w-3.5 h-3.5" />
                                </div>
                                <span><strong>Unlimited Staf Barber</strong></span>
                            </div>

                            <div class="flex items-center gap-3 p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <x-icon name="check" class="w-3.5 h-3.5" />
                                </div>
                                <span><strong>Unlimited Transaksi Kasir POS</strong></span>
                            </div>

                            <div class="flex items-center gap-3 p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <x-icon name="check" class="w-3.5 h-3.5" />
                                </div>
                                <span><strong>Papan Workstation & Reservasi</strong></span>
                            </div>

                            <div class="flex items-center gap-3 p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <x-icon name="check" class="w-3.5 h-3.5" />
                                </div>
                                <span><strong>Struk Digital & Logger WA</strong></span>
                            </div>

                            <div class="flex items-center gap-3 p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <x-icon name="check" class="w-3.5 h-3.5" />
                                </div>
                                <span><strong>Landing Page Portal Mandiri (/slug)</strong></span>
                            </div>

                            <div class="flex items-center gap-3 p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <x-icon name="check" class="w-3.5 h-3.5" />
                                </div>
                                <span><strong>Stok Produk & HPP Inventaris</strong></span>
                            </div>
                        </div>

                        <!-- Card CTA Button -->
                        <div class="space-y-3 pt-2">
                            <a href="/login" class="block w-full py-3 text-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-200 transition duration-200">
                                Daftarkan Barbershop Sekarang &rarr;
                            </a>
                            <div class="text-center text-[11px] text-slate-400 font-medium">
                                &zap; Tanpa kartu kredit &bull; Setup instan dalam 2 menit
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Ready to Get Started CTA Banner (Siap Memulai Sekarang?) -->
        <section class="py-12 bg-slate-50 border-t border-slate-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-950 rounded-2xl p-8 sm:p-12 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
                    <!-- Subtle Orb Effect -->
                    <div class="absolute -top-10 -right-10 w-64 h-64 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="space-y-2 text-center md:text-left relative z-10">
                        <h3 class="font-heading font-extrabold text-2xl sm:text-3xl text-white">Siap Memulai Sekarang?</h3>
                        <p class="text-xs sm:text-sm text-indigo-200 max-w-md">
                            Buka akun barbershop Anda sekarang dan berikan pelayanan terbaik untuk pelanggan Anda.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 relative z-10 shrink-0">
                        <a href="/login" class="px-6 py-3 text-xs font-bold text-slate-900 bg-white hover:bg-indigo-50 rounded-xl shadow-md transition">
                            Mulai Sekarang (Gratis)
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Deep Blue Footer -->
    <footer class="bg-indigo-950 text-indigo-200 py-12 text-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div class="space-y-3">
                <div class="flex items-center gap-2 text-white font-bold font-heading text-lg">
                    <img src="{{ asset('images/logos/Logo-BaberSaaS.webp') }}" alt="BarberSaaS Logo" class="w-6 h-6 object-contain" />
                    <span>BarberSaaS</span>
                </div>
                <p class="text-[11px] text-indigo-300 leading-relaxed">
                    Sistem operasi manajemen barbershop & pangkas rambut pria terpercaya di Indonesia.
                </p>
            </div>
            <div>
                <h4 class="font-bold text-white mb-3 uppercase tracking-wider text-[11px]">Navigasi</h4>
                <ul class="space-y-1.5 text-indigo-300">
                    <li><a href="/" class="hover:text-white">Beranda</a></li>
                    <li><a href="#fitur" class="hover:text-white">Fitur Utama</a></li>
                    <li><a href="#solusi" class="hover:text-white">Solusi POS</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-white mb-3 uppercase tracking-wider text-[11px]">Kontak & Sistem</h4>
                <div class="space-y-1 text-indigo-300">
                    <div>Email: support@babershop.my.id</div>
                    <div>WhatsApp: 0812-3456-7890</div>
                    <div class="mt-2 text-emerald-400 font-bold">&bull; Sistem Berjalan Normal</div>
                </div>
            </div>
        </div>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 border-t border-indigo-900 pt-6 text-center text-indigo-400 text-[11px]">
            &copy; {{ date('Y') }} BarberSaaS. Seluruh hak cipta dilindungi.
        </div>
    </footer>

</body>
</html>
