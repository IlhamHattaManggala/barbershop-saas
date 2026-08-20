@php
    $themeKey = $tenant->theme ?? 'classic-light';
    if (!view()->exists('themes.' . $themeKey)) {
        $themeKey = 'classic-light';
    }
@endphp

@include('themes.' . $themeKey)
