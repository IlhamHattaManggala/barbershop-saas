<div class="relative px-3 py-2 border-b border-zinc-200 dark:border-zinc-800" x-data="{ open: false }">
    @if($successMessage)
        <div class="mb-2 p-2 rounded-lg bg-emerald-50 text-emerald-800 text-[11px] font-bold border border-emerald-200">
            {{ $successMessage }}
        </div>
    @endif

    <!-- Outlet Switcher Trigger Button -->
    <button @click="open = !open" @click.away="open = false" class="w-full flex items-center justify-between p-2.5 rounded-xl bg-zinc-100 dark:bg-zinc-800/80 hover:bg-zinc-200 dark:hover:bg-zinc-800 text-left transition border border-zinc-200 dark:border-zinc-700/60 shadow-2xs">
        <div class="flex items-center gap-2.5 min-w-0">
            <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-bold flex items-center justify-center text-xs shrink-0 font-mono">
                <flux:icon name="building-storefront" class="size-4" />
            </div>
            <div class="min-w-0">
                <div class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider">Cabang Barbershop</div>
                <div class="text-xs font-black text-zinc-900 dark:text-white truncate">
                    {{ $activeOutlet->name ?? 'Cabang Utama' }}
                </div>
            </div>
        </div>

        <flux:icon name="chevron-down" class="size-4 text-zinc-400 shrink-0" />
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" x-cloak class="absolute left-3 right-3 mt-1.5 z-50 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-xl p-2 space-y-1 animate-in fade-in zoom-in-95 duration-150">
        <div class="px-2 py-1 flex items-center justify-between text-[10px] font-bold text-zinc-400 uppercase tracking-wider border-b border-zinc-100 dark:border-zinc-800">
            <span>Daftar Cabang Toko</span>
            <span class="font-mono">{{ $ownedOutlets->count() }} / {{ $allowedCount }} Slot</span>
        </div>

        <div class="max-h-48 overflow-y-auto space-y-0.5 py-1">
            @foreach($ownedOutlets as $outlet)
                <button type="button" wire:click="switchOutlet({{ $outlet->id }})" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition text-left {{ ($activeOutlet && $activeOutlet->id === $outlet->id) ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                    <span class="truncate">{{ $outlet->name }}</span>
                    @if($activeOutlet && $activeOutlet->id === $outlet->id)
                        <flux:badge size="sm" color="indigo">Aktif</flux:badge>
                    @endif
                </button>
            @endforeach
        </div>

        <div class="pt-1 border-t border-zinc-100 dark:border-zinc-800">
            <button type="button" wire:click="requestNewOutlet" class="w-full flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs shadow-xs transition">
                <flux:icon name="plus" class="size-4" />
                <span>Tambah Cabang Baru (Rp 100.000)</span>
            </button>
        </div>
    </div>

    <!-- Modal Form: Pendaftaran Cabang Baru -->
    @if($showAddOutletModal)
        <div class="fixed inset-0 z-50 bg-zinc-950/80 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 max-w-md w-full space-y-4 border border-zinc-200 dark:border-zinc-800 shadow-2xl">
                <div>
                    <h3 class="text-base font-extrabold text-zinc-900 dark:text-white">Pendaftaran Cabang Baru</h3>
                    <p class="text-xs text-zinc-500">Daftarkan rincian nama dan alamat cabang barbershop baru Anda.</p>
                </div>

                <form wire:submit.prevent="createNewOutlet" class="space-y-3 text-xs">
                    <div>
                        <flux:label>Nama Cabang Barbershop</flux:label>
                        <flux:input wire:model="new_outlet_name" required placeholder="Contoh: Gentlemen Barber - Cabang Kemang" />
                    </div>

                    <div>
                        <flux:label>No. Telepon / WA Cabang</flux:label>
                        <flux:input wire:model="new_outlet_phone" placeholder="08123456789" />
                    </div>

                    <div>
                        <flux:label>Alamat Cabang</flux:label>
                        <flux:textarea wire:model="new_outlet_address" rows="2" placeholder="Jl. Kemang Raya No. 12..." />
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-800">
                        <button type="button" wire:click="$set('showAddOutletModal', false)" class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-bold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl transition shadow-md">
                            Simpan & Buka Cabang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Pakasir: Pembayaran Slot Cabang Rp 100.000 -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 bg-zinc-950/80 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 max-w-md w-full space-y-4 border border-zinc-200 dark:border-zinc-800 shadow-2xl text-center">
                <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto">
                    <flux:icon name="building-storefront" class="size-6" />
                </div>

                <div>
                    <h3 class="text-base font-extrabold text-zinc-900 dark:text-white">Beli Slot Cabang Baru</h3>
                    <p class="text-xs text-zinc-500 mt-1">Pembayaran ekspansi cabang barbershop seharga <strong class="text-indigo-600">Rp 100.000</strong> via Pakasir Payment Gateway.</p>
                </div>

                <div class="p-3 bg-zinc-50 dark:bg-zinc-800/60 rounded-2xl border border-zinc-200 dark:border-zinc-700 text-xs font-mono">
                    <div class="text-zinc-400 text-[10px]">RESI ORDER</div>
                    <div class="font-bold text-zinc-800 dark:text-zinc-200">{{ $paymentReference }}</div>
                </div>

                <div class="flex flex-col gap-2 pt-2">
                    <a href="{{ $pakasirPayUrl }}" target="_blank" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-md transition uppercase tracking-wider inline-flex items-center justify-center gap-2">
                        <flux:icon name="credit-card" class="size-4" />
                        <span>Bayar Rp 100.000 via Pakasir</span>
                    </a>
                    
                    <button type="button" wire:click="verifyOutletPayment('{{ $paymentReference }}')" class="w-full py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs rounded-xl border border-emerald-200 transition">
                        Cek Status Pembayaran
                    </button>

                    <button type="button" wire:click="$set('showPaymentModal', false)" class="text-xs text-zinc-400 hover:underline pt-1">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
