@php
    $siteName = \App\Models\SiteSetting::get('app_name', 'BarberSaaS');
    $siteLogo = asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        @include('partials.head')
        <title>{{ $title ? $title . ' - ' . $siteName : $siteName }}</title>
        <!-- Google Fonts: Outfit & Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            h1, h2, h3, h4, .font-heading { font-family: 'Outfit', sans-serif; }
        </style>
    </head>
    <body class="min-h-screen bg-slate-950 text-white font-sans antialiased selection:bg-cyan-400 selection:text-indigo-950 relative overflow-x-hidden">
        
        <!-- Fullscreen Background with Royal Indigo Gradient & Glow Orbs -->
        <div class="fixed inset-0 bg-gradient-to-br from-indigo-950 via-slate-950 to-indigo-900 z-0"></div>
        <div class="fixed -top-40 -left-40 w-96 h-96 rounded-full bg-indigo-600/20 blur-3xl pointer-events-none"></div>
        <div class="fixed -bottom-40 -right-40 w-96 h-96 rounded-full bg-cyan-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 min-h-screen flex flex-col justify-between p-4 sm:p-6 lg:p-8">
            
            <!-- Main 2-Column Grid -->
            <div class="max-w-7xl mx-auto w-full flex-1 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center py-6">
                
                <!-- Left Column (Brand & Text Description) -->
                <div class="lg:col-span-6 space-y-8 p-4 sm:p-8 flex flex-col justify-center h-full">

                    <!-- Brand Statement -->
                    <div class="space-y-4">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                            <img src="{{ $siteLogo }}" alt="{{ $siteName }} Logo" class="w-10 h-10 object-contain rounded-xl shadow-lg shadow-cyan-400/20" />
                            <span class="font-heading font-extrabold text-2xl text-white tracking-tight">{{ $siteName }}</span>
                        </a>

                        <h2 class="text-2xl sm:text-3xl font-extrabold font-heading text-white leading-tight">
                            Selamat Datang di Sistem Operasi Manajemen Barbershop.
                        </h2>

                        <p class="text-xs sm:text-sm text-indigo-200/80 leading-relaxed font-normal max-w-md">
                            Kelola kasir POS, papan jam kerja barber workstation, stok produk inventaris, dan portal reservasi online cabang Anda.
                        </p>
                    </div>

                    <!-- Bottom Quick Action Links -->
                    <div class="pt-4 flex items-center gap-3">
                        <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl border border-indigo-500/30 bg-indigo-900/30 hover:bg-indigo-800/50 text-xs font-semibold text-indigo-200 transition inline-flex items-center gap-2 backdrop-blur-xs">
                            <x-icon name="arrow-right" class="w-3.5 h-3.5 rotate-180 text-cyan-400" />
                            <span>Beranda Utama</span>
                        </a>
                        <span class="text-xs text-indigo-400/60 font-mono">BarberSaaS v1.0</span>
                    </div>

                </div>

                <!-- Right Column (Glassmorphic Translucent Auth Card Matching Image) -->
                <div class="lg:col-span-6 flex justify-center lg:justify-end">
                    <div class="w-full max-w-md bg-indigo-900/30 backdrop-blur-xl border border-indigo-500/20 rounded-3xl p-6 sm:p-10 shadow-2xl shadow-indigo-950/90 text-white space-y-6">
                        
                        <!-- Slot Component Content (Login / Register Form) -->
                        {{ $slot }}

                    </div>
                </div>

            </div>

        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
