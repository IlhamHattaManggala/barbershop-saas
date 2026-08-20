<div class="flex flex-col h-[calc(100vh-105px)] w-full gap-3 bg-slate-50 text-slate-900 overflow-hidden">
    
    <!-- Alert Success Notification (Floating top) -->
    @if($success_message)
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 flex items-center justify-between shadow-xs shrink-0">
            <div class="flex items-center gap-2 text-xs font-bold">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ $success_message }}</span>
            </div>
            @if($last_transaction)
                <a href="https://wa.me/{{ $last_transaction->customer_phone }}?text=Halo%20{{ urlencode($last_transaction->customer_name) }},%20terima%20kasih%20telah%20berkunjung%20ke%20Gentlemen%20Barber%20Studio.%20Total%20pembayaran:%20Rp%20{{ number_format($last_transaction->total_amount, 0, ',', '.') }}." target="_blank" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold inline-flex items-center gap-1.5 shadow-xs transition">
                    <span>Kirim Struk WA</span>
                </a>
            @endif
        </div>
    @endif

    <!-- Main Dual-Column Fixed Kiosk Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-stretch flex-1 min-h-0">
        
        <!-- LEFT 7 COLS: Catalog Area with Category Filter Tabs & Independent Vertical Scroll -->
        <div class="lg:col-span-7 flex flex-col min-h-0 bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs space-y-3">
            
            <!-- Top Controls: Search Bar & Barber Assignment -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 shrink-0">
                <!-- Search Box -->
                <div class="relative">
                    <input type="text" wire:model.live.debounce.150ms="search" placeholder="🔍 Cari nama layanan atau produk (misal: Haircut, Pomade)..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-500 shadow-2xs" />
                </div>

                <!-- Barber Selector -->
                <div>
                    <select wire:model.live="selected_barber_id" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500 shadow-2xs">
                        <option value="">-- Pilih Barber Pangkas --</option>
                        @foreach($barbers as $b)
                            <option value="{{ $b->id }}">{{ $b->name }} ({{ strtoupper($b->role) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Category Filter Tabs (Quick Filter for 100+ Items) -->
            <div class="flex items-center gap-1.5 border-b border-slate-200 pb-2.5 shrink-0 overflow-x-auto">
                <button type="button" wire:click="setCatalogFilter('all')" class="px-3 py-1.5 rounded-xl text-xs font-extrabold transition shrink-0 {{ $catalog_filter === 'all' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    🛒 Semua Katalog ({{ $services->count() + $products->count() }})
                </button>
                <button type="button" wire:click="setCatalogFilter('services')" class="px-3 py-1.5 rounded-xl text-xs font-extrabold transition shrink-0 {{ $catalog_filter === 'services' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    ✂️ Layanan Pangkas ({{ $services->count() }})
                </button>
                <button type="button" wire:click="setCatalogFilter('products')" class="px-3 py-1.5 rounded-xl text-xs font-extrabold transition shrink-0 {{ $catalog_filter === 'products' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    🧴 Produk Retail & Pomade ({{ $products->count() }})
                </button>
            </div>

            <!-- Independent Scrollable Catalog Container (Scrolls smoothly even with 100+ items) -->
            <div class="flex-1 overflow-y-auto pr-1 space-y-4 min-h-0">
                
                <!-- SECTION 1: SERVICES -->
                @if(in_array($catalog_filter, ['all', 'services']))
                    <div class="space-y-2">
                        <div class="flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs py-1 z-10 border-b border-slate-100">
                            <h2 class="text-xs font-black font-heading text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                <span>✂️</span>
                                <span>Layanan & Pangkas</span>
                            </h2>
                            <span class="text-[10px] font-bold text-slate-400 font-mono">{{ $services->count() }} Item Tersedia</span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                            @forelse($services as $s)
                                <div wire:click="addServiceToCart({{ $s->id }})" class="p-3 rounded-xl bg-slate-50 hover:bg-indigo-50/70 border border-slate-200 hover:border-indigo-500 hover:shadow-md cursor-pointer transition flex flex-col justify-between space-y-2 group">
                                    <div class="space-y-1">
                                        <div class="font-bold text-xs text-slate-900 group-hover:text-indigo-600 transition leading-snug line-clamp-2">{{ $s->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>{{ $s->duration_minutes }} Mnt</span>
                                        </div>
                                    </div>
                                    <div class="pt-1 border-t border-slate-200/80 flex items-center justify-between">
                                        <span class="font-extrabold text-xs text-indigo-600 font-mono">Rp {{ number_format($s->price, 0, ',', '.') }}</span>
                                        <span class="w-5 h-5 rounded-full bg-indigo-100 group-hover:bg-indigo-600 text-indigo-600 group-hover:text-white flex items-center justify-center text-xs font-bold transition">+</span>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 sm:col-span-3 text-center py-6 text-xs text-slate-400">
                                    Tidak ada layanan ditemukan.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                <!-- SECTION 2: PRODUCTS -->
                @if(in_array($catalog_filter, ['all', 'products']))
                    <div class="space-y-2 pt-2">
                        <div class="flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs py-1 z-10 border-b border-slate-100">
                            <h2 class="text-xs font-black font-heading text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                <span>🧴</span>
                                <span>Produk Retail & Pomade</span>
                            </h2>
                            <span class="text-[10px] font-bold text-slate-400 font-mono">{{ $products->count() }} Item Tersedia</span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                            @forelse($products as $p)
                                <div wire:click="addProductToCart({{ $p->id }})" class="p-3 rounded-xl bg-slate-50 hover:bg-emerald-50/70 border border-slate-200 hover:border-emerald-500 hover:shadow-md cursor-pointer transition flex flex-col justify-between space-y-2 group {{ $p->stock < 1 ? 'opacity-40 pointer-events-none' : '' }}">
                                    <div class="space-y-1">
                                        <div class="font-bold text-xs text-slate-900 group-hover:text-emerald-600 transition leading-snug line-clamp-2">{{ $p->name }}</div>
                                        <div class="text-[10px] flex items-center justify-between font-mono">
                                            <span class="text-slate-400 truncate max-w-[70px]">{{ $p->category }}</span>
                                            <span class="font-bold {{ $p->stock <= $p->min_stock ? 'text-rose-600' : 'text-slate-600' }}">Stok: {{ $p->stock }}</span>
                                        </div>
                                    </div>
                                    <div class="pt-1 border-t border-slate-200/80 flex items-center justify-between">
                                        <span class="font-extrabold text-xs text-emerald-600 font-mono">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                                        <span class="w-5 h-5 rounded-full bg-emerald-100 group-hover:bg-emerald-600 text-emerald-600 group-hover:text-white flex items-center justify-center text-xs font-bold transition">+</span>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 sm:col-span-3 text-center py-6 text-xs text-slate-400">
                                    Tidak ada produk retail ditemukan.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

            </div>

        </div>

        <!-- RIGHT 5 COLS: Fixed Cart & Checkout Drawer (Stays Always Locked Visible on Screen) -->
        <div class="lg:col-span-5 flex flex-col min-h-0 bg-white rounded-2xl border border-slate-200 p-4 shadow-sm space-y-3">
            
            <!-- Cart Header -->
            <div class="flex items-center justify-between pb-2.5 border-b border-slate-200 shrink-0">
                <h2 class="text-sm font-extrabold font-heading text-slate-900 uppercase flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Keranjang Transaksi</span>
                </h2>
                <button wire:click="clearCart" class="text-xs font-bold text-rose-600 hover:underline">
                    Kosongkan
                </button>
            </div>

            <!-- Customer Details Inputs -->
            <div class="grid grid-cols-2 gap-2 text-xs shrink-0">
                <div>
                    <label class="block font-bold text-slate-600 mb-1">Nama Pelanggan</label>
                    <input type="text" wire:model="customer_name" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-1.5 text-xs text-slate-900 font-bold focus:outline-none focus:border-indigo-500 shadow-2xs" />
                </div>
                <div>
                    <label class="block font-bold text-slate-600 mb-1">WhatsApp</label>
                    <input type="text" wire:model="customer_phone" placeholder="0812..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-1.5 text-xs text-slate-900 font-bold focus:outline-none focus:border-indigo-500 shadow-2xs" />
                </div>
            </div>

            <!-- Scrollable Cart Items Drawer -->
            <div class="flex-1 overflow-y-auto space-y-2 pr-1 min-h-0 border-y border-slate-100 py-2">
                @forelse($cart as $key => $item)
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs shadow-2xs">
                        <div class="space-y-0.5 max-w-[130px]">
                            <div class="font-bold text-slate-900 truncate leading-snug">{{ $item['name'] }}</div>
                            <div class="text-[10px] text-slate-500 font-mono">@ Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden bg-white shadow-2xs">
                                <button wire:click="updateQty('{{ $key }}', {{ $item['qty'] - 1 }})" class="px-2 py-0.5 font-bold hover:bg-slate-100 text-slate-700">-</button>
                                <span class="px-2 py-0.5 text-xs font-extrabold text-slate-900">{{ $item['qty'] }}</span>
                                <button wire:click="updateQty('{{ $key }}', {{ $item['qty'] + 1 }})" class="px-2 py-0.5 font-bold hover:bg-slate-100 text-slate-700">+</button>
                            </div>

                            <span class="font-extrabold text-xs text-slate-900 w-16 text-right font-mono">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-xs text-slate-400 font-medium space-y-1">
                        <div>Keranjang belanja kosong.</div>
                        <div class="text-[11px] text-slate-400">Klik item di katalog sebelah kiri untuk memilih.</div>
                    </div>
                @endforelse
            </div>

            <!-- Payment Breakdown Summary & Checkout Trigger -->
            <div class="space-y-2 shrink-0 pt-1 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span class="font-semibold">Subtotal</span>
                    <span class="font-extrabold text-slate-900 font-mono">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <div class="flex items-center justify-between text-slate-600">
                    <span class="font-semibold">Metode Pembayaran</span>
                    <select wire:model.live="payment_method" class="bg-slate-50 border border-slate-300 rounded-lg px-2.5 py-1 text-xs font-bold text-slate-900 focus:outline-none">
                        <option value="cash">TUNAI (CASH)</option>
                        <option value="qris">QRIS / EDC</option>
                        <option value="transfer">TRANSFER BANK</option>
                    </select>
                </div>

                @if($payment_method === 'cash')
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="font-semibold">Uang Diterima (Rp)</span>
                        <input type="number" wire:model.live="cash_paid" class="w-32 bg-slate-50 border border-slate-300 rounded-lg px-2.5 py-1 text-xs font-extrabold text-right text-slate-900 focus:outline-none" />
                    </div>

                    <div class="flex justify-between text-emerald-700 font-extrabold bg-emerald-50 p-2 rounded-xl border border-emerald-200">
                        <span>Kembalian</span>
                        <span class="font-mono">Rp {{ number_format(max(0, $cash_paid - $totalAmount), 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="flex justify-between text-base font-black text-indigo-700 pt-1.5 border-t border-slate-200">
                    <span>TOTAL BAYAR</span>
                    <span class="font-mono">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                </div>

                <button wire:click="checkout" {{ empty($cart) ? 'disabled' : '' }} class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-200 disabled:text-slate-400 text-white font-extrabold text-xs rounded-xl shadow-md transition uppercase tracking-wider">
                    Bayar & Selesaikan Transaksi
                </button>
            </div>

        </div>

    </div>

</div>
