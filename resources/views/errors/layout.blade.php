<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') &mdash; {{ config('app.name', 'BarberSaaS') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 font-sans antialiased flex flex-col justify-between selection:bg-indigo-500 selection:text-white">
    <!-- Header Minimal -->
    <header class="p-6 flex items-center justify-between max-w-7xl w-full mx-auto">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 rounded-xl flex items-center justify-center font-black text-base shadow-sm group-hover:scale-105 transition-transform">
                B
            </div>
            <span class="font-black text-lg tracking-tight text-zinc-900 dark:text-white">BarberSaaS</span>
        </a>

        @if(auth()->check())
            <a href="{{ route('dashboard') }}" class="text-xs font-bold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">
                &larr; Kembali ke Dashboard
            </a>
        @else
            <a href="{{ route('home') }}" class="text-xs font-bold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">
                &larr; Beranda Utama
            </a>
        @endif
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6">
        @yield('content')
    </main>

    <!-- Footer Minimal -->
    <footer class="p-6 text-center text-xs text-zinc-400 dark:text-zinc-600 font-medium">
        &copy; {{ date('Y') }} {{ config('app.name', 'BarberSaaS') }}. All rights reserved.
    </footer>
</body>
</html>
