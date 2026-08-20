<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header with Back Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-zinc-200 dark:border-zinc-800">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('superadmin.themes') }}" wire:navigate class="text-xs text-zinc-600 dark:text-zinc-400 font-semibold hover:underline flex items-center gap-1">
                    &larr; Kembali ke Kelola Tema
                </a>
            </div>
            <flux:heading size="xl" level="1">Panduan Pembuatan Tema Web Barbershop</flux:heading>
            <flux:subheading>Pedoman teknis, arsitektur Blade, variabel kontrak, dan standar pembuatan tema baru di platform SaaS.</flux:subheading>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.themes') }}" wire:navigate>
                <flux:button variant="primary" icon="rectangle-stack" class="bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white text-white">
                    Kelola Katalog Tema
                </flux:button>
            </a>
        </div>
    </div>

    <!-- Quick Overview Card -->
    <flux:card class="p-6 bg-zinc-900 text-white space-y-3 shadow-sm rounded-2xl border border-zinc-800">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-zinc-800 border border-zinc-700 flex items-center justify-center text-zinc-300">
                <flux:icon icon="code-bracket" class="size-5" />
            </div>
            <div>
                <h3 class="text-lg font-bold">Arsitektur Tema Barbershop SaaS</h3>
                <p class="text-xs text-zinc-400">Setiap tema adalah file Blade terisolasi di folder <code class="bg-zinc-800 px-1.5 py-0.5 rounded font-mono text-zinc-200">resources/views/themes/{slug}.blade.php</code> yang menerima kontrak data dari komponen <code class="bg-zinc-800 px-1.5 py-0.5 rounded font-mono text-zinc-200">ShopBookingPage</code>.</p>
            </div>
        </div>
    </flux:card>

    <!-- Step 1: File Location & Naming Standard -->
    <flux:card class="p-6 space-y-4 border border-zinc-200 dark:border-zinc-800">
        <div class="flex items-center gap-3 border-b border-zinc-200 dark:border-zinc-800 pb-3">
            <span class="w-7 h-7 rounded-full bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-xs font-bold flex items-center justify-center">1</span>
            <flux:heading size="lg">Struktur File & Penamaan Tema</flux:heading>
        </div>

        <div class="text-xs space-y-3 text-zinc-700 dark:text-zinc-300 leading-relaxed">
            <p>Untuk membuat tema baru di platform ini, ikuti aturan penamaan lokasi file berikut:</p>
            
            <div class="bg-zinc-950 text-zinc-100 p-4 rounded-xl font-mono text-xs overflow-x-auto space-y-2 border border-zinc-800">
                <div class="text-zinc-500">// Lokasi file Blade untuk tema baru:</div>
                <div class="text-zinc-200">resources/views/themes/<span class="text-zinc-400">{slug-tema}</span>.blade.php</div>
                <div class="text-zinc-500 mt-2">// Contoh:</div>
                <div>resources/views/themes/<span class="text-zinc-400">urban-street</span>.blade.php</div>
                <div>resources/views/themes/<span class="text-zinc-400">luxury-gold</span>.blade.php</div>
            </div>

            <p class="text-zinc-500">Setelah file dibuat, daftarkan tema baru tersebut di halaman <a href="{{ route('superadmin.themes') }}" wire:navigate class="text-zinc-900 dark:text-zinc-100 underline font-semibold">Kelola Tema</a> dengan mengisi field <strong>Slug Identifier</strong> dan <strong>Blade View Path</strong> (<code class="bg-zinc-100 dark:bg-zinc-800 px-1 py-0.5 rounded font-mono">themes.urban-street</code>).</p>
        </div>
    </flux:card>

    <!-- Step 2: Contract Variables Reference -->
    <flux:card class="p-6 space-y-4 border border-zinc-200 dark:border-zinc-800">
        <div class="flex items-center gap-3 border-b border-zinc-200 dark:border-zinc-800 pb-3">
            <span class="w-7 h-7 rounded-full bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-xs font-bold flex items-center justify-center">2</span>
            <flux:heading size="lg">Daftar Variabel Kontrak yang Tersedia di Blade</flux:heading>
        </div>

        <div class="text-xs space-y-4">
            <p class="text-zinc-600 dark:text-zinc-400">Komponen Livewire utama secara otomatis mengirimkan variabel data dan properti berikut ke dalam template Blade Anda:</p>

            <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-800 rounded-xl">
                <table class="w-full text-left text-xs">
                    <thead class="bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-bold border-b border-zinc-200 dark:border-zinc-700">
                        <tr>
                            <th class="p-3">Nama Variabel</th>
                            <th class="p-3">Tipe Data</th>
                            <th class="p-3">Keterangan & Penggunaan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 font-mono text-[11px]">
                        <tr>
                            <td class="p-3 font-bold text-zinc-900 dark:text-zinc-100">$tenant</td>
                            <td class="p-3 text-zinc-500">App\Models\Tenant</td>
                            <td class="p-3 text-zinc-700 dark:text-zinc-300 font-sans">Objek data toko ($tenant->name, $tenant->logo, $tenant->phone, $tenant->primary_color, $tenant->hero_title, $tenant->hero_subtitle, dll)</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-bold text-zinc-900 dark:text-zinc-100">$services</td>
                            <td class="p-3 text-zinc-500">Collection&lt;Service&gt;</td>
                            <td class="p-3 text-zinc-700 dark:text-zinc-300 font-sans">Daftar layanan potong/styling yang aktif ($s->name, $s->price, $s->duration_minutes)</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-bold text-zinc-900 dark:text-zinc-100">$barbers</td>
                            <td class="p-3 text-zinc-500">Collection&lt;User&gt;</td>
                            <td class="p-3 text-zinc-700 dark:text-zinc-300 font-sans">Daftar staf kapster/barber toko yang tersedia untuk dipilh pelanggan</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-bold text-zinc-900 dark:text-zinc-100">$products</td>
                            <td class="p-3 text-zinc-500">Collection&lt;Product&gt;</td>
                            <td class="p-3 text-zinc-700 dark:text-zinc-300 font-sans">Katalog produk perawatan rambut (pomade, serum, dll)</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-bold text-zinc-900 dark:text-zinc-100">wire:model="customer_name"</td>
                            <td class="p-3 text-zinc-500">Livewire Input</td>
                            <td class="p-3 text-zinc-700 dark:text-zinc-300 font-sans">Binding form nama pelanggan pada form booking</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-bold text-zinc-900 dark:text-zinc-100">wire:model="customer_phone"</td>
                            <td class="p-3 text-zinc-500">Livewire Input</td>
                            <td class="p-3 text-zinc-700 dark:text-zinc-300 font-sans">Binding form nomor WhatsApp pelanggan</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-bold text-zinc-900 dark:text-zinc-100">wire:submit="createBooking"</td>
                            <td class="p-3 text-zinc-500">Livewire Action</td>
                            <td class="p-3 text-zinc-700 dark:text-zinc-300 font-sans">Action pengiriman form untuk menyimpan data reservasi pelanggan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </flux:card>

    <!-- Step 3: Supporting Dynamic Color & Customization Rules -->
    <flux:card class="p-6 space-y-4 border border-zinc-200 dark:border-zinc-800">
        <div class="flex items-center gap-3 border-b border-zinc-200 dark:border-zinc-800 pb-3">
            <span class="w-7 h-7 rounded-full bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-xs font-bold flex items-center justify-center">3</span>
            <flux:heading size="lg">Standar Kustomisasi Warna & Urutan Seksi (Section Order)</flux:heading>
        </div>

        <div class="text-xs space-y-4 text-zinc-700 dark:text-zinc-300 leading-relaxed">
            <div class="space-y-2">
                <h4 class="font-bold text-zinc-900 dark:text-zinc-100">A. Warna Primer Dinamis ($tenant->primary_color)</h4>
                <p>Gunakan kelas Tailwind dinamis berdasarkan variabel <code class="bg-zinc-100 dark:bg-zinc-800 px-1 py-0.5 rounded font-mono text-zinc-800 dark:text-zinc-200">$tenant->primary_color</code>.</p>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-zinc-900 dark:text-zinc-100">B. Urutan Seksi Dinamis ($tenant->section_order)</h4>
                <p>Tenant dapat mengurutkan seksi di Live Customizer. Gunakan pengulangan <code class="bg-zinc-100 dark:bg-zinc-800 px-1 py-0.5 rounded font-mono">@@foreach($tenant->section_order as $section)</code> untuk me-render seksi secara fleksibel:</p>

                @verbatim
                <div class="bg-zinc-950 text-zinc-100 p-4 rounded-xl font-mono text-xs overflow-x-auto border border-zinc-800">
                    <div>@foreach($tenant->section_order ?? ['services', 'booking', 'products'] as $section)</div>
                    <div class="pl-4 text-zinc-300">@if($section === 'services' && $tenant->show_services)</div>
                    <div class="pl-8 text-zinc-500">&lt;!-- Section Katalog Layanan --&gt;</div>
                    <div class="pl-4 text-zinc-300">@elseif($section === 'booking')</div>
                    <div class="pl-8 text-zinc-500">&lt;!-- Section Form Reservasi --&gt;</div>
                    <div class="pl-4 text-zinc-300">@elseif($section === 'products' && $tenant->show_products)</div>
                    <div class="pl-8 text-zinc-500">&lt;!-- Section Katalog Produk --&gt;</div>
                    <div class="pl-4 text-zinc-300">@endif</div>
                    <div>@endforeach</div>
                </div>
                @endverbatim
            </div>
        </div>
    </flux:card>

    <!-- Step 4: Starter Boilerplate Code -->
    <flux:card class="p-6 space-y-4 border border-zinc-200 dark:border-zinc-800">
        <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-800 pb-3">
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-full bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-xs font-bold flex items-center justify-center">4</span>
                <flux:heading size="lg">Starter Template Blade Tema (Copy-Paste Ready)</flux:heading>
            </div>
        </div>

        <div class="text-xs space-y-2">
            <p class="text-zinc-600 dark:text-zinc-400">Anda dapat menduplikasi struktur dasar ini untuk mulai membangun tema baru:</p>

            @verbatim
            <div class="bg-zinc-950 text-zinc-100 p-5 rounded-2xl font-mono text-[11px] overflow-x-auto leading-relaxed border border-zinc-800 space-y-1 shadow-inner">
                <div class="text-zinc-500">&lt;!-- resources/views/themes/custom-theme.blade.php --&gt;</div>
                <div class="text-zinc-400">&lt;div class="min-h-screen bg-zinc-950 text-white font-sans"&gt;</div>
                <div class="pl-4 text-zinc-400">&lt;!-- HERO BANNER --&gt;</div>
                <div class="pl-4 text-zinc-300">&lt;header class="text-center py-16 px-4 bg-zinc-900 border-b border-zinc-800"&gt;</div>
                <div class="pl-8 text-zinc-100">&lt;h1 class="text-4xl font-extrabold"&gt;{{ $tenant->hero_title ?? $tenant->name }}&lt;/h1&gt;</div>
                <div class="pl-8 text-zinc-400">&lt;p class="mt-2 text-zinc-400 text-sm max-w-xl mx-auto"&gt;{{ $tenant->hero_subtitle }}&lt;/p&gt;</div>
                <div class="pl-4 text-zinc-300">&lt;/header&gt;</div>
                <br>
                <div class="pl-4 text-zinc-400">&lt;!-- DYNAMIC SECTIONS LOOP --&gt;</div>
                <div class="pl-4 text-zinc-300">@foreach($tenant->section_order ?? ['services', 'booking', 'products'] as $section)</div>
                <div class="pl-8 text-zinc-300">@if($section === 'services' && $tenant->show_services)</div>
                <div class="pl-12 text-zinc-500">&lt;!-- Services Grid --&gt;</div>
                <div class="pl-8 text-zinc-300">@elseif($section === 'booking')</div>
                <div class="pl-12 text-zinc-500">&lt;!-- Booking Form Wizard --&gt;</div>
                <div class="pl-8 text-zinc-300">@endif</div>
                <div class="pl-4 text-zinc-300">@endforeach</div>
                <div class="text-zinc-400">&lt;/div&gt;</div>
            </div>
            @endverbatim
        </div>
    </flux:card>
</div>
