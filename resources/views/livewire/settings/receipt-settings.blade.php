<div class="space-y-6">
    <!-- Header -->
    <div>
        <flux:heading size="xl" level="1">Pengaturan Struk Kasir POS Thermal</flux:heading>

    </div>

    @if(!empty($success_message))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ $success_message }}</span>
            </div>
            <button type="button" wire:click="$set('success_message', '')" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Settings Form Column (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <flux:card class="p-6 border border-zinc-200 dark:border-zinc-800 space-y-6">
                <form wire:submit.prevent="saveReceiptSettings" class="space-y-5">
                    <div>
                        <flux:label>Ukuran Kertas Printer Thermal</flux:label>
                        <flux:select wire:model.live="receipt_paper_size" class="mt-1">
                            <option value="58mm">58mm (Printer Kasir Mini Bluetooth/USB Default)</option>
                            <option value="80mm">80mm (Printer Kasir Desktop Standar)</option>
                        </flux:select>

                    </div>

                    <div class="space-y-3 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                        <flux:label class="font-bold text-xs">Opsi Tampilan Struk</flux:label>
                        <div class="space-y-2.5 text-xs">
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox" wire:model.live="receipt_show_logo" class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900" />
                                <span class="font-semibold text-zinc-800 dark:text-zinc-200">Tampilkan Logo Outlet di Bagian Atas Struk</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox" wire:model.live="receipt_show_barber" class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900" />
                                <span class="font-semibold text-zinc-800 dark:text-zinc-200">Tampilkan Nama Barber Specialist per Item Pangkas</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-zinc-100 dark:border-zinc-800 space-y-4">
                        <div>
                            <flux:label>Pesan Header Struk (Opsional)</flux:label>
                            <flux:input wire:model.live="receipt_header_text" placeholder="Contoh: Premium Haircut & Gentlemen Grooming Studio" />
                        </div>

                        <div>
                            <flux:label>Pesan Footer Struk (Penutup)</flux:label>
                            <flux:textarea wire:model.live="receipt_footer_text" rows="3" placeholder="Terima kasih atas kunjungan Anda. Harap simpan struk ini sebagai bukti pembayaran resmi." />
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex justify-end">
                        <flux:button type="submit" variant="primary" class="bg-zinc-900 hover:bg-zinc-800 text-white dark:bg-white dark:text-zinc-900 font-bold text-xs rounded-xl shadow cursor-pointer">
                            Simpan Pengaturan Struk
                        </flux:button>
                    </div>
                </form>
            </flux:card>
        </div>

        <!-- Live Thermal Receipt Sandbox Preview Column (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Live Preview Struk Thermal ({{ $receipt_paper_size }})</span>
                <flux:badge color="zinc" size="sm">{{ $receipt_paper_size }}</flux:badge>
            </div>

            <!-- Receipt Card Container -->
            <div class="bg-amber-50/50 dark:bg-zinc-900 p-6 rounded-3xl border border-amber-200/80 dark:border-zinc-800 shadow-sm flex items-center justify-center">
                
                <!-- Receipt Paper Mockup -->
                <div class="bg-white text-zinc-900 font-mono text-[11px] leading-tight p-4 shadow-md border border-zinc-200 rounded-sm space-y-3 transition-all"
                     style="width: {{ $receipt_paper_size === '80mm' ? '280px' : '220px' }};">
                    
                    <!-- Header -->
                    <div class="text-center space-y-1">
                        @if($receipt_show_logo && auth()->user()->tenant && auth()->user()->tenant->logo)
                            <img src="{{ asset(auth()->user()->tenant->logo) }}" alt="Logo" class="w-8 h-8 object-contain mx-auto mb-1" />
                        @endif
                        <div class="font-extrabold uppercase text-xs tracking-tight">{{ auth()->user()->tenant->name ?? 'Gentlemen Barber Studio' }}</div>
                        <div class="text-[10px] text-zinc-600">{{ auth()->user()->tenant->address ?? 'Jl. Sudirman No. 45, Jakarta' }}</div>
                        @if($receipt_header_text)
                            <div class="text-[10px] text-zinc-600 italic">{{ $receipt_header_text }}</div>
                        @endif
                    </div>

                    <div class="border-b border-dashed border-zinc-400 my-1"></div>

                    <!-- Meta info -->
                    <div class="space-y-0.5 text-[10px]">
                        <div class="flex justify-between">
                            <span>Nota: TRX-{{ date('Ymd') }}-001</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Waktu: {{ date('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kasir: Rina Kasir</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pelanggan: Doni Setiawan</span>
                        </div>
                    </div>

                    <div class="border-b border-dashed border-zinc-400 my-1"></div>

                    <!-- Items -->
                    <div class="space-y-2 text-[10px]">
                        <div>
                            <div class="font-bold">Gentleman Haircut & Wash</div>
                            @if($receipt_show_barber)
                                <div class="text-[9px] text-zinc-500">(Barber: Agus Barber)</div>
                            @endif
                            <div class="flex justify-between">
                                <span>1 x 50.000</span>
                                <span class="font-bold">50.000</span>
                            </div>
                        </div>

                        <div>
                            <div class="font-bold">Water-Based Pomade</div>
                            <div class="flex justify-between">
                                <span>1 x 85.000</span>
                                <span class="font-bold">85.000</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-b border-dashed border-zinc-400 my-1"></div>

                    <!-- Totals -->
                    <div class="space-y-0.5 text-[10px]">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>135.000</span>
                        </div>
                        <div class="flex justify-between font-bold text-xs pt-1 border-t border-zinc-300">
                            <span>TOTAL:</span>
                            <span>Rp 135.000</span>
                        </div>
                        <div class="flex justify-between text-[10px] pt-0.5">
                            <span>TUNAI:</span>
                            <span>150.000</span>
                        </div>
                        <div class="flex justify-between text-[10px]">
                            <span>Kembali:</span>
                            <span>15.000</span>
                        </div>
                    </div>

                    <div class="border-b border-dashed border-zinc-400 my-1"></div>

                    <!-- Footer Message -->
                    <div class="text-center text-[9px] text-zinc-600 leading-tight pt-1">
                        {{ $receipt_footer_text }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
