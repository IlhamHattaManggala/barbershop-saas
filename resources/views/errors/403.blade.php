@extends('errors.layout')

@section('title', '403 — Akses Ditolak')

@section('content')
<div class="max-w-md w-full text-center space-y-6">
    <div class="space-y-3">
        <div class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 font-mono text-xs font-bold border border-amber-300 dark:border-amber-800">
            HTTP Status 403 &bull; Forbidden
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-zinc-950 dark:text-white">
            Akses Ditolak
        </h1>
        <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto leading-relaxed">
            Anda tidak memiliki izin atau wewenang role untuk mengakses halaman ini. Silakan hubungi pemilik toko (*Owner*) atau SuperAdmin.
        </p>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
        @if(auth()->check())
            <a 
                href="{{ route('dashboard') }}" 
                class="w-full sm:w-auto px-5 py-2.5 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xl shadow-sm transition text-center cursor-pointer"
            >
                Kembali ke Dashboard
            </a>
        @else
            <a 
                href="{{ route('home') }}" 
                class="w-full sm:w-auto px-5 py-2.5 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xl shadow-sm transition text-center cursor-pointer"
            >
                Kembali ke Beranda
            </a>
        @endif
    </div>
</div>
@endsection
