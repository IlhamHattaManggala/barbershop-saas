<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="theme-color" content="#4f46e5" />
<meta name="mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<meta name="apple-mobile-web-app-title" content="BarberSaaS" />

@php
    $siteName = \App\Models\SiteSetting::get('app_name', 'BarberSaaS');
    $siteFavicon = asset(\App\Models\SiteSetting::get('app_favicon', 'images/logos/Logo-BaberSaaS.webp'));
@endphp

<title>
    {{ filled($title ?? null) ? $title.' - '.$siteName : $siteName }}
</title>

<link rel="icon" type="image/webp" href="{{ $siteFavicon }}">
<link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
<link rel="manifest" href="{{ asset('manifest.json') }}">

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
<script>
    if (!window.localStorage.getItem('flux.appearance')) {
        window.Flux.applyAppearance('light');
    }

    // Register PWA Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('PWA ServiceWorker ready:', reg.scope))
                .catch(err => console.log('PWA ServiceWorker status:', err));
        });
    }
</script>
