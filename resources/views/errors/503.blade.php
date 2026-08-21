@extends('errors.layout')

@section('title', '503 — Pemeliharaan Sistem')

@section('content')
<div class="max-w-md w-full text-center space-y-6">
    <div class="space-y-3">
        <div class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-indigo-100 dark:bg-indigo-950/60 text-indigo-800 dark:text-indigo-300 font-mono text-xs font-bold border border-indigo-300 dark:border-indigo-800">
            System Maintenance Mode
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-zinc-950 dark:text-white">
            Pemeliharaan Sistem Rutin
        </h1>
        <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto leading-relaxed">
            Platform {{ config('app.name', 'BarberSaaS') }} sedang dalam proses pemeliharaan rutin untuk meningkatkan stabilitas, kecepatan, dan keamanan sistem.
        </p>
    </div>

    <div class="p-4 bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl text-xs text-zinc-600 dark:text-zinc-400 space-y-1">
        <p class="font-bold text-zinc-900 dark:text-zinc-200">Seluruh Data & Reservasi Toko Anda Tetap Aman</p>
        <p class="text-[11px]">Sistem akan segera aktif kembali beberapa saat lagi.</p>
    </div>

    <div class="flex items-center justify-center gap-3 pt-2">
        <button 
            type="button" 
            onclick="window.location.reload()" 
            class="px-6 py-3 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xl shadow-md transition text-center cursor-pointer flex items-center gap-2 mx-auto"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <span>Cek Status Terbaru (Refresh)</span>
        </button>
    </div>
</div>
@endsection
