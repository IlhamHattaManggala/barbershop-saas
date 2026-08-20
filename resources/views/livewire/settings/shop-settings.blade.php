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
                        <flux:label>Informasi Rekening Bank / Catatan Pembayaran (Opsional)</flux:label>
                        <flux:textarea wire:model="bank_info" rows="2" placeholder="BCA: 1234567890 a/n Gentlemen Barber Studio&#10;Mandiri: 9876543210 a/n Gentlemen Barber Studio" />
                        <flux:description class="mt-0.5">Detail rekening bank ini akan membantu kasir dan pelanggan saat memilih metode Transfer Bank.</flux:description>
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
