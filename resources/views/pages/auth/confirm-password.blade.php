<x-layouts::app :title="__('Konfirmasi Password')">
    <!-- Modal Backdrop Overlay -->
    <div class="fixed inset-0 z-50 bg-zinc-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <flux:card class="w-full max-w-md p-8 space-y-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 shadow-2xl rounded-2xl relative animate-in fade-in zoom-in-95 duration-200">
            
            <div class="space-y-2 text-center">
                <div class="mx-auto w-12 h-12 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-900 dark:text-white mb-3 shadow-inner">
                    <flux:icon icon="lock-closed" class="size-6" />
                </div>
                <flux:heading size="xl">Konfirmasi Password Keamanan</flux:heading>
                <flux:subheading class="text-xs text-zinc-500 dark:text-zinc-400">
                    Ini adalah area sensitif sistem. Silakan masukkan password akun Anda untuk melanjutkan ke Pengaturan Keamanan.
                </flux:subheading>
            </div>

            <x-auth-session-status class="text-center" :status="session('status')" />

            <form method="POST" action="{{ route('password.confirm.store') }}" class="space-y-4 text-xs">
                @csrf

                <flux:field>
                    <flux:label class="mb-2 block font-semibold text-zinc-800 dark:text-zinc-200">Password Akun Anda</flux:label>
                    <flux:input
                        name="password"
                        type="password"
                        required
                        placeholder="Masukkan password akun..."
                        viewable
                        autofocus
                    />
                </flux:field>

                <div class="flex items-center justify-between gap-3 pt-2">
                    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/owner/settings/profile' }}" class="px-4 py-2 text-xs font-semibold text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition">
                        Batal
                    </a>
                    <flux:button variant="primary" type="submit" class="flex-1 justify-center">
                        Konfirmasi & Lanjutkan
                    </flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
