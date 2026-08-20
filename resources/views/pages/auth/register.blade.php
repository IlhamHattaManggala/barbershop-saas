<x-layouts::auth :title="__('Daftar Akun Baru')">
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="text-2xl font-extrabold text-white font-heading tracking-tight">Daftar Akun Barbershop</h1>
            <p class="text-xs text-indigo-200/80">Lengkapi formulir di bawah ini untuk mendaftarkan akun baru.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center text-xs text-emerald-400 font-semibold" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-4">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-xs font-semibold text-indigo-200 uppercase tracking-wider mb-1">Nama Lengkap Owner</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Contoh: Budi Santoso" class="w-full bg-indigo-950/70 border border-indigo-700/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-indigo-300/40 focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 font-medium transition" />
                @error('name')
                    <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label class="block text-xs font-semibold text-indigo-200 uppercase tracking-wider mb-1">Alamat Email Login</label>
                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@babershop.my.id" class="w-full bg-indigo-950/70 border border-indigo-700/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-indigo-300/40 focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 font-medium transition" />
                @error('email')
                    <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-semibold text-indigo-200 uppercase tracking-wider mb-1">Password</label>
                <input type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" class="w-full bg-indigo-950/70 border border-indigo-700/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-indigo-300/40 focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 font-medium transition" />
                @error('password')
                    <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-xs font-semibold text-indigo-200 uppercase tracking-wider mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" class="w-full bg-indigo-950/70 border border-indigo-700/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-indigo-300/40 focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 font-medium transition" />
                @error('password_confirmation')
                    <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button (Cyan Pill Button Matching Reference Image) -->
            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 px-4 bg-cyan-400 hover:bg-cyan-300 text-indigo-950 font-extrabold text-xs rounded-xl shadow-lg shadow-cyan-400/25 transition duration-200 uppercase tracking-wider" data-test="register-user-button">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <!-- Switch to Login Link -->
        <div class="pt-3 border-t border-indigo-800/40 text-center text-xs text-indigo-200/80">
            <span>Sudah memiliki akun?</span>
            <a href="{{ route('login') }}" class="text-cyan-300 hover:underline font-bold ml-1" wire:navigate>
                Masuk di Sini
            </a>
        </div>
    </div>
</x-layouts::auth>
