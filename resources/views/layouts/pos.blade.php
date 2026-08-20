<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Mesin Kasir POS' }} - {{ auth()->user()->tenant->name ?? 'BarberSaaS' }}</title>

    <meta name="theme-color" content="#4f46e5" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="BarberSaaS POS" />

    <link rel="icon" type="image/webp" href="{{ asset('images/logos/Logo-BaberSaaS.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .font-mono-code {
            font-family: 'JetBrains Mono', monospace;
        }
        /* Custom clean scrollbars for POS cashier */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body x-data="{ 
    isFullscreen: false,
    currentTime: '',
    currentDate: '',
    initClock() {
        const update = () => {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
            this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
        };
        update();
        setInterval(update, 1000);
    },
    toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().then(() => { this.isFullscreen = true; }).catch(err => console.log(err));
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen().then(() => { this.isFullscreen = false; }).catch(err => console.log(err));
            }
        }
    }
}" x-init="
    initClock();
    document.addEventListener('fullscreenchange', () => { isFullscreen = !!document.fullscreenElement; });
" class="min-h-screen bg-slate-50 text-slate-900 flex flex-col justify-between selection:bg-indigo-500 selection:text-white antialiased overflow-x-hidden">

    <!-- POS Dedicated Clean White Kiosk Header -->
    <header class="bg-white border-b border-slate-200 px-4 sm:px-6 py-2.5 flex items-center justify-between gap-4 sticky top-0 z-50 shadow-xs flex-wrap sm:flex-nowrap">
        <!-- Outlet Info -->
        <div class="flex items-center gap-3 min-w-0">
            @php
                $tenant = auth()->user()->tenant;
                $logo = $tenant && $tenant->logo ? asset($tenant->logo) : asset('images/logos/Logo-BaberSaaS.webp');
            @endphp
            <img src="{{ $logo }}" alt="Logo" class="w-9 h-9 object-contain rounded-lg border border-slate-200 bg-white p-1 flex-shrink-0 shadow-2xs" />
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <span class="font-extrabold text-sm sm:text-base text-slate-900 font-heading tracking-wide uppercase truncate">{{ $tenant->name ?? 'BarberSaaS' }}</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-md uppercase tracking-wider hidden md:inline-block">Mesin Kasir POS</span>
                </div>
                <div class="text-[11px] text-slate-500 truncate flex items-center gap-2">
                    <span>Kasir: <strong class="text-slate-800">{{ auth()->user()->name }}</strong> ({{ strtoupper(auth()->user()->role) }})</span>
                </div>
            </div>
        </div>

        <!-- Center Live Clock -->
        <div class="hidden lg:flex items-center gap-3 px-4 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-xs font-mono-code shadow-2xs">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-slate-600 font-bold" x-text="currentDate"></span>
            <span class="text-indigo-600 font-extrabold text-sm" x-text="currentTime"></span>
        </div>

        <!-- Action Tools: Fullscreen & Dashboard Exit -->
        <div class="flex items-center gap-2 flex-shrink-0">
            <!-- Fullscreen Toggle Button -->
            <button type="button" @click="toggleFullscreen()" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-xl text-xs font-extrabold text-white transition shadow-sm flex items-center gap-2">
                <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                <svg x-show="isFullscreen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9L4 4m0 0l5 0m-5 0l0 5m11 4l5 5m0 0l-5 0m5 0l0-5m-11 0l-5 5m0 0l5 0m-5 0l0-5"/></svg>
                <span x-text="isFullscreen ? 'Keluar Fullscreen' : 'Layar Penuh (Fullscreen POS)'"></span>
            </button>

            <!-- Dashboard Exit Link (For Owner/Admin) -->
            @if(in_array(auth()->user()->role, ['owner', 'superadmin']))
                <a href="{{ route('owner.dashboard') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 rounded-xl text-xs font-bold border border-slate-200 transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span class="hidden sm:inline">Dashboard</span>
                </a>
            @endif
        </div>
    </header>

    <!-- Full Width POS Main Workspace Content (Clean Bright White Canvas) -->
    <main class="flex-1 w-full p-3 sm:p-5 flex flex-col justify-between overflow-y-auto bg-slate-50">
        {{ $slot }}
    </main>



    @fluxScripts
    @livewireScripts

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('PWA ServiceWorker ready:', reg.scope))
                    .catch(err => console.log('PWA ServiceWorker status:', err));
            });
        }
    </script>
</body>
</html>
