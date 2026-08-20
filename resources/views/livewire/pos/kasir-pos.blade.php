<div class="flex flex-col h-[calc(100vh-75px)] w-full gap-3 bg-slate-50 text-slate-900 overflow-hidden">
    
    <!-- Alert Success Notification (Floating top) -->
    @if($success_message)
        <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 flex items-center justify-between shadow-xs shrink-0 animate-in fade-in slide-in-from-top-2 duration-200">
            <div class="flex items-center gap-2 text-xs font-bold">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ $success_message }}</span>
            </div>
            <div class="flex items-center gap-2">
                @if($last_transaction && $last_transaction->customer_phone)
                    <a href="https://wa.me/{{ $last_transaction->customer_phone }}?text=Halo%20{{ urlencode($last_transaction->customer_name) }},%20terima%20kasih%20telah%20berkunjung%20ke%20Gentlemen%20Barber%20Studio.%20Total%20pembayaran:%20Rp%20{{ number_format($last_transaction->total_amount, 0, ',', '.') }}." target="_blank" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold inline-flex items-center gap-1.5 shadow-xs transition">
                        <span>Kirim Struk WA</span>
                    </a>
                @endif
                <button type="button" wire:click="dismissSuccessAlert" class="p-1 rounded-lg hover:bg-emerald-200/60 text-emerald-700 transition" title="Tutup Notifikasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
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
                    <input type="text" wire:model.live.debounce.150ms="search" placeholder="Cari nama layanan atau produk (misal: Haircut, Pomade)..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-500 shadow-2xs" />
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
                    Semua Katalog ({{ $services->count() + $products->count() }})
                </button>
                <button type="button" wire:click="setCatalogFilter('services')" class="px-3 py-1.5 rounded-xl text-xs font-extrabold transition shrink-0 {{ $catalog_filter === 'services' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    Layanan Pangkas ({{ $services->count() }})
                </button>
                <button type="button" wire:click="setCatalogFilter('products')" class="px-3 py-1.5 rounded-xl text-xs font-extrabold transition shrink-0 {{ $catalog_filter === 'products' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    Produk Retail & Pomade ({{ $products->count() }})
                </button>
            </div>

            <!-- Independent Scrollable Catalog Container (Scrolls smoothly even with 100+ items) -->
            <div class="flex-1 overflow-y-auto pr-1 space-y-4 min-h-0">
                
                <!-- SECTION 1: SERVICES -->
                @if(in_array($catalog_filter, ['all', 'services']))
                    <div class="space-y-2">
                        <div class="flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs py-1 z-10 border-b border-slate-100">
                            <h2 class="text-xs font-black font-heading text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
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



            <!-- Scrollable Cart Items Drawer -->
            <div class="flex-1 overflow-y-auto space-y-2 pr-1 min-h-0 border-y border-slate-100 py-2">
                @forelse($cart as $key => $item)
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs shadow-2xs gap-2">
                        <div class="space-y-0.5 flex-1 min-w-0">
                            <div class="font-bold text-slate-900 truncate leading-snug">{{ $item['name'] }}</div>
                            <div class="text-[10px] text-slate-500 font-mono">@ Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden bg-white shadow-2xs shrink-0">
                                <button wire:click="updateQty('{{ $key }}', {{ $item['qty'] - 1 }})" class="px-2 py-0.5 font-bold hover:bg-slate-100 text-slate-700">-</button>
                                <span class="px-2 py-0.5 text-xs font-extrabold text-slate-900">{{ $item['qty'] }}</span>
                                <button wire:click="updateQty('{{ $key }}', {{ $item['qty'] + 1 }})" class="px-2 py-0.5 font-bold hover:bg-slate-100 text-slate-700">+</button>
                            </div>

                            <span class="font-extrabold text-xs text-slate-900 whitespace-nowrap shrink-0 text-right font-mono min-w-[85px]">
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

                @php
                    $tenant = auth()->user()->tenant;
                @endphp

                @if($payment_method === 'cash')
                    <div class="flex items-center justify-between text-slate-600 gap-2">
                        <span class="font-semibold">Uang Diterima</span>
                        <div class="flex items-center gap-1 bg-slate-50 border border-slate-300 rounded-lg px-2.5 py-1 focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 shadow-2xs w-40"
                             x-data="{
                                 display: '',
                                 format(v) {
                                     let n = (v || '').toString().replace(/[^0-9]/g, '');
                                     return n ? new Intl.NumberFormat('id-ID').format(n) : '';
                                 },
                                 init() {
                                     this.display = this.format($wire.cash_paid);
                                     this.$watch('$wire.cash_paid', v => { this.display = this.format(v); });
                                 },
                                 onInput(e) {
                                     let clean = e.target.value.replace(/[^0-9]/g, '');
                                     let num = clean ? parseInt(clean) : 0;
                                     this.display = this.format(clean);
                                     $wire.set('cash_paid', num);
                                 }
                             }">
                            <span class="text-xs font-mono font-extrabold text-slate-400 select-none">Rp</span>
                            <input 
                                type="text" 
                                x-model="display"
                                @input="onInput($event)"
                                placeholder="0" 
                                class="w-full bg-transparent text-xs font-mono font-extrabold text-right text-indigo-700 focus:outline-none" 
                            />
                        </div>
                    </div>

                    <div class="flex justify-between text-emerald-700 font-extrabold bg-emerald-50 p-2 rounded-xl border border-emerald-200">
                        <span>Kembalian</span>
                        <span class="font-mono">Rp {{ number_format(max(0, $cash_paid - $totalAmount), 0, ',', '.') }}</span>
                    </div>
                @elseif($payment_method === 'qris')
                    <div class="p-3 bg-indigo-50/70 border border-indigo-200 rounded-xl space-y-2 text-center">
                        <div class="text-[11px] font-extrabold text-indigo-900 uppercase tracking-wider">Scan Barcode QRIS Outlet</div>
                        @if($tenant && $tenant->qris_image)
                            <div class="bg-white p-2 rounded-xl inline-block border border-indigo-200 shadow-sm mx-auto">
                                <img src="{{ asset($tenant->qris_image) }}" alt="QRIS Outlet" class="w-36 h-36 object-contain mx-auto" />
                            </div>
                        @else
                            <div class="text-[11px] text-amber-700 bg-amber-50 p-2 rounded-lg border border-amber-200">
                                Barcode QRIS belum di-upload di Pengaturan Toko.
                            </div>
                        @endif
                    </div>
                @elseif($payment_method === 'transfer')
                    @if($tenant && $tenant->bank_info)
                        <div class="p-3 bg-blue-50/70 border border-blue-200 rounded-xl space-y-1 text-xs">
                            <div class="font-extrabold text-blue-900 uppercase tracking-wider">Info Rekening Transfer</div>
                            <div class="text-[11px] text-blue-800 whitespace-pre-line font-mono font-medium">{{ $tenant->bank_info }}</div>
                        </div>
                    @endif
                @endif

                <!-- Payment Proof Live Camera Photo Capture (For QRIS & Transfer Bank) -->
                @if(in_array($payment_method, ['qris', 'transfer']))
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-2"
                         x-data="{
                             showCamera: false,
                             stream: null,
                             cameraError: '',
                             async startCamera() {
                                 this.cameraError = '';
                                 this.showCamera = true;
                                 this.$nextTick(async () => {
                                     try {
                                         this.stream = await navigator.mediaDevices.getUserMedia({
                                             video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
                                         });
                                         this.$refs.webcamVideo.srcObject = this.stream;
                                     } catch (err) {
                                         console.error(err);
                                         this.cameraError = 'Tidak dapat mengakses kamera. Pastikan izin kamera aktif di browser Anda.';
                                     }
                                 });
                             },
                             stopCamera() {
                                 if (this.stream) {
                                     this.stream.getTracks().forEach(t => t.stop());
                                     this.stream = null;
                                 }
                                 this.showCamera = false;
                             },
                             capturePhoto() {
                                 let video = this.$refs.webcamVideo;
                                 let canvas = document.createElement('canvas');
                                 canvas.width = video.videoWidth || 640;
                                 canvas.height = video.videoHeight || 480;
                                 let ctx = canvas.getContext('2d');
                                 ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                                 let dataUrl = canvas.toDataURL('image/jpeg', 0.85);
                                 $wire.saveBase64PaymentProof(dataUrl);
                                 this.stopCamera();
                             }
                         }">
                        
                        <div class="flex items-center justify-between text-xs font-bold text-slate-800">
                            <span>Foto Bukti Transfer Customer</span>
                            <span class="text-[10px] text-slate-400 font-normal">(Kamera Live)</span>
                        </div>

                        @if($base64_payment_proof)
                            <div class="relative group p-2 bg-white rounded-lg border border-emerald-300 flex items-center justify-between gap-2 shadow-2xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <img src="{{ $base64_payment_proof }}" alt="Foto Bukti Transfer" class="w-12 h-12 object-cover rounded-md border border-slate-200 shrink-0" />
                                    <div>
                                        <div class="text-[11px] font-bold text-emerald-800">Foto Bukti Terfoto</div>
                                        <div class="text-[10px] text-slate-400">Siap disimpan saat transaksi</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button type="button" @click="startCamera()" class="px-2 py-1 text-[10px] font-bold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-md border border-indigo-200 transition">
                                        Foto Ulang
                                    </button>
                                    <button type="button" wire:click="removePaymentProof" class="px-2 py-1 text-[10px] font-bold bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-md border border-rose-200 transition">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @elseif($payment_proof_photo)
                            <div class="relative group p-2 bg-white rounded-lg border border-emerald-300 flex items-center justify-between gap-2 shadow-2xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <img src="{{ $payment_proof_photo->temporaryUrl() }}" alt="Bukti Transfer" class="w-12 h-12 object-cover rounded-md border border-slate-200 shrink-0" />
                                    <div class="text-[11px] font-bold text-emerald-800">Foto Bukti Attached</div>
                                </div>
                                <button type="button" wire:click="removePaymentProof" class="text-xs text-rose-600 font-bold hover:underline shrink-0">
                                    Hapus
                                </button>
                            </div>
                        @else
                            <button type="button" @click="startCamera()" class="w-full flex items-center justify-center gap-2 p-3 rounded-xl border border-dashed border-indigo-400 bg-indigo-50/70 hover:bg-indigo-100 text-xs font-extrabold text-indigo-700 shadow-2xs transition">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Buka Kamera & Foto Bukti Transfer</span>
                            </button>
                        @endif

                        <!-- LIVE CAMERA WEBRTC MODAL OVERLAY -->
                        <template x-teleport="body">
                            <div x-show="showCamera" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
                                <div class="bg-white rounded-2xl p-5 max-w-md w-full space-y-4 shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-200">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                        <div class="flex items-center gap-2 font-bold text-slate-900 text-sm">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span>Ambil Foto Bukti Pembayaran</span>
                                        </div>
                                        <button type="button" @click="stopCamera()" class="text-slate-400 hover:text-slate-600 font-bold p-1">✕</button>
                                    </div>

                                    <div class="relative bg-slate-900 rounded-xl overflow-hidden aspect-video flex items-center justify-center shadow-inner">
                                        <video x-ref="webcamVideo" autoplay playsinline class="w-full h-full object-cover"></video>

                                        <template x-if="cameraError">
                                            <div class="absolute inset-0 p-4 bg-slate-900/90 text-white flex flex-col items-center justify-center text-center space-y-2">
                                                <p class="text-xs text-rose-400 font-semibold" x-text="cameraError"></p>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 pt-2">
                                        <button type="button" @click="stopCamera()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                                            Batal
                                        </button>
                                        <button type="button" @click="capturePhoto()" class="flex-1 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span>Jepret & Simpan Foto</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>
                @endif

                <!-- Validation Error Display -->
                @if($errors->any())
                    <div class="p-2.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs space-y-1">
                        @foreach($errors->all() as $error)
                            <div class="flex items-center gap-1.5 font-bold">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="flex justify-between text-base font-black text-indigo-700 pt-1.5 border-t border-slate-200">
                    <span>TOTAL BAYAR</span>
                    <span class="font-mono">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                </div>

                @php
                    $isInvalidCash = ($payment_method === 'cash' && (float) $cash_paid < $totalAmount);
                    $isInvalidProof = (in_array($payment_method, ['qris', 'transfer']) && empty($base64_payment_proof) && empty($payment_proof_photo));
                    $isCheckoutDisabled = empty($cart) || $isInvalidCash || $isInvalidProof;
                @endphp

                <button 
                    wire:click="checkout" 
                    {{ $isCheckoutDisabled ? 'disabled' : '' }} 
                    class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white font-extrabold text-xs rounded-xl shadow-md transition uppercase tracking-wider cursor-pointer"
                >
                    Bayar & Selesaikan Transaksi
                </button>
            </div>

        </div>

    </div>

</div>
