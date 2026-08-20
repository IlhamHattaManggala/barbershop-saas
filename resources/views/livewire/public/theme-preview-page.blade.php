<div class="relative w-full min-h-screen">
    <!-- Floating Preview Info Banner at Top -->
    <div class="bg-indigo-950 border-b border-indigo-800 text-white px-4 py-2.5 text-xs font-semibold sticky top-0 z-50 shadow-md">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase bg-amber-500 text-zinc-950 rounded-md">PRATINJAU TEMA</span>
                <span class="font-bold text-sm text-amber-400">{{ $theme->name }}</span>
                <span class="text-zinc-400 text-[11px]">({{ strtoupper($theme->type) }} {{ $theme->price > 0 ? '• Rp ' . number_format($theme->price, 0, ',', '.') : '' }})</span>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-[11px] text-indigo-300 hidden md:inline">Mode Pratinjau Demo Standalone (Tanpa Login Tenant)</span>
                @auth
                    <a href="{{ route('owner.theme.edit') }}" class="px-3 py-1 bg-white text-zinc-900 font-extrabold text-[11px] rounded-lg shadow hover:bg-zinc-100 transition">
                        Kembali ke Galeri Tema
                    </a>
                @else
                    <a href="{{ route('home') }}" class="px-3 py-1 bg-white text-zinc-900 font-extrabold text-[11px] rounded-lg shadow hover:bg-zinc-100 transition">
                        Beranda Utama
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Render Selected Theme View -->
    @include($viewPath, [
        'tenant' => $tenant,
        'services' => $services,
        'barbers' => $barbers,
        'products' => $products,
        'booking_success' => $booking_success,
        'created_reservation_code' => $created_reservation_code,
    ])
</div>
