<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-800 antialiased">
        <flux:sidebar sticky collapsible="mobile" class="bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.header>
                <flux:sidebar.brand
                    :href="route('dashboard')"
                    logo="{{ asset(\App\Models\SiteSetting::get('app_logo', 'images/logos/Logo-BaberSaaS.webp')) }}"
                    name="{{ \App\Models\SiteSetting::get('app_name', 'BarberSaaS') }}"
                />

                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.search placeholder="Cari..." />

            <flux:sidebar.nav>
                @if(auth()->user()->isSuperAdmin())
                    <flux:sidebar.item icon="home" :href="route('superadmin.dashboard')" :current="request()->routeIs('superadmin.dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                @elseif(auth()->user()->isCashier())
                    <flux:sidebar.item icon="home" :href="route('cashier.dashboard')" :current="request()->routeIs('cashier.dashboard')" wire:navigate>
                        {{ __('Dashboard Kasir') }}
                    </flux:sidebar.item>
                @elseif(auth()->user()->isBarber())
                    <flux:sidebar.item icon="home" :href="route('barber.dashboard')" :current="request()->routeIs('barber.dashboard')" wire:navigate>
                        {{ __('Dashboard Barber') }}
                    </flux:sidebar.item>
                @else
                    <flux:sidebar.item icon="home" :href="route('owner.dashboard')" :current="request()->routeIs('owner.dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                @endif

                @if(auth()->user()->isSuperAdmin())
                    <!-- SUPERADMIN NAVIGATION: PLATFORM MANAGEMENT -->
                    <flux:sidebar.item icon="building-storefront" :href="route('tenants')" :current="request()->routeIs('tenants')" wire:navigate>
                        {{ __('Kelola Barbershop') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="paint-brush" :href="route('superadmin.themes')" :current="request()->routeIs('superadmin.themes')" wire:navigate>
                        {{ __('Kelola Tema Web') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="credit-card" :href="route('superadmin.gateway.edit')" :current="request()->routeIs('superadmin.gateway.*')" wire:navigate>
                        {{ __('Gateway Pakasir') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="book-open" :href="route('superadmin.themes.guide')" :current="request()->routeIs('superadmin.themes.guide')" wire:navigate>
                        {{ __('Panduan Buat Tema') }}
                    </flux:sidebar.item>
                @else
                    <!-- TENANT NAVIGATION: BARBERSHOP OPERATIONS -->
                    @if(auth()->user()->isCashier())
                        <flux:sidebar.item icon="shopping-bag" :href="route('pos')" :current="request()->routeIs('pos')" wire:navigate>
                            {{ __('Kasir POS') }}
                        </flux:sidebar.item>
                    @endif

                    @if(auth()->user()->isBarber())
                        <flux:sidebar.item icon="calendar" :href="route('barber.reservations')" :current="request()->routeIs('barber.reservations')" wire:navigate>
                            {{ __('Papan Workstation & Reservasi') }}
                        </flux:sidebar.item>
                    @else
                        <flux:sidebar.item icon="calendar" :href="route('reservations')" :current="request()->routeIs('reservations')" wire:navigate>
                            {{ __('Papan Reservasi') }}
                        </flux:sidebar.item>
                    @endif

                    @if(auth()->user()->isOwner())
                        <flux:sidebar.item icon="chart-bar" :href="route('reports')" :current="request()->routeIs('reports')" wire:navigate>
                            {{ __('Laporan & Keuangan') }}
                        </flux:sidebar.item>
                    @endif

                    @if(auth()->user()->isOwner())
                        <flux:sidebar.group expandable heading="Pengelolaan Toko" class="grid">
                            <flux:sidebar.item icon="archive-box" :href="route('products')" :current="request()->routeIs('products')" wire:navigate>
                                {{ __('Produk & Inventaris') }}
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="scissors" :href="route('services')" :current="request()->routeIs('services')" wire:navigate>
                                {{ __('Layanan & Harga') }}
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="users" :href="route('staff')" :current="request()->routeIs('staff')" wire:navigate>
                                {{ __('Staf Barber & Akses') }}
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="swatch" :href="route('owner.theme.edit')" :current="request()->routeIs('owner.theme.*')" wire:navigate>
                                {{ __('Tema Web Portal') }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @endif
                @endif
            </flux:sidebar.nav>

            <flux:sidebar.spacer />

            <flux:sidebar.nav>
                @if(!auth()->user()->isSuperAdmin() && !auth()->user()->hasSubmittedFeedback())
                    <flux:sidebar.item icon="star" :href="route('user.feedback')" :current="request()->routeIs('user.feedback')" wire:navigate>
                        {{ __('Penilaian Aplikasi') }}
                    </flux:sidebar.item>
                @endif

                @if(auth()->user()->isSuperAdmin())
                    <flux:sidebar.item icon="cog-6-tooth" :href="route('branding.edit')" :current="request()->routeIs('branding.*')" wire:navigate>
                        {{ __('Pengaturan Platform') }}
                    </flux:sidebar.item>
                @elseif(auth()->user()->isCashier())
                    <flux:sidebar.item icon="cog-6-tooth" :href="route('cashier.profile.edit')" :current="request()->routeIs('cashier.*')" wire:navigate>
                        {{ __('Pengaturan Akun') }}
                    </flux:sidebar.item>
                @elseif(auth()->user()->isBarber())
                    <flux:sidebar.item icon="cog-6-tooth" :href="route('barber.profile.edit')" :current="request()->routeIs('barber.*')" wire:navigate>
                        {{ __('Pengaturan Akun') }}
                    </flux:sidebar.item>
                @else
                    <flux:sidebar.item icon="cog-6-tooth" :href="route('owner.profile.edit')" :current="request()->routeIs('owner.*')" wire:navigate>
                        {{ __('Pengaturan Toko') }}
                    </flux:sidebar.item>
                @endif
            </flux:sidebar.nav>

            <flux:dropdown position="top" align="start" class="max-lg:hidden">
                <flux:sidebar.profile :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-2 text-xs font-semibold text-zinc-500 dark:text-zinc-400">
                            {{ auth()->user()->email }} ({{ strtoupper(auth()->user()->role) }})
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                        >
                            {{ __('Logout') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>

            <!-- PWA Install Button Container -->
            <div id="pwa-install-container" class="hidden p-3 border-t border-zinc-200 dark:border-zinc-800">
                <button id="pwa-install-btn" type="button" class="w-full py-2.5 px-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-between gap-2 cursor-pointer">
                    <div class="flex items-center gap-2">
                        <flux:icon name="arrow-down-tray" class="size-4" />
                        <span>Install Aplikasi BarberSaaS</span>
                    </div>
                    <span class="text-[9px] bg-white/20 px-1.5 py-0.5 rounded font-mono uppercase">PWA</span>
                </button>
            </div>
        </flux:sidebar>

        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="start">
                <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                <flux:menu>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                        >
                            {{ __('Logout') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <flux:main>
            {{ $slot }}
        </flux:main>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts

        <script>
            let deferredPrompt;
            const pwaContainer = document.getElementById('pwa-install-container');
            const pwaBtn = document.getElementById('pwa-install-btn');

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                if (pwaContainer) pwaContainer.classList.remove('hidden');
            });

            if (pwaBtn) {
                pwaBtn.addEventListener('click', async () => {
                    if (deferredPrompt) {
                        deferredPrompt.prompt();
                        const { outcome } = await deferredPrompt.userChoice;
                        if (outcome === 'accepted') {
                            if (pwaContainer) pwaContainer.classList.add('hidden');
                        }
                        deferredPrompt = null;
                    }
                });
            }
        </script>
    </body>
</html>
