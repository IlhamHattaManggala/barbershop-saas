<div class="space-y-6">
    <!-- Header Title -->
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200 dark:border-zinc-700">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 text-[11px] font-extrabold bg-indigo-600 text-white rounded-lg uppercase tracking-wider">PAKASIR</span>
                <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Pengaturan Payment Gateway</h1>
            </div>
            <p class="text-xs text-zinc-500 mt-1">Kelola integrasi Payment Gateway Pakasir untuk transaksi & pembelian tema premium tenant. API Key disimpan dalam database dengan <strong>enkripsi ganda</strong>.</p>
        </div>
    </div>

    @if(!empty($success_message))
        <flux:badge color="emerald" size="lg" class="w-full justify-between p-3">
            <span>{{ $success_message }}</span>
        </flux:badge>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Card -->
        <div class="lg:col-span-2">
            <flux:card class="p-6 space-y-6 border border-zinc-200 dark:border-zinc-800">
                <form wire:submit.prevent="saveGateway" class="space-y-5">
                    <!-- Toggle Status Active -->
                    <div class="p-4 rounded-xl border border-indigo-200 dark:border-indigo-900/60 bg-indigo-50/40 dark:bg-indigo-950/20 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-zinc-900 dark:text-white">Status Gateway Pakasir</div>
                            <div class="text-[11px] text-zinc-500">Aktifkan untuk menerima pembayaran QRIS & E-Wallet dari tenant.</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="pakasir_is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-zinc-300 peer-focus:outline-none rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-zinc-600 peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <!-- Merchant Slug -->
                    <div>
                        <flux:label>Merchant Slug Pakasir</flux:label>
                        <flux:input wire:model="pakasir_slug" required placeholder="babershopsaas" icon="identification" />
                        <span class="text-[11px] text-zinc-500 mt-1 block">Slug unik merchant yang terdaftar di portal Pakasir.</span>
                        @error('pakasir_slug') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Encrypted API Key Input -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <flux:label>Pakasir API Key (Terenkripsi)</flux:label>
                            <button type="button" wire:click="toggleShowKey" class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $show_key ? 'Sembunyikan' : 'Tampilkan API Key' }}
                            </button>
                        </div>
                        <div class="relative">
                            <flux:input type="{{ $show_key ? 'text' : 'password' }}" wire:model="pakasir_api_key" required placeholder="Masukkan API Key Pakasir..." icon="key" />
                        </div>
                        <div class="flex items-center gap-1.5 mt-1.5 text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">
                            <flux:icon icon="lock-closed" class="size-3.5" />
                            <span>Tersimpan dengan enkripsi aman Crypt (AES-256-GCM) di database.</span>
                        </div>
                        @error('pakasir_api_key') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex justify-end">
                        <flux:button type="submit" variant="primary" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold cursor-pointer">
                            Simpan Konfigurasi Gateway
                        </flux:button>
                    </div>
                </form>
            </flux:card>
        </div>

        <!-- Sidebar Info & Webhook URL Box -->
        <div class="space-y-6">
            <flux:card class="p-6 space-y-4 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <flux:icon icon="link" class="size-4 text-indigo-600" />
                    <span>URL Webhook Notification</span>
                </h3>
                <p class="text-xs text-zinc-500 leading-relaxed">Salin dan tempelkan URL berikut di menu <strong>Webhook Settings</strong> pada dashboard akun Pakasir Anda:</p>

                <div class="p-3 bg-zinc-900 text-indigo-300 rounded-xl font-mono text-[11px] break-all border border-zinc-800 selection:bg-indigo-500 selection:text-white">
                    {{ $webhook_url }}
                </div>

                <div class="space-y-2 pt-2 border-t border-zinc-100 dark:border-zinc-800 text-[11px] text-zinc-500">
                    <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-semibold">
                        <flux:icon icon="check-circle" class="size-3.5" />
                        <span>Metode Callback: POST JSON</span>
                    </div>
                    <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-semibold">
                        <flux:icon icon="sparkles" class="size-3.5" />
                        <span>Auto-Unlock Tema Premium Instant</span>
                    </div>
                </div>
            </flux:card>
        </div>
    </div>
</div>
