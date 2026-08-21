@extends('errors.layout')

@section('title', '404 — Halaman Tidak Ditemukan')

@section('content')
<div class="max-w-md w-full text-center space-y-6">
    <div class="space-y-3">
        <div class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-zinc-200/70 dark:bg-zinc-800/80 text-zinc-800 dark:text-zinc-200 font-mono text-xs font-bold border border-zinc-300 dark:border-zinc-700">
            HTTP Status 404
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-zinc-950 dark:text-white">
            Halaman Tidak Ditemukan
        </h1>
        <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto leading-relaxed">
            Halaman atau outlet barbershop yang Anda tuju tidak ditemukan, telah dipindahkan, atau alamat URL yang dimasukkan kurang tepat.
        </p>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
        <a 
            href="{{ route('home') }}" 
            class="w-full sm:w-auto px-5 py-2.5 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xl shadow-sm transition text-center cursor-pointer"
        >
            Kembali ke Beranda
        </a>

        @if(auth()->check())
            <a 
                href="{{ route('dashboard') }}" 
                class="w-full sm:w-auto px-5 py-2.5 bg-white dark:bg-zinc-900 hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border border-zinc-300 dark:border-zinc-700 font-bold text-xs rounded-xl transition text-center cursor-pointer"
            >
                Dashboard Workspace
            </a>
        @endif
    </div>
</div>
@endsection
