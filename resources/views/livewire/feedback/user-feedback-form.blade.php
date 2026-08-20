<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="pb-4 border-b border-zinc-200 dark:border-zinc-700">
        <flux:heading size="xl" level="1">Penilaian Aplikasi BarberSaaS</flux:heading>
        <flux:subheading>Berikan penilaian dan ulasan pengalaman Anda menggunakan platform BarberSaaS.</flux:subheading>
    </div>

    @if($success_message)
        <flux:badge color="emerald" size="lg" class="w-full justify-between p-4">
            <span>{{ $success_message }}</span>
        </flux:badge>
    @endif

    @if($hasSubmitted && $userFeedback)
        <!-- Already Submitted Card -->
        <flux:card class="p-6 space-y-4 border border-zinc-200 dark:border-zinc-700 text-center">
            <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 mx-auto flex items-center justify-center">
                <flux:icon name="check-circle" class="size-6" />
            </div>
            <div class="space-y-1">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white">Penilaian Anda Telah Diterima</h3>
                <p class="text-xs text-zinc-500">Terima kasih atas masukan yang Anda berikan untuk pengembangan aplikasi BarberSaaS.</p>
            </div>
            
            <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 inline-block text-left max-w-md w-full space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-zinc-700 dark:text-zinc-300">
                    <span>Bintang Penilaian:</span>
                    <div class="flex items-center gap-1 text-amber-500">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="size-4 fill-current {{ $i <= $userFeedback->rating ? 'text-amber-500' : 'text-zinc-300 dark:text-zinc-700' }}" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        @endfor
                        <span class="ml-1 text-zinc-900 dark:text-white font-mono font-bold">({{ $userFeedback->rating }}/5)</span>
                    </div>
                </div>
                @if($userFeedback->feedback_text)
                    <div class="text-xs text-zinc-600 dark:text-zinc-400 border-t border-zinc-200 dark:border-zinc-800 pt-2 italic">
                        "{{ $userFeedback->feedback_text }}"
                    </div>
                @endif
                <div class="text-[10px] text-zinc-400 font-mono text-right pt-1">
                    Dikirim: {{ $userFeedback->created_at->format('d/m/Y H:i') }} WIB
                </div>
            </div>
        </flux:card>
    @else
        <!-- Rating Form -->
        <flux:card class="p-6 space-y-6 border border-zinc-200 dark:border-zinc-700">
            <form wire:submit.prevent="submitFeedback" class="space-y-6">
                <!-- Interactive Star Rating Picker -->
                <div>
                    <label class="block text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider mb-2">
                        Pilih Tingkat Kepuasan (1-5 Bintang)
                    </label>
                    <div class="flex items-center gap-2">
                        @for($star = 1; $star <= 5; $star++)
                            <button type="button" wire:click="$set('rating', {{ $star }})" class="p-1 focus:outline-none transition-transform hover:scale-110 cursor-pointer">
                                <svg class="size-8 {{ $star <= $rating ? 'text-amber-500 fill-current' : 'text-zinc-300 dark:text-zinc-700 fill-current' }}" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </button>
                        @endfor
                        <span class="ml-2 font-mono font-bold text-sm text-zinc-900 dark:text-white">
                            {{ $rating }} / 5 Bintang
                        </span>
                    </div>
                    @error('rating') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Feedback Text -->
                <div>
                    <flux:label>Ulasan & Masukan Pengalaman (Opsional)</flux:label>
                    <flux:textarea wire:model="feedback_text" rows="4" placeholder="Tuliskan pengalaman atau saran Anda mengenai fitur aplikasi..." />
                    <flux:description class="mt-1">Masukan Anda sangat berharga untuk meningkatkan kualitas layanan platform kami.</flux:description>
                    @error('feedback_text') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                        <flux:icon name="paper-airplane" class="size-4" />
                        <span>Kirim Penilaian Aplikasi</span>
                    </button>
                </div>
            </form>
        </flux:card>
    @endif
</div>
