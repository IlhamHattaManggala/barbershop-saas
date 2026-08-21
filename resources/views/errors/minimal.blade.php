@extends('errors.layout')

@section('title')
    @yield('code', 'Error') &mdash; @yield('message', 'Terjadi Kendala')
@endsection

@section('content')
<div class="max-w-md w-full text-center space-y-6">
    <div class="space-y-3">
        <div class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-zinc-200/70 dark:bg-zinc-800/80 text-zinc-800 dark:text-zinc-200 font-mono text-xs font-bold border border-zinc-300 dark:border-zinc-700">
            HTTP Status @yield('code', 'Error')
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-zinc-950 dark:text-white">
            @yield('message', 'Terjadi Kendala Teknis')
        </h1>
    </div>

    <div class="flex items-center justify-center gap-3 pt-2">
        <a 
            href="{{ route('home') }}" 
            class="px-5 py-2.5 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xl shadow-sm transition text-center cursor-pointer"
        >
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
