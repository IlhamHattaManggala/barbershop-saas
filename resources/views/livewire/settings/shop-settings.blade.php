<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profil Barbershop') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Profil Barbershop')" :subheading="__('Kelola nama outlet barbershop, logo, kontak, alamat, dan deskripsi toko Anda.')">
        <div class="space-y-6">
            @if(!empty($success_message))
                <flux:badge color="emerald" size="lg" class="w-full justify-between p-3">
                    <span>{{ $success_message }}</span>
                </flux:badge>
            @endif

            <form wire:submit.prevent="saveShopSettings" class="space-y-6">
                <div>
                    <flux:label>Nama Barbershop Outlet</flux:label>
                    <flux:input wire:model="name" required placeholder="Contoh: Gentlemen Barber Studio" />
                </div>

                <div>
                    <flux:label>URL Path Barbershop (Slug)</flux:label>
                    <flux:input wire:model="slug" disabled readonly class="bg-zinc-100 dark:bg-zinc-800 font-mono text-indigo-600 font-bold" />
                    <flux:description class="mt-1">Link URL Portal Reservasi Online Pelanggan: <a href="{{ url('/' . ($slug ?? 'gentlemen-barber')) }}" target="_blank" class="font-mono font-bold text-indigo-600 underline">{{ url('/' . ($slug ?? 'gentlemen-barber')) }}</a></flux:description>
                </div>

                <div>
                    <flux:label>Logo Outlet Barbershop</flux:label>
                    <div class="flex items-center gap-4 mt-2">
                        @if(!empty($new_logo))
                            <img src="{{ $new_logo->temporaryUrl() }}" alt="Preview Logo" class="w-12 h-12 object-contain rounded-lg border border-zinc-200 p-1 bg-white" />
                        @elseif(!empty($current_logo))
                            <img src="{{ asset($current_logo) }}" alt="Logo Saat Ini" class="w-12 h-12 object-contain rounded-lg border border-zinc-200 p-1 bg-white" />
                        @else
                            <div class="w-12 h-12 rounded-lg border border-zinc-200 bg-zinc-100 flex items-center justify-center text-xs font-bold text-zinc-400">NO LOGO</div>
                        @endif
                        <div class="flex-1">
                            <input type="file" wire:model="new_logo" accept="image/*" class="text-xs text-zinc-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200" />
                        </div>
                    </div>
                    <flux:description class="mt-1">Logo resmi outlet Anda. Format PNG/WEBP max 2MB.</flux:description>
                </div>

                <div>
                    <flux:label>Nomor Telepon / WhatsApp Outlet</flux:label>
                    <flux:input wire:model="phone" placeholder="081234567890" />
                </div>

                <div>
                    <flux:label>Alamat Lengkap Outlet</flux:label>
                    <flux:textarea wire:model="address" rows="2" placeholder="Jl. Sudirman No. 45, Jakarta Selatan" />
                </div>

                <div>
                    <flux:label>Deskripsi & Slogan Toko (Opsional)</flux:label>
                    <flux:textarea wire:model="description" rows="3" placeholder="Barbershop premium spesialis fade, pompadour, dan perawatan jenggot terlengkap..." />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <flux:label>Persentase Bagi Hasil Barber (%)</flux:label>
                        <div class="relative mt-1">
                            <flux:input type="number" min="0" max="100" wire:model="barber_commission_percentage" placeholder="40" required />
                        </div>
                        <flux:description class="mt-1">Persentase (%) komisi untuk staf barber dari setiap layanan pangkas. Contoh: 40% (Default).</flux:description>
                    </div>

                    <div>
                        <flux:label>Persentase Bagi Hasil Kasir (%)</flux:label>
                        <div class="relative mt-1">
                            <flux:input type="number" min="0" max="100" wire:model="cashier_commission_percentage" placeholder="5" required />
                        </div>
                        <flux:description class="mt-1">Persentase (%) insentif untuk akun kasir/tablet POS dari transaksi. Contoh: 5% (atau 0% jika gaji tetap).</flux:description>
                    </div>
                </div>

                <!-- SECTION UPLOAD BARCODE QRIS OUTLET -->
                <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700 space-y-4">
                    <div>
                        <flux:label class="font-bold text-zinc-900 dark:text-white">Barcode QRIS Pembayaran Outlet</flux:label>
                        <flux:description class="mt-0.5">Upload gambar Barcode QRIS resmi outlet Anda. QRIS ini akan otomatis ditampilkan pada Mesin Kasir POS & Portal Booking Online saat metode bayar QRIS dipilih.</flux:description>

                        <div class="mt-3 flex flex-col sm:flex-row sm:items-center gap-4 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/40">
                            @if(!empty($new_qris_image))
                                <div class="relative group shrink-0">
                                    <img src="{{ $new_qris_image->temporaryUrl() }}" alt="Preview QRIS Baru" class="w-36 h-36 object-contain rounded-xl border border-zinc-300 bg-white p-2 shadow-sm" />
                                    <span class="absolute top-1 left-1 px-2 py-0.5 text-[10px] font-bold bg-indigo-600 text-white rounded-md">File Baru</span>
                                </div>
                            @elseif(!empty($current_qris_image))
                                <div class="relative group shrink-0 text-center">
                                    <img src="{{ asset($current_qris_image) }}" alt="Barcode QRIS Saat Ini" class="w-36 h-36 object-contain rounded-xl border border-zinc-300 bg-white p-2 shadow-sm" />
                                    <button type="button" wire:click="removeQrisImage" class="mt-2 w-full px-2 py-1 text-[11px] font-bold bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-lg transition">
                                        Hapus Barcode
                                    </button>
                                </div>
                            @else
                                <div class="w-36 h-36 rounded-xl border-2 border-dashed border-zinc-300 bg-zinc-100 dark:bg-zinc-800 flex flex-col items-center justify-center text-center p-2 text-zinc-400 shrink-0">
                                    <flux:icon name="qr-code" class="size-8 mb-1 text-zinc-400" />
                                    <span class="text-[11px] font-bold">Belum Ada Barcode</span>
                                </div>
                            @endif

                            <div class="flex-1 space-y-2">
                                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300">Pilih File Gambar Barcode QRIS:</label>
                                <input type="file" wire:model="new_qris_image" accept="image/*" class="w-full text-xs text-zinc-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                <flux:description class="text-[11px]">Format file yang didukung: JPG, PNG, WEBP. Maksimal ukuran file 3MB.</flux:description>
                            </div>
                        </div>
                    </div>

                    <div>
                        <flux:label>Informasi Rekening Bank Outlet (Opsional)</flux:label>
                        <flux:textarea wire:model="bank_info" rows="2" placeholder="BCA: 1234567890 a/n Gentlemen Studio&#10;Mandiri: 0987654321 a/n Budi Santoso" />
                        <flux:description class="mt-1">Disertakan pada instruksi transfer manual.</flux:description>
                    </div>
                </div>

                <!-- SECTION PENGATURAN WHATSAPP GATEWAY (BAILEYS API) -->
                <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700 space-y-4">
                    <div>
                        <flux:label class="font-bold text-zinc-900 dark:text-white">Pengaturan WhatsApp Gateway (Baileys / Fonnte / Custom API)</flux:label>
                        <flux:description class="mt-0.5">Kirimi pelanggan pesan notifikasi WhatsApp konfirmasi booking secara otomatis setelah pemesanan pangkas online dibuat.</flux:description>

                        <div class="mt-3 space-y-4 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/40">
                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-2xs">
                                <input type="checkbox" wire:model="wa_enabled" class="rounded border-zinc-300 text-indigo-600 focus:ring-indigo-600 w-4 h-4" />
                                <div>
                                    <span class="font-bold text-zinc-900 dark:text-white text-xs block">Aktifkan Notifikasi Konfirmasi Booking via WhatsApp</span>
                                    <span class="text-[11px] text-zinc-500">Centang opsi ini jika Anda telah menyambungkan server Baileys / WhatsApp Gateway.</span>
                                </div>
                            </label>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <flux:label>URL Endpoint WA Gateway (Baileys / Custom)</flux:label>
                                    <flux:input wire:model="wa_gateway_url" placeholder="http://localhost:3000/send-message" />
                                    <flux:description class="mt-1">Endpoint HTTP POST server Baileys/Gateway pengirim pesan WhatsApp.</flux:description>
                                </div>

                                <div>
                                    <flux:label>API Key / Secret Token (Opsional)</flux:label>
                                    <flux:input type="password" wire:model="wa_api_key" placeholder="Masukkan Secret Key jika ada" />
                                    <flux:description class="mt-1">Authorization token header untuk keamanan request gateway.</flux:description>
                                </div>
                            </div>

                            <!-- Live Baileys WhatsApp Web QR Code Scan Box -->
                            <div class="p-4 rounded-2xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-xs text-zinc-900 dark:text-white">Status Sesi & Barcode QR WhatsApp (Baileys)</h4>
                                        <p class="text-[11px] text-zinc-500">Pindai (*scan*) QR Code berikut menggunakan aplikasi WhatsApp di HP Anda untuk menghubungkan sesi.</p>
                                    </div>

                                    <button 
                                        type="button" 
                                        wire:click="checkBaileysQrStatus" 
                                        class="px-3 py-1.5 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xl transition cursor-pointer flex items-center gap-1.5 shrink-0"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <span>Cek Status / Muat QR</span>
                                    </button>
                                </div>

                                @if($wa_connection_status === 'connected')
                                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 rounded-xl flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <div>
                                            <span class="font-bold text-xs text-emerald-900 dark:text-emerald-300 block">Sesi WhatsApp Terhubung!</span>
                                            <span class="text-[11px] text-emerald-700 dark:text-emerald-400">{{ $wa_status_message }}</span>
                                        </div>
                                    </div>
                                @elseif($wa_connection_status === 'qr_ready' && $wa_qr_code)
                                    <div class="text-center p-4 bg-zinc-50 dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 space-y-2">
                                        <div class="bg-white p-3 rounded-2xl inline-block shadow-md border border-zinc-300 mx-auto">
                                            @if(str_starts_with($wa_qr_code, 'data:image') || str_starts_with($wa_qr_code, 'http'))
                                                <img src="{{ $wa_qr_code }}" alt="Baileys WhatsApp QR" class="w-48 h-48 object-contain mx-auto" />
                                            @else
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($wa_qr_code) }}" alt="Baileys WhatsApp QR" class="w-48 h-48 object-contain mx-auto" />
                                            @endif
                                        </div>
                                        <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400">Buka WhatsApp di HP &gt; Perangkat Tertaut &gt; Tautkan Perangkat</p>
                                    </div>
                                @else
                                    <div class="p-3 bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-xs text-zinc-600 dark:text-zinc-400">
                                        <p class="font-bold text-zinc-900 dark:text-zinc-200 mb-0.5">Status Koneksi Gateway:</p>
                                        <p class="text-[11px]">{{ $wa_status_message ?: 'Klik tombol "Cek Status / Muat QR" di atas untuk memuat Barcode QR dari server Baileys Gateway Anda.' }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Testing Connection Tool -->
                            <div class="pt-3 border-t border-zinc-200 dark:border-zinc-700/60 space-y-2">
                                <flux:label class="font-bold text-xs">Uji Koneksi Pengiriman Pesan WA</flux:label>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <flux:input wire:model="test_wa_phone" placeholder="08123456789" class="flex-1" />
                                    <button 
                                        type="button" 
                                        wire:click="testWhatsAppConnection" 
                                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-2xs transition cursor-pointer shrink-0"
                                    >
                                        Tes Kirim Pesan WA
                                    </button>
                                </div>

                                @if(!empty($wa_test_result))
                                    <div class="p-3 rounded-xl text-xs font-semibold {{ str_contains($wa_test_result, 'BERHASIL') ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' }}">
                                        {{ $wa_test_result }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION PENGATURAN STRUK KASIR POS THERMAL -->
                <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700 space-y-4">
                    <div>
                        <flux:label class="font-bold text-zinc-900 dark:text-white">Pengaturan Struk Kasir POS (Printer Thermal)</flux:label>
                        <flux:description class="mt-0.5">Atur format ukuran kertas, header, dan footer struk fisik thermal yang dicetak dari Mesin Kasir POS.</flux:description>

                        <div class="mt-3 space-y-4 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/40">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <flux:label>Ukuran Kertas Printer Thermal</flux:label>
                                    <flux:select wire:model="receipt_paper_size" class="mt-1">
                                        <option value="58mm">58mm (Printer Kasir Mini Bluetooth/USB Default)</option>
                                        <option value="80mm">80mm (Printer Kasir Desktop Standar)</option>
                                    </flux:select>
                                    <flux:description class="mt-1">Pilih ukuran lebar kertas thermal yang terpasang pada printer kasir outlet Anda.</flux:description>
                                </div>

                                <div class="space-y-2 pt-1">
                                    <flux:label class="font-semibold text-xs">Opsi Tampilan Struk</flux:label>
                                    <div class="space-y-2 text-xs">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" wire:model="receipt_show_logo" class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900" />
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Tampilkan Logo Outlet di Bagian Atas Struk</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" wire:model="receipt_show_barber" class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900" />
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Tampilkan Nama Barber Specialist per Item Pangkas</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <flux:label>Pesan Header Struk (Opsional)</flux:label>
                                <flux:input wire:model="receipt_header_text" placeholder="Contoh: Premium Haircut & Gentlemen Grooming Studio" />
                            </div>

                            <div>
                                <flux:label>Pesan Footer Struk (Penutup)</flux:label>
                                <flux:textarea wire:model="receipt_footer_text" rows="2" placeholder="Terima kasih atas kunjungan Anda. Harap simpan struk ini sebagai bukti pembayaran resmi." />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-start pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button type="submit" variant="primary">
                        Simpan Profil Barbershop
                    </flux:button>
                </div>
            </form>
        </div>
    </x-pages::settings.layout>
</section>
