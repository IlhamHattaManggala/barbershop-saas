@extends('errors.layout')

@section('title', '500 — Kesalahan Server')

@section('content')
<div class="max-w-md w-full text-center space-y-6">
    <div class="space-y-3">
        <div class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 font-mono text-xs font-bold border border-rose-300 dark:border-rose-800">
            HTTP Status 500 &bull; Server Error
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-zinc-950 dark:text-white">
            Kesalahan Internal Server
        </h1>
        <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto leading-relaxed">
            Terjadi kendala teknis tak terduga pada server kami. Log sistem telah dicatat dan tim pengembang sedang menangani pembaruan ini.
        </p>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
        <button 
            type="button" 
            onclick="window.location.reload()" 
            class="w-full sm:w-auto px-5 py-2.5 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xl shadow-sm transition text-center cursor-pointer"
        >
            Muat Ulang Halaman
        </button>

        <a 
            href="{{ route('home') }}" 
            class="w-full sm:w-auto px-5 py-2.5 bg-white dark:bg-zinc-900 hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border border-zinc-300 dark:border-zinc-700 font-bold text-xs rounded-xl transition text-center cursor-pointer"
        >
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
