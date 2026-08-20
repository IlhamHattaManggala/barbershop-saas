@php
    $isSA = auth()->user()->isSuperAdmin();
    $isCashier = auth()->user()->isCashier();
    $isBarber = auth()->user()->isBarber();
    $profileRoute = $isSA ? route('superadmin.profile.edit') : ($isCashier ? route('cashier.profile.edit') : ($isBarber ? route('barber.profile.edit') : route('owner.profile.edit')));
    $securityRoute = $isSA ? route('superadmin.security.edit') : ($isCashier ? route('cashier.security.edit') : ($isBarber ? route('barber.security.edit') : route('owner.security.edit')));
    $appearanceRoute = $isSA ? route('superadmin.appearance.edit') : ($isCashier ? route('cashier.appearance.edit') : ($isBarber ? route('barber.appearance.edit') : route('owner.appearance.edit')));
@endphp

<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist aria-label="{{ __('Settings') }}">
            <flux:navlist.item :href="$profileRoute" :current="request()->routeIs('*profile.edit')" wire:navigate>{{ __('Profile') }}</flux:navlist.item>
            <flux:navlist.item :href="$securityRoute" :current="request()->routeIs('*security.edit')" wire:navigate>{{ __('Security') }}</flux:navlist.item>
            <flux:navlist.item :href="$appearanceRoute" :current="request()->routeIs('*appearance.edit')" wire:navigate>{{ __('Appearance') }}</flux:navlist.item>
            @if($isSA)
                <flux:navlist.item :href="route('branding.edit')" :current="request()->routeIs('branding.edit')" wire:navigate>{{ __('Identitas Website') }}</flux:navlist.item>
            @elseif(!$isCashier && !$isBarber)
                <flux:navlist.item :href="route('owner.shop.edit')" :current="request()->routeIs('owner.shop.edit')" wire:navigate>{{ __('Profil Barbershop') }}</flux:navlist.item>
            @endif
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-3xl">
            {{ $slot }}
        </div>
    </div>
</div>
