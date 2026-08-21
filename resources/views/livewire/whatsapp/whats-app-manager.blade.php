<div class="space-y-6 font-sans">
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Pusat WhatsApp Gateway & Chat Pelanggan</flux:heading>
        </div>

        <!-- Connection Status Badge -->
        <div class="flex items-center gap-2">
            @if($wa_connection_status === 'connected')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>WhatsApp Terhubung (Connected)</span>
                </span>
            @elseif($wa_connection_status === 'qr_ready')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    <span>Siap Scan Barcode QR</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-zinc-200 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                    <span class="w-2 h-2 rounded-full bg-zinc-400"></span>
                    <span>Offline / Belum Terhubung</span>
                </span>
            @endif
        </div>
    </div>

    <!-- Notification Result Banner -->
    @if(!empty($send_result_message))
        <div class="p-3.5 rounded-xl text-xs font-semibold flex items-center justify-between shadow-2xs {{ $send_result_status === 'success' ? 'bg-emerald-50 border border-emerald-200 text-emerald-800 dark:bg-emerald-950/40 dark:border-emerald-900/50 dark:text-emerald-300' : 'bg-rose-50 border border-rose-200 text-rose-800 dark:bg-rose-950/40 dark:border-rose-900/50 dark:text-rose-300' }}">
            <div class="flex items-center gap-2">
                @if($send_result_status === 'success')
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                @else
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @endif
                <span>{{ $send_result_message }}</span>
            </div>
            <button type="button" wire:click="$set('send_result_message', '')" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- Sub-navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-zinc-200 dark:border-zinc-800 pb-2">
        <button 
            type="button" 
            wire:click="$set('active_tab', 'chat')" 
            class="px-4 py-2 text-xs font-bold rounded-xl transition cursor-pointer flex items-center gap-2 {{ $active_tab === 'chat' ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900 shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <span>Kirim Pesan Pelanggan</span>
        </button>

        <button 
            type="button" 
            wire:click="$set('active_tab', 'settings')" 
            class="px-4 py-2 text-xs font-bold rounded-xl transition cursor-pointer flex items-center gap-2 {{ $active_tab === 'settings' ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900 shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 14v1m8-8h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <span>Scan Barcode QR & Server Gateway</span>
        </button>
    </div>

    <!-- TAB 1: KIRIM PESAN PELANGGAN -->
    @if($active_tab === 'chat')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Kirim Pesan (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <flux:card class="p-6 border border-zinc-200 dark:border-zinc-800 space-y-5">
                    <h3 class="font-extrabold text-sm text-zinc-900 dark:text-white border-b border-zinc-100 dark:border-zinc-800 pb-3">Form Kirim Pesan WhatsApp Instan</h3>
                    
                    <form wire:submit.prevent="sendDirectMessage" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <flux:label>Nomor WhatsApp Pelanggan</flux:label>
                                <flux:input wire:model="target_phone" placeholder="Contoh: 08123456789" required />
                                <flux:description class="mt-1">Nomor WA diawali 08 atau 62.</flux:description>
                            </div>

                            <div>
                                <flux:label>Nama Pelanggan (Opsional)</flux:label>
                                <flux:input wire:model="customer_name" placeholder="Contoh: Doni Setiawan" />
                                <flux:description class="mt-1">Untuk dipasangkan pada template pesan.</flux:description>
                            </div>
                        </div>

                        <!-- Template Presets Buttons -->
                        <div class="space-y-2 pt-1">
                            <flux:label class="font-semibold text-xs">Pilih Template Pesan Siap Pakai:</flux:label>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" wire:click="applyTemplate('reminder')" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold text-xs rounded-xl border border-indigo-200 dark:border-indigo-800 transition cursor-pointer">
                                    Pengingat Jam Cukur
                                </button>
                                <button type="button" wire:click="applyTemplate('thank_you')" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 font-bold text-xs rounded-xl border border-emerald-200 dark:border-emerald-800 transition cursor-pointer">
                                    Ucapan Terima Kasih
                                </button>
                                <button type="button" wire:click="applyTemplate('promo')" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 font-bold text-xs rounded-xl border border-amber-200 dark:border-amber-800 transition cursor-pointer">
                                    Promo Pangkas Rambut
                                </button>
                            </div>
                        </div>

                        <div>
                            <flux:label>Isi Pesan Teks WhatsApp</flux:label>
                            <flux:textarea wire:model="custom_message" rows="5" placeholder="Tuliskan pesan teks yang ingin dikirimkan ke WhatsApp pelanggan..." required />
                        </div>

                        <div class="pt-2 flex justify-end">
                            <flux:button type="submit" variant="primary" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow cursor-pointer flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span>Kirim Pesan WhatsApp Sekarang</span>
                            </flux:button>
                        </div>
                    </form>
                </flux:card>
            </div>

            <!-- Customer Picker Column (5 cols) -->
            <div class="lg:col-span-5 space-y-4">
                <flux:card class="p-6 border border-zinc-200 dark:border-zinc-800 space-y-4">
                    <h3 class="font-extrabold text-sm text-zinc-900 dark:text-white border-b border-zinc-100 dark:border-zinc-800 pb-3">Pilih Pelanggan Terbaru (1-Klik)</h3>
                    
                    <!-- Customer List from Recent Reservations -->
                    <div class="space-y-2 max-h-[400px] overflow-y-auto pr-1">
                        @if($recentReservations->isNotEmpty())
                            <div class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">Dari Papan Reservasi</div>
                            @foreach($recentReservations as $res)
                                <div wire:click="selectCustomer('{{ $res->customer_phone }}', '{{ $res->customer_name }}')" 
                                     class="p-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 hover:bg-indigo-50 dark:bg-zinc-800/40 dark:hover:bg-zinc-800 transition cursor-pointer flex items-center justify-between text-xs">
                                    <div>
                                        <div class="font-bold text-zinc-900 dark:text-white">{{ $res->customer_name }}</div>
                                        <div class="text-[11px] font-mono text-zinc-500">{{ $res->customer_phone }}</div>
                                    </div>
                                    <span class="px-2 py-0.5 text-[10px] font-mono font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-300 rounded-md">
                                        {{ $res->reservation_code }}
                                    </span>
                                </div>
                            @endforeach
                        @endif

                        @if($recentTransactions->isNotEmpty())
                            <div class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 pt-2">Dari Nota Kasir POS</div>
                            @foreach($recentTransactions as $trx)
                                <div wire:click="selectCustomer('{{ $trx->customer_phone }}', '{{ $trx->customer_name }}')" 
                                     class="p-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 hover:bg-indigo-50 dark:bg-zinc-800/40 dark:hover:bg-zinc-800 transition cursor-pointer flex items-center justify-between text-xs">
                                    <div>
                                        <div class="font-bold text-zinc-900 dark:text-white">{{ $trx->customer_name ?: 'Pelanggan Umum' }}</div>
                                        <div class="text-[11px] font-mono text-zinc-500">{{ $trx->customer_phone }}</div>
                                    </div>
                                    <span class="px-2 py-0.5 text-[10px] font-mono font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 rounded-md">
                                        {{ $trx->transaction_number }}
                                    </span>
                                </div>
                            @endforeach
                        @endif

                        @if($recentReservations->isEmpty() && $recentTransactions->isEmpty())
                            <div class="p-4 text-center text-xs text-zinc-400 border border-dashed border-zinc-200 dark:border-zinc-800 rounded-xl">
                                Belum ada data transaksi/reservasi pelanggan.
                            </div>
                        @endif
                    </div>
                </flux:card>
            </div>
        </div>
    @endif

    <!-- TAB 2: SCAN BARCODE QR & PENGATURAN SERVER GATEWAY -->
    @if($active_tab === 'settings')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Server Config Form (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <flux:card class="p-6 border border-zinc-200 dark:border-zinc-800 space-y-5">
                    <h3 class="font-extrabold text-sm text-zinc-900 dark:text-white border-b border-zinc-100 dark:border-zinc-800 pb-3">Konfigurasi Server Baileys WhatsApp Gateway</h3>

                    <form wire:submit.prevent="saveGatewaySettings" class="space-y-4">
                        <label class="flex items-center gap-3 cursor-pointer p-3.5 rounded-2xl bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-900/50">
                            <input type="checkbox" wire:model="wa_enabled" class="rounded border-zinc-300 text-indigo-600 focus:ring-indigo-600 w-4 h-4" />
                            <div>
                                <span class="font-bold text-zinc-900 dark:text-white text-xs block">Aktifkan Notifikasi Konfirmasi Booking via WhatsApp</span>
                                <span class="text-[11px] text-indigo-700 dark:text-indigo-300">Centang opsi ini agar notifikasi WA dikirim otomatis ke pelanggan setiap booking baru dibuat.</span>
                            </div>
                        </label>

                        <div>
                            <flux:label>URL Endpoint WA Gateway (Baileys / Custom)</flux:label>
                            <flux:input wire:model="wa_gateway_url" placeholder="http://localhost:3000/send-message" required />
                            <flux:description class="mt-1">Endpoint HTTP POST server Baileys / WhatsApp Gateway pengirim pesan.</flux:description>
                        </div>

                        <div>
                            <flux:label>API Key / Secret Token (Opsional)</flux:label>
                            <flux:input type="password" wire:model="wa_api_key" placeholder="Masukkan Secret Key jika ada" />
                            <flux:description class="mt-1">Authorization token header untuk keamanan request gateway.</flux:description>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <flux:button type="submit" variant="primary" class="bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xl shadow cursor-pointer">
                                Simpan Pengaturan Gateway
                            </flux:button>
                        </div>
                    </form>
                </flux:card>
            </div>

            <!-- QR Code Scanner Box Column (5 cols) -->
            <div class="lg:col-span-5 space-y-4">
                <flux:card class="p-6 border border-zinc-200 dark:border-zinc-800 space-y-4 text-center">
                    <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                        <h3 class="font-extrabold text-xs text-zinc-900 dark:text-white text-left">Barcode QR Sesi Baileys</h3>
                        <button 
                            type="button" 
                            wire:click="checkBaileysQrStatus" 
                            class="px-2.5 py-1 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-[11px] rounded-lg transition cursor-pointer flex items-center gap-1 shrink-0"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Refresh QR</span>
                        </button>
                    </div>

                    @if($wa_connection_status === 'connected')
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl space-y-2">
                            <div class="w-12 h-12 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold mx-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h4 class="font-extrabold text-sm text-emerald-900 dark:text-emerald-300">Sesi WhatsApp Terhubung!</h4>
                            <p class="text-xs text-emerald-700 dark:text-emerald-400">{{ $wa_status_message }}</p>
                        </div>
                    @elseif($wa_connection_status === 'qr_ready' && $wa_qr_code)
                        <div class="space-y-3">
                            <div class="bg-white p-3 rounded-2xl inline-block shadow-md border border-zinc-300 mx-auto">
                                @if(str_starts_with($wa_qr_code, 'data:image') || str_starts_with($wa_qr_code, 'http'))
                                    <img src="{{ $wa_qr_code }}" alt="Baileys WhatsApp QR" class="w-52 h-52 object-contain mx-auto" />
                                @else
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($wa_qr_code) }}" alt="Baileys WhatsApp QR" class="w-52 h-52 object-contain mx-auto" />
                                @endif
                            </div>
                            <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400">Buka WhatsApp di HP &gt; Perangkat Tertaut &gt; Tautkan Perangkat</p>
                        </div>
                    @else
                        <div class="p-4 bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-2xl text-xs text-zinc-500 space-y-1">
                            <p class="font-bold text-zinc-800 dark:text-zinc-200">Status Server Gateway Offline</p>
                            <p class="text-[11px]">{{ $wa_status_message }}</p>
                        </div>
                    @endif
                </flux:card>
            </div>
        </div>
    @endif
</div>
