<x-layouts::auth :title="__('Masuk')">
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="text-2xl font-extrabold text-white font-heading tracking-tight">Masuk ke BarberSaaS™</h1>
            <p class="text-xs text-indigo-200/80">Masukkan email dan password akun Anda untuk masuk ke sistem.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center text-xs text-emerald-400 font-semibold" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label class="block text-xs font-semibold text-indigo-200 uppercase tracking-wider mb-1.5">Email Anda</label>
                <div class="relative">
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="nama@babershop.my.id" class="w-full bg-indigo-950/70 border border-indigo-700/60 rounded-xl px-4 py-3 text-xs text-white placeholder-indigo-300/40 focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 font-medium transition" />
                </div>
                @error('email')
                    <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-indigo-200 uppercase tracking-wider">Password Anda</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-cyan-300 hover:underline font-medium" wire:navigate>
                            Lupa Password?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full bg-indigo-950/70 border border-indigo-700/60 rounded-xl px-4 py-3 text-xs text-white placeholder-indigo-300/40 focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 font-medium transition" />
                </div>
                @error('password')
                    <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} class="w-4 h-4 rounded bg-indigo-950 border-indigo-700 text-cyan-400 focus:ring-cyan-400/30" />
                <label for="remember" class="text-xs text-indigo-200/90 font-medium cursor-pointer">Ingat Saya</label>
            </div>

            <!-- Submit Button (Cyan Pill Button Matching Reference Image) -->
            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 px-4 bg-cyan-400 hover:bg-cyan-300 text-indigo-950 font-extrabold text-xs rounded-xl shadow-lg shadow-cyan-400/25 transition duration-200 uppercase tracking-wider" data-test="login-button">
                    Masuk Sekarang
                </button>
            </div>
        </form>

        <!-- Switch to Register Link -->
        <div class="pt-4 border-t border-indigo-800/40 text-center text-xs text-indigo-200/80">
            <span>Belum memiliki akun barbershop?</span>
            <a href="{{ route('register') }}" class="text-cyan-300 hover:underline font-bold ml-1" wire:navigate>
                Daftar Akun Baru
            </a>
        </div>
    </div>
</x-layouts::auth>
