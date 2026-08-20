<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Identitas Website') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Identitas Website')" :subheading="__('Kelola nama platform, logo, favicon, slogan, dan teks footer yang tersimpan di database.')">
        <div class="space-y-6">
            @if(!empty($success_message))
                <flux:badge color="emerald" size="lg" class="w-full justify-between p-3">
                    <span>{{ $success_message }}</span>
                </flux:badge>
            @endif

            <form wire:submit.prevent="saveBranding" class="space-y-6">
                <div>
                    <flux:label>Nama Platform / Website</flux:label>
                    <flux:input wire:model="app_name" required placeholder="Contoh: BarberSaaS" />
                    <flux:description class="mt-1">Nama utama aplikasi yang akan tampil di header, tab browser, dan email.</flux:description>
                </div>

                <div>
                    <flux:label>Slogan / Tagline</flux:label>
                    <flux:input wire:model="app_tagline" placeholder="Contoh: Platform Barbershop Multi-Tenant #1 di Indonesia" />
                </div>

                <div>
                    <flux:label>Logo Website (Header & Sidebar)</flux:label>
                    <div class="flex items-center gap-4 mt-2">
                        @if(!empty($new_logo))
                            <img src="{{ $new_logo->temporaryUrl() }}" alt="Preview Logo" class="w-12 h-12 object-contain rounded-lg border border-zinc-200 p-1 bg-white" />
                        @elseif(!empty($current_logo))
                            <img src="{{ asset($current_logo) }}" alt="Logo Saat Ini" class="w-12 h-12 object-contain rounded-lg border border-zinc-200 p-1 bg-white" />
                        @endif
                        <div class="flex-1">
                            <input type="file" wire:model="new_logo" accept="image/*" class="text-xs text-zinc-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200" />
                        </div>
                    </div>
                    <flux:description class="mt-1">Format gambar PNG, WEBP, atau SVG max 2MB.</flux:description>
                </div>

                <div>
                    <flux:label>Favicon Website (Icon Tab Browser)</flux:label>
                    <div class="flex items-center gap-4 mt-2">
                        @if(!empty($new_favicon))
                            <img src="{{ $new_favicon->temporaryUrl() }}" alt="Preview Favicon" class="w-8 h-8 object-contain rounded-md border border-zinc-200 p-1 bg-white" />
                        @elseif(!empty($current_favicon))
                            <img src="{{ asset($current_favicon) }}" alt="Favicon Saat Ini" class="w-8 h-8 object-contain rounded-md border border-zinc-200 p-1 bg-white" />
                        @endif
                        <div class="flex-1">
                            <input type="file" wire:model="new_favicon" accept="image/*" class="text-xs text-zinc-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200" />
                        </div>
                    </div>
                    <flux:description class="mt-1">Format gambar persegi PNG/WEBP max 1MB.</flux:description>
                </div>

                <div>
                    <flux:label>Teks Hak Cipta Footer</flux:label>
                    <flux:input wire:model="footer_text" placeholder="© 2026 BarberSaaS. All rights reserved." />
                </div>

                <div class="flex items-center justify-start pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button type="submit" variant="primary">
                        Simpan Perubahan Identitas
                    </flux:button>
                </div>
            </form>
        </div>
    </x-pages::settings.layout>
</section>
