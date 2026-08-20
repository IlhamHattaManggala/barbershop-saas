<div class="space-y-6">
    <!-- Header Title & Live Portal Link -->
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200 dark:border-zinc-700">
        <div>
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Tema Web Portal</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Pilih tema tampilan website portal booking online outlet Anda ({{ url($slug) }})</p>
        </div>
        <a href="{{ url($slug) }}" target="_blank" class="px-3.5 py-2 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xl shadow transition inline-flex items-center gap-1.5">
            <span>Lihat Portal Live</span>
            <flux:icon icon="arrow-up-right" class="size-3.5" />
        </a>
    </div>

    @if(!empty($success_message))
        <flux:badge color="emerald" size="lg" class="w-full justify-between p-3">
            <span>{{ $success_message }}</span>
        </flux:badge>
    @endif

    @if(session()->has('error'))
        <flux:badge color="rose" size="lg" class="w-full justify-between p-3">
            <span>{{ session()->get('error') }}</span>
        </flux:badge>
    @endif

    <!-- Theme Gallery Grid (Dynamic list from Database) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($allThemes as $t)
            @php
                $isPurchased = $tenant ? $tenant->hasPurchasedTheme($t->slug) : true;
                $hasThumbnail = !empty($t->thumbnail) && file_exists(public_path($t->thumbnail));
            @endphp
            <div class="p-5 rounded-2xl border-2 transition-all flex flex-col justify-between space-y-4 {{ $active_theme === $t->slug ? 'border-amber-500 bg-amber-50/20 dark:bg-amber-950/10 ring-2 ring-amber-500/20' : 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900' }}">
                <div class="space-y-3">
                    <!-- Visual Mockup Preview Header (Renders Exact Real Theme Styles) -->
                    <div class="w-full h-44 rounded-xl border border-zinc-200 dark:border-zinc-800 relative overflow-hidden shadow-xs flex flex-col justify-between group">
                        
                        @if($hasThumbnail)
                            <img src="{{ asset($t->thumbnail) }}" alt="{{ $t->name }}" class="w-full h-full object-cover object-top absolute inset-0" />
                        @else
                            <!-- EXACT REAL THEME MINI MOCKUP PREVIEWS -->
                            @if($t->slug === 'gentlemen-classic')
                                <div class="w-full h-full bg-[#FAF9F6] p-3 text-zinc-900 flex flex-col justify-between font-serif relative">
                                    <div class="flex items-center justify-between border-b border-zinc-300 pb-1.5">
                                        <div class="font-extrabold text-xs text-zinc-900 uppercase">GENTLEMEN CLASSIC</div>
                                        <span class="text-[8px] bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-sans">Classic Pole</span>
                                    </div>
                                    <div class="space-y-1 text-center py-2">
                                        <div class="text-[10px] font-sans font-bold text-zinc-500 uppercase tracking-widest">PANGKAS KLASIK</div>
                                        <div class="text-xs font-black text-zinc-950 uppercase">Tradisi Grooming Pria</div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-1.5 pt-1 font-sans">
                                        <div class="p-1.5 bg-white border border-zinc-200 rounded text-[9px]">
                                            <div class="font-bold text-zinc-900">Classic Cut</div>
                                            <div class="text-emerald-600 font-bold">Rp 50.000</div>
                                        </div>
                                        <div class="p-1.5 bg-white border border-zinc-200 rounded text-[9px]">
                                            <div class="font-bold text-zinc-900">Beard Trim</div>
                                            <div class="text-emerald-600 font-bold">Rp 35.000</div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($t->slug === 'modern-minimalist')
                                <div class="w-full h-full bg-[#F8F9FA] p-3 text-zinc-900 flex flex-col justify-between font-sans relative">
                                    <div class="flex items-center justify-between border-b border-zinc-200 pb-1.5">
                                        <div class="font-black text-xs text-zinc-950">MODERN MINIMALIST</div>
                                        <span class="text-[8px] bg-emerald-500 text-white px-1.5 py-0.5 rounded-full font-bold">Buka</span>
                                    </div>
                                    <div class="p-2 bg-white rounded-xl border border-zinc-200 shadow-2xs space-y-1">
                                        <div class="text-[10px] font-black text-zinc-950">Scandinavian Studio UI</div>
                                        <div class="text-[9px] text-zinc-400">Presisi & Kebersihan Optimal</div>
                                    </div>
                                    <div class="flex items-center justify-between text-[9px] font-bold">
                                        <span class="text-zinc-900">2 Paket Layanan</span>
                                        <span class="bg-zinc-950 text-white px-2 py-0.5 rounded-lg">Booking</span>
                                    </div>
                                </div>
                            @elseif($t->slug === 'vintage-noir')
                                <div class="w-full h-full bg-zinc-950 p-3 text-amber-100 flex flex-col justify-between font-serif relative">
                                    <div class="flex items-center justify-between border-b border-amber-500/30 pb-1.5">
                                        <div class="font-bold text-xs text-amber-400 uppercase tracking-widest">VINTAGE NOIR</div>
                                        <span class="text-[8px] border border-amber-500/40 text-amber-300 px-1.5 py-0.5 uppercase">Speakeasy</span>
                                    </div>
                                    <div class="text-center space-y-0.5 py-1">
                                        <div class="text-xs font-bold text-amber-200 uppercase">1920s Gentlemen Club</div>
                                        <div class="text-[9px] text-zinc-400 font-sans">Aksen Emas & Cukur Pisau Lipat</div>
                                    </div>
                                    <div class="p-1.5 bg-zinc-900 border border-amber-500/30 rounded text-[9px] flex items-center justify-between">
                                        <span class="text-amber-200 font-bold">Layanan Speakeasy</span>
                                        <span class="text-amber-400 font-mono font-bold">Rp 20.000+</span>
                                    </div>
                                </div>
                            @elseif($t->slug === 'urban-streetwear')
                                <div class="w-full h-full bg-black p-3 text-white flex flex-col justify-between font-sans relative">
                                    <div class="flex items-center justify-between border-b-2 border-lime-400 pb-1.5">
                                        <div class="font-black italic text-xs text-lime-400 uppercase">URBAN STREETWEAR</div>
                                        <span class="px-1.5 py-0.5 bg-lime-400 text-black font-black text-[8px] uppercase">OPEN NOW</span>
                                    </div>
                                    <div class="bg-zinc-950 border border-zinc-800 p-2 space-y-1">
                                        <div class="text-[10px] font-black italic text-white uppercase">#FRESHFADE #NEON</div>
                                        <div class="text-[9px] text-cyan-400 font-mono">BRUTALIST STUDIO</div>
                                    </div>
                                    <div class="p-1 bg-lime-400 text-black font-black text-center text-[9px] uppercase">
                                        SUBMIT BOOKING
                                    </div>
                                </div>
                            @elseif($t->slug === 'cyberpunk-neon')
                                <div class="w-full h-full bg-slate-950 p-3 text-rose-100 flex flex-col justify-between font-mono relative" style="clip-path: polygon(0 10px, 10px 0, calc(100% - 10px) 0, 100% 10px, 100% calc(100% - 10px), calc(100% - 10px) 100%, 10px 100%, 0 calc(100% - 10px));">
                                    <div class="flex items-center justify-between border-b border-rose-500/40 pb-1.5">
                                        <div class="font-black italic text-xs text-rose-500 uppercase">CYBERPUNK 2088</div>
                                        <span class="text-[8px] text-cyan-400 font-bold">SYS: ONLINE</span>
                                    </div>
                                    <div class="p-2 bg-slate-900 border border-rose-500/40 space-y-1">
                                        <div class="text-[10px] font-bold text-white uppercase">Matrix Cut & Fade</div>
                                        <div class="text-[8px] text-rose-400 font-bold">HUD POLYGON FRAME</div>
                                    </div>
                                    <div class="text-[9px] text-cyan-300 font-bold text-center bg-rose-500/20 border border-rose-500/40 py-1">
                                        TRANSMIT RESERVATION
                                    </div>
                                </div>
                            @elseif($t->slug === 'royal-emerald')
                                <div class="w-full h-full bg-[#061A14] p-3 text-emerald-100 flex flex-col justify-between font-serif relative">
                                    <div class="flex items-center justify-between border-b border-emerald-500/30 pb-1.5">
                                        <div class="font-bold text-xs text-amber-300 uppercase">ROYAL EMERALD</div>
                                        <span class="text-[8px] border border-amber-400/40 text-amber-300 px-1.5 py-0.5 rounded-full uppercase">VIP Suite</span>
                                    </div>
                                    <div class="text-center space-y-0.5 py-1">
                                        <div class="text-xs font-bold text-amber-200 uppercase">Luxury Regency Salon</div>
                                        <div class="text-[9px] text-emerald-300/80 font-sans">Forest Green & Champagne Gold</div>
                                    </div>
                                    <div class="p-1.5 bg-[#0B2E24] border border-emerald-500/30 rounded-xl text-[9px] text-center text-amber-300 font-bold">
                                        KIRIM RESERVASI ROYAL
                                    </div>
                                </div>
                            @elseif($t->slug === 'tokyo-minimal')
                                <div class="w-full h-full bg-[#FFFDF9] p-3 text-zinc-900 flex flex-col justify-between font-sans relative">
                                    <div class="flex items-center justify-between border-b border-rose-200 pb-1.5">
                                        <div class="flex items-center gap-1">
                                            <span class="w-3.5 h-3.5 rounded-full bg-rose-600 text-white flex items-center justify-center text-[7px] font-bold">印</span>
                                            <span class="font-black text-xs text-zinc-950">TOKYO SAKURA</span>
                                        </div>
                                        <span class="text-[8px] bg-rose-50 text-rose-700 px-1.5 py-0.5 rounded-full font-bold">東京</span>
                                    </div>
                                    <div class="p-2 bg-white border border-rose-100 rounded-xl text-center space-y-0.5">
                                        <div class="text-[10px] font-black text-zinc-950">Wabi-Sabi & Sakura Artwork</div>
                                        <div class="text-[9px] text-rose-600 font-bold">Presisi Pangkas Jepang</div>
                                    </div>
                                    <div class="p-1 bg-rose-600 text-white text-center text-[9px] font-bold rounded-lg">
                                        Kirim Reservasi Sekarang
                                    </div>
                                </div>
                            @elseif($t->slug === 'retro-synthwave')
                                <div class="w-full h-full bg-purple-950 p-3 text-fuchsia-100 flex flex-col justify-between font-sans relative">
                                    <div class="flex items-center justify-between border-b border-fuchsia-500/40 pb-1.5">
                                        <div class="font-black italic text-xs text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-fuchsia-400">RETRO SYNTHWAVE</div>
                                        <span class="text-[8px] text-cyan-300 font-mono">ARCADE 80S</span>
                                    </div>
                                    <div class="p-2 bg-purple-900/80 border border-fuchsia-500/30 rounded-xl text-center">
                                        <div class="text-[10px] font-black text-cyan-300 italic">VAPOR CUTS & NEON GRID</div>
                                    </div>
                                    <div class="p-1 bg-gradient-to-r from-cyan-400 to-fuchsia-500 text-purple-950 font-black text-center text-[9px] uppercase rounded-lg">
                                        TRANSMIT RESERVATION
                                    </div>
                                </div>
                            @elseif($t->slug === 'executive-titanium')
                                <div class="w-full h-full bg-stone-950 p-3 text-stone-100 flex flex-col justify-between font-mono relative">
                                    <div class="flex items-center justify-between border-b border-stone-800 pb-1.5">
                                        <div class="font-bold text-xs text-white uppercase">EXECUTIVE TITANIUM</div>
                                        <span class="text-[8px] border border-stone-600 px-1.5 py-0.5 text-stone-300">VIP LOUNGE</span>
                                    </div>
                                    <div class="p-2 bg-stone-900 border border-stone-800 rounded-xl text-center">
                                        <div class="text-[10px] font-bold text-stone-200">BRUSHED METALLIC SILVER</div>
                                    </div>
                                    <div class="p-1 bg-stone-100 text-stone-950 font-black text-center text-[9px] uppercase rounded-xl">
                                        SUBMIT EXECUTIVE RESERVATION
                                    </div>
                                </div>
                            @elseif($t->slug === 'batik-heritage')
                                <div class="w-full h-full bg-[#FAF6F0] p-3 text-stone-900 flex flex-col justify-between font-serif relative">
                                    <div class="flex items-center justify-between border-b border-amber-900/15 pb-1.5">
                                        <div class="font-extrabold text-xs text-amber-950 uppercase">BATIK HERITAGE</div>
                                        <span class="text-[8px] bg-amber-100 text-amber-900 px-1.5 py-0.5 rounded-full font-bold">Keraton</span>
                                    </div>
                                    <div class="p-2 bg-white border border-amber-900/15 rounded-xl text-center space-y-0.5">
                                        <div class="text-[10px] font-bold text-amber-950 uppercase">Batik Parang & Kawung</div>
                                        <div class="text-[9px] text-amber-800 font-sans">Tradisi Keraton Nusantara</div>
                                    </div>
                                    <div class="p-1 bg-amber-800 text-amber-50 text-center text-[9px] font-bold rounded-xl uppercase">
                                        Kirim Reservasi Keraton
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-zinc-800 to-zinc-950 rounded-lg flex flex-col items-center justify-center p-4 text-center">
                                    <div class="text-xs font-mono font-bold text-indigo-400 uppercase tracking-widest">{{ $t->slug }}</div>
                                    <div class="text-[10px] text-zinc-500 mt-1">{{ $t->blade_view }}</div>
                                </div>
                            @endif
                        @endif

                        <!-- BADGE TIPE TEMA -->
                        <div class="absolute top-2 right-2 z-20">
                            @if($t->type === 'free')
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-emerald-500 text-white rounded-full shadow-md">FREE</span>
                            @elseif($isPurchased)
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-indigo-600 text-white rounded-full shadow-md">TERBELI • PREMIUM</span>
                            @else
                                <span class="px-2.5 py-0.5 text-[10px] font-bold bg-amber-500 text-zinc-950 rounded-full shadow-md whitespace-nowrap">PREMIUM • {{ $t->formatted_price }}</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="font-bold text-sm text-zinc-900 dark:text-white">{{ $t->name }}</h3>
                            @if($t->type === 'free')
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 rounded-full shrink-0 whitespace-nowrap">FREE</span>
                            @elseif($isPurchased)
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 rounded-full shrink-0 whitespace-nowrap">TERBELI</span>
                            @else
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300 rounded-full shrink-0 whitespace-nowrap">PREMIUM • {{ $t->formatted_price }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">{{ $t->description ?? 'Tema kustom profesional untuk barbershop Anda.' }}</p>
                    </div>
                </div>

                <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 space-y-2">
                    @if($previewEnabled ?? true)
                        <a href="{{ route('theme.preview', $t->slug) }}" target="_blank" class="w-full py-1.5 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 font-bold text-xs rounded-xl transition inline-flex items-center justify-center gap-1">
                            <flux:icon icon="eye" class="size-3.5" />
                            <span>Preview Live Tema</span>
                        </a>
                    @endif
                    @if($active_theme === $t->slug)
                        <flux:badge color="emerald" size="sm" icon="check-circle" class="w-full justify-center py-2">
                            TEMA AKTIF
                        </flux:badge>
                        <a href="{{ route('owner.theme.customize') }}" class="w-full py-2.5 bg-amber-500 hover:bg-amber-400 text-zinc-950 font-bold text-xs rounded-xl transition inline-flex items-center justify-center gap-1.5 shadow-xs">
                            <flux:icon icon="pencil" class="size-3.5" />
                            <span>Kustomisasi Tema Ini</span>
                        </a>
                    @elseif($isPurchased)
                        <flux:button wire:click="selectTheme('{{ $t->slug }}')" variant="primary" size="sm" class="w-full py-2 cursor-pointer">
                            Gunakan Tema Ini
                        </flux:button>
                    @else
                        <flux:button wire:click="selectTheme('{{ $t->slug }}')" variant="primary" icon="sparkles" class="w-full py-2 cursor-pointer bg-indigo-600 hover:bg-indigo-500 text-white font-bold">
                            Beli Tema — {{ $t->formatted_price }} (Pakasir)
                        </flux:button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-8 text-center text-zinc-400 text-xs">
                Belum ada tema yang aktif.
            </div>
        @endforelse
    </div>

    <!-- Modal Payment Gateway Pakasir -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl max-w-md w-full p-6 space-y-6 shadow-2xl relative">
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-800 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 text-[11px] font-extrabold bg-indigo-600 text-white rounded-lg uppercase tracking-wider">PAKASIR</span>
                        <h2 class="text-base font-bold text-zinc-900 dark:text-white">Payment Gateway</h2>
                    </div>
                    <button wire:click="closePaymentModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 text-xl font-bold">&times;</button>
                </div>

                <div class="space-y-4">
                    @if(!empty($paymentModalStatus))
                        <div class="p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-xl text-xs font-semibold text-amber-800 dark:text-amber-300 flex items-center gap-2">
                            <flux:icon icon="information-circle" class="size-4 shrink-0 text-amber-600" />
                            <span>{{ $paymentModalStatus }}</span>
                        </div>
                    @endif

                    <div class="bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800 rounded-xl p-4 space-y-2">
                        <div class="text-[11px] font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Rincian Pembelian Tema</div>
                        <div class="flex items-center justify-between font-bold text-sm text-zinc-900 dark:text-white">
                            <span>{{ $paymentThemeName }}</span>
                            <span class="text-indigo-600 dark:text-indigo-400">Rp {{ number_format($paymentThemePrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-zinc-500 font-mono pt-1 border-t border-indigo-100 dark:border-indigo-900/40">
                            <span>Merchant: {{ $pakasirSlug ?? 'babershopsaas' }}</span>
                            <span>Ref: {{ $paymentReference }}</span>
                        </div>
                    </div>

                    <div class="p-4 border border-zinc-200 dark:border-zinc-800 rounded-xl bg-zinc-50 dark:bg-zinc-950 space-y-3">
                        <div class="text-xs font-bold text-zinc-900 dark:text-white flex items-center justify-between">
                            <span>Metode Pembayaran Pakasir:</span>
                            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-extrabold uppercase">Terkoneksi API</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center text-[10px] text-zinc-600 dark:text-zinc-400">
                            <div class="p-2.5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg space-y-1">
                                <flux:icon icon="qr-code" class="size-4 mx-auto text-indigo-600 dark:text-indigo-400" />
                                <div class="font-bold text-zinc-900 dark:text-white">QRIS</div>
                                <div class="text-[9px]">All e-Wallet & Bank</div>
                            </div>
                            <div class="p-2.5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg space-y-1">
                                <flux:icon icon="credit-card" class="size-4 mx-auto text-indigo-600 dark:text-indigo-400" />
                                <div class="font-bold text-zinc-900 dark:text-white">Virtual Account</div>
                                <div class="text-[9px]">BCA, Mandiri, BRI</div>
                            </div>
                            <div class="p-2.5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg space-y-1">
                                <flux:icon icon="wallet" class="size-4 mx-auto text-indigo-600 dark:text-indigo-400" />
                                <div class="font-bold text-zinc-900 dark:text-white">E-Wallet</div>
                                <div class="text-[9px]">OVO, DANA, GoPay</div>
                            </div>
                        </div>

                        <!-- Scan Preview / Direct Link -->
                        <div class="pt-2 text-center space-y-2">
                            <a href="{{ $pakasirPayUrl }}" target="_blank" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow transition inline-flex items-center justify-center gap-2">
                                <span>Buka Halaman Checkout Resmi Pakasir</span>
                                <flux:icon icon="arrow-up-right" class="size-3.5" />
                            </a>
                            <div class="text-[10px] text-zinc-400 truncate px-2">URL Resmi: <code class="text-indigo-400 font-mono text-[9px]">https://app.pakasir.com/pay/{{ $pakasirSlug }}/{{ (int)$paymentThemePrice }}</code></div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-800">
                    <button wire:click="closePaymentModal" type="button" class="w-full sm:w-auto px-3 py-2 text-xs font-semibold text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-xl transition">Batal</button>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button wire:click="verifyPakasirOrder('{{ $paymentReference }}')" type="button" class="px-3.5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <flux:icon icon="arrow-path" class="size-3.5" />
                            <span>Cek Status Pakasir</span>
                        </button>
                        <button wire:click="processPakasirPayment" type="button" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <flux:icon icon="check-circle" class="size-4" />
                            <span>Konfirmasi & Aktifkan Tema</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
