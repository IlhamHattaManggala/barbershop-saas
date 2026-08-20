@props([
    'sidebar' => false,
])

@php
    $siteName = \App\Models\SiteSetting::get('app_name', 'BarberSaaS');
    $siteLogo = asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp'));
@endphp

<a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-1 py-1" wire:navigate>
    <img src="{{ $siteLogo }}" alt="{{ $siteName }} Logo" class="w-8 h-8 object-contain rounded-lg shadow-xs" />
    <span class="font-heading font-extrabold text-lg text-slate-900 tracking-tight">{{ $siteName }}</span>
</a>
