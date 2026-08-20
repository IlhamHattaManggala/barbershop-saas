<div class="min-h-[85vh] flex items-center justify-center p-4 sm:p-6 font-sans">
    <style>
        @media print {
            body { background: white !important; color: black !important; padding: 0 !important; margin: 0 !important; }
            header, nav, sidebar, flux-sidebar, [data-flux-sidebar], button, footer, .no-print { display: none !important; }
            .print-only-container { width: 100% !important; max-width: 100% !important; border: 1px solid #000 !important; box-shadow: none !important; margin: 0 !important; padding: 20px !important; }
        }
    </style>

    <div class="max-w-md w-full space-y-6 print-only-container">
        <!-- Success Card Ticket -->
        <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-xl overflow-hidden text-zinc-900 dark:text-white">
            
            <!-- Top Status Banner -->
            <div class="bg-emerald-600 dark:bg-emerald-700 p-6 text-white text-center space-y-2">
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto backdrop-blur-xs">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h1 class="text-xl font-extrabold tracking-tight">Booking Berhasil Terkonfirmasi!</h1>
                <p class="text-xs text-emerald-100 font-medium">Terima kasih, jadwal pangkas Anda telah tercatat secara resmi.</p>
            </div>

            <!-- Main Details Content -->
            <div class="p-6 space-y-5">
                <!-- Tenant Header -->
                <div class="flex items-center justify-between pb-4 border-b border-zinc-100 dark:border-zinc-800">
                    <div class="flex items-center gap-3">
                        @if($tenant->logo)
                            <img src="{{ asset($tenant->logo) }}" alt="{{ $tenant->name }}" class="w-10 h-10 object-cover rounded-xl border border-zinc-200" />
                        @else
                            <div class="w-10 h-10 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 rounded-xl flex items-center justify-center font-black text-sm">
                                {{ strtoupper(substr($tenant->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <h2 class="font-bold text-sm leading-tight">{{ $tenant->name }}</h2>
                            <p class="text-[11px] text-zinc-400 font-medium">{{ $tenant->phone ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider">Kode Booking</div>
                        <div class="font-mono font-extrabold text-sm text-indigo-600 dark:text-indigo-400">{{ $reservation->reservation_code }}</div>
                    </div>
                </div>

                <!-- Ticket Details List -->
                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400 font-medium">Nama Pelanggan</span>
                        <span class="font-bold text-right">{{ $reservation->customer_name }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400 font-medium">Nomor WhatsApp</span>
                        <span class="font-bold font-mono text-right">{{ $reservation->customer_phone }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400 font-medium">Layanan Pangkas</span>
                        <span class="font-bold text-right text-indigo-600 dark:text-indigo-400">{{ $reservation->service?->name ?? 'Pangkas Rambut' }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400 font-medium">Barber Specialist</span>
                        <span class="font-bold text-right">{{ $reservation->barber?->name ?? 'Bebas (Siapa Saja Ready)' }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400 font-medium">Tanggal & Jam Cukur</span>
                        <span class="font-bold text-right font-mono text-emerald-600 dark:text-emerald-400">
                            {{ date('d/m/Y', strtotime($reservation->reservation_date)) }} ({{ substr($reservation->start_time, 0, 5) }} WIB)
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400 font-medium">Biaya Layanan</span>
                        <span class="font-extrabold font-mono text-right text-sm">
                            Rp {{ number_format($reservation->service?->price ?? 0, 0, ',', '.') }}
                        </span>
                    </div>

                    @if($reservation->notes)
                        <div class="p-3 rounded-2xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/40 text-[11px]">
                            <span class="font-bold text-amber-800 dark:text-amber-400 block mb-0.5">Catatan Khusus:</span>
                            <span class="text-amber-700 dark:text-amber-300">{{ $reservation->notes }}</span>
                        </div>
                    @endif
                </div>

                <!-- Footer Location Info -->
                <div class="pt-2 text-center text-[11px] text-zinc-400 space-y-0.5">
                    <p class="font-semibold text-zinc-600 dark:text-zinc-300">{{ $tenant->address ?? 'Alamat Outlet Barbershop' }}</p>
                    <p>Harap datang 5-10 menit sebelum jam pangkas yang dijadwalkan.</p>
                </div>
            </div>

            <!-- Bottom Action Buttons -->
            <div class="p-5 bg-zinc-50 dark:bg-zinc-800/40 border-t border-zinc-100 dark:border-zinc-800 flex flex-col sm:flex-row gap-2 no-print">
                <button 
                    type="button" 
                    onclick="window.print()" 
                    class="flex-1 py-2.5 px-4 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xl transition shadow-2xs cursor-pointer flex items-center justify-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Tiket / Struk</span>
                </button>

                <a 
                    href="{{ route('tenant.public', ['slug' => $tenant->slug]) }}" 
                    class="flex-1 py-2.5 px-4 bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 border border-zinc-200 dark:border-zinc-700 font-bold text-xs rounded-xl transition cursor-pointer text-center flex items-center justify-center gap-1.5"
                >
                    <span>Kembali ke Website Toko</span>
                </a>
            </div>

        </div>
    </div>
</div>
