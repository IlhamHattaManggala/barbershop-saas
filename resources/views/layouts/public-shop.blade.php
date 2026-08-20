<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#4f46e5" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="BarberSaaS" />

    <title>{{ $title ?? 'Portal Reservasi Barbershop' }}</title>

    @php
        $tenantFavicon = isset($tenant) && $tenant->logo ? asset($tenant->logo) : asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
    @endphp
    <link rel="icon" type="image/webp" href="{{ $tenantFavicon }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Outfit', sans-serif; }

        /* Hide scrollbars while retaining full scrollability */
        html, body {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        html::-webkit-scrollbar, body::-webkit-scrollbar, ::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
            width: 0 !important;
            height: 0 !important;
        }
    </style>
</head>
<body class="h-full bg-zinc-950 text-zinc-100 antialiased flex flex-col justify-between">

    {{ $slot }}

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
