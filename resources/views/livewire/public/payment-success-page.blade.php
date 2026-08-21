<div class="min-h-[85vh] flex items-center justify-center p-4 sm:p-6 font-sans">
    <div class="max-w-md w-full space-y-6">
        <!-- Success Card Ticket -->
        <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-xl overflow-hidden text-zinc-900 dark:text-white">
            
            <!-- Top Status Banner -->
            <div class="bg-emerald-600 dark:bg-emerald-700 p-6 text-white text-center space-y-2">
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto backdrop-blur-xs">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h1 class="text-xl font-extrabold tracking-tight">Pembayaran Tema Berhasil!</h1>
                <p class="text-xs text-emerald-100 font-medium">Transaksi via Pakasir Payment Gateway telah terverifikasi lunas.</p>
            </div>

            <!-- Main Details Content -->
            <div class="p-6 space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-zinc-100 dark:border-zinc-800">
                    <div>
                        <div class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider">Metode Pembayaran</div>
                        <div class="font-bold text-sm text-indigo-600 dark:text-indigo-400">Pakasir Payment Gateway</div>
                    </div>

                    <div class="text-right">
                        <div class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider">Status Transaksi</div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                            TERKONFIRMASI LUNAS
                        </span>
                    </div>
                </div>

                <!-- Ticket Details List -->
                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400 font-medium">Order ID Transaksi</span>
                        <span class="font-mono font-bold text-right text-zinc-900 dark:text-white truncate max-w-[200px]" title="{{ $order_id }}">{{ $order_id ?: '-' }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400 font-medium">Nama Tema Premium</span>
                        <span class="font-bold text-right text-indigo-600 dark:text-indigo-400">{{ $theme_name }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400 font-medium">Nominal Pembayaran</span>
                        <span class="font-extrabold font-mono text-right text-sm text-emerald-600 dark:text-emerald-400">
                            Rp {{ number_format($amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/40 text-xs text-emerald-800 dark:text-emerald-300">
                        <p class="font-bold mb-0.5">Lisensi Aktivasi Tema:</p>
                        <p class="text-[11px]">{{ $status_message }}</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Action Buttons -->
            <div class="p-5 bg-zinc-50 dark:bg-zinc-800/40 border-t border-zinc-100 dark:border-zinc-800 flex flex-col sm:flex-row gap-2">
                <a 
                    href="{{ route('owner.theme.edit') }}" 
                    class="flex-1 py-2.5 px-4 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xl transition shadow-2xs text-center flex items-center justify-center gap-1.5"
                >
                    <span>Buka Galeri Tema</span>
                </a>

                @if(auth()->check() && auth()->user()->tenant)
                    <a 
                        href="{{ route('tenant.public', ['slug' => auth()->user()->tenant->slug]) }}" 
                        target="_blank"
                        class="flex-1 py-2.5 px-4 bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 border border-zinc-200 dark:border-zinc-700 font-bold text-xs rounded-xl transition text-center flex items-center justify-center gap-1.5"
                    >
                        <span>Lihat Website Toko</span>
                    </a>
                @endif
            </div>

        </div>
    </div>
</div>
