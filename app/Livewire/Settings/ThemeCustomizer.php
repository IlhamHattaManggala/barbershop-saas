<?php

namespace App\Livewire\Settings;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithFileUploads;

class ThemeCustomizer extends Component
{
    use WithFileUploads;

    // Active Builder Mode: 'block' or 'classic'
    public string $builder_mode = 'block';

    // Active Classic Builder Tab
    public string $classic_tab = 'colors'; // colors, typography, header, hero, sections, layout, footer

    // Active Device Preview Mode
    public string $device_view = 'desktop'; // desktop, mobile

    // Master Theme Configuration Array (Shared between Block & Classic Builder)
    public array $theme_config = [];

    // Blocks list (Reflected in theme_config['blocks'])
    public array $blocks = [];

    // Global Settings (Reflected in theme_config['settings'])
    public array $settings = [];

    // Block Config Modal / Edit state
    public bool $show_add_block_modal = false;

    public ?int $editing_block_index = null;

    public array $editing_block_config = [];

    // File Uploads
    public $new_hero_banner = null;

    public string $current_hero_banner = '';

    // Status & Preview
    public int $preview_key;

    public string $success_message = '';

    // Reusable Block Library Definitions
    public array $available_block_types = [
        'hero' => [
            'name' => 'Hero Banner & Tagline',
            'icon' => 'sparkles',
            'desc' => 'Header utama dengan judul, deskripsi, tagline status, dan gambar banner.',
        ],
        'services' => [
            'name' => 'Katalog Layanan Potong',
            'icon' => 'scissors',
            'desc' => 'Daftar layanan cukur, warna, dan paket grooming beserta harga dan durasi.',
        ],
        'booking' => [
            'name' => 'Form Reservasi Online',
            'icon' => 'calendar',
            'desc' => 'Widget interaktif untuk pelanggan memilih layanan, barber, tanggal & jam.',
        ],
        'products' => [
            'name' => 'Katalog Produk & Haircare',
            'icon' => 'shopping-bag',
            'desc' => 'Koleksi produk pomade, clay, serum, dan perawatan rambut.',
        ],
        'barbers' => [
            'name' => 'Tim Barber & Kapster',
            'icon' => 'users',
            'desc' => 'Profil kapster studio dengan foto dan deskripsi keahlian.',
        ],
        'why_us' => [
            'name' => 'Fasilitas & Keunggulan',
            'icon' => 'star',
            'desc' => 'Highlight fasilitas outlet (Minuman Gratis, Ruang Tunggu Ber-AC, Steril).',
        ],
        'contact' => [
            'name' => 'Lokasi Outlet & Kontak',
            'icon' => 'map-pin',
            'desc' => 'Informasi alamat lengkap, jam operasional, dan tombol WhatsApp.',
        ],
        'footer' => [
            'name' => 'Footer & Hak Cipta',
            'icon' => 'layout',
            'desc' => 'Bagian kaki halaman dengan alamat dan hak cipta toko.',
        ],
    ];

    public function mount()
    {
        $this->preview_key = time();
        $tenant = auth()->user()->tenant;

        if ($tenant) {
            $config = $tenant->getThemeConfigArray();
            $this->theme_config = $config;
            $this->builder_mode = $config['builder_mode'] ?? 'block';
            $this->settings = $config['settings'] ?? [];
            $this->blocks = $config['blocks'] ?? [];
            $this->current_hero_banner = $tenant->hero_banner ?? '';

            // Ensure settings structure defaults if missing
            $this->syncSettingsDefaults($tenant);
        }
    }

    private function syncSettingsDefaults(Tenant $tenant)
    {
        if (empty($this->settings['primary_color'])) {
            $this->settings['primary_color'] = $tenant->primary_color ?? 'amber';
        }
        if (empty($this->settings['font_family'])) {
            $this->settings['font_family'] = 'Plus Jakarta Sans';
        }
        if (empty($this->settings['button_style'])) {
            $this->settings['button_style'] = $tenant->button_style ?? 'rounded-xl';
        }
        if (empty($this->settings['layout_pos'])) {
            $this->settings['layout_pos'] = $tenant->layout_pos ?? 'left';
        }
        if (! isset($this->settings['show_wa_button'])) {
            $this->settings['show_wa_button'] = (bool) ($tenant->show_wa_button ?? true);
        }
        if (! isset($this->settings['header'])) {
            $this->settings['header'] = [
                'show_logo' => true,
                'show_status_badge' => true,
                'status_text' => 'Buka Hari Ini',
            ];
        }
        if (! isset($this->settings['footer'])) {
            $this->settings['footer'] = [
                'text' => $tenant->footer_text ?? ($tenant->address ?? 'Buka Setiap Hari: 09.00 - 21.00 WIB'),
                'copyright' => $tenant->footer_copyright ?? ('© '.date('Y').' '.$tenant->name.'. All rights reserved.'),
            ];
        }
    }

    // Switch Builder Mode: 'block' or 'classic'
    public function setBuilderMode(string $mode)
    {
        if (in_array($mode, ['block', 'classic'])) {
            $this->builder_mode = $mode;
            $this->theme_config['builder_mode'] = $mode;
            $this->updateDraftPreview();
        }
    }

    // Switch Classic Tab
    public function setClassicTab(string $tab)
    {
        $this->classic_tab = $tab;
    }

    // Switch Device Preview
    public function setDeviceView(string $mode)
    {
        $this->device_view = in_array($mode, ['desktop', 'mobile']) ? $mode : 'desktop';
    }

    // --- BLOCK BUILDER ACTIONS ---

    public function openAddBlockModal()
    {
        $this->show_add_block_modal = true;
    }

    public function closeAddBlockModal()
    {
        $this->show_add_block_modal = false;
    }

    public function addBlock(string $type)
    {
        if (! isset($this->available_block_types[$type])) {
            return;
        }

        $def = $this->available_block_types[$type];
        $tenant = auth()->user()->tenant;

        $newBlock = [
            'id' => 'blk_'.$type.'_'.time(),
            'type' => $type,
            'name' => $def['name'],
            'enabled' => true,
            'config' => $this->getDefaultConfigForBlockType($type, $tenant),
        ];

        $this->blocks[] = $newBlock;
        $this->syncBlocksToSettings();
        $this->closeAddBlockModal();
        $this->updateDraftPreview();
    }

    public function removeBlock(int $index)
    {
        if (isset($this->blocks[$index])) {
            array_splice($this->blocks, $index, 1);
            $this->syncBlocksToSettings();
            $this->updateDraftPreview();
        }
    }

    public function reorderBlocks(int $fromIndex, int $toIndex)
    {
        if ($fromIndex !== $toIndex && isset($this->blocks[$fromIndex]) && isset($this->blocks[$toIndex])) {
            $movedItem = array_splice($this->blocks, $fromIndex, 1)[0];
            array_splice($this->blocks, $toIndex, 0, [$movedItem]);
            $this->syncBlocksToSettings();
            $this->updateDraftPreview();
        }
    }

    public function moveBlockUp(int $index)
    {
        if ($index > 0 && isset($this->blocks[$index])) {
            $temp = $this->blocks[$index - 1];
            $this->blocks[$index - 1] = $this->blocks[$index];
            $this->blocks[$index] = $temp;
            $this->syncBlocksToSettings();
            $this->updateDraftPreview();
        }
    }

    public function moveBlockDown(int $index)
    {
        if ($index < count($this->blocks) - 1 && isset($this->blocks[$index])) {
            $temp = $this->blocks[$index + 1];
            $this->blocks[$index + 1] = $this->blocks[$index];
            $this->blocks[$index] = $temp;
            $this->syncBlocksToSettings();
            $this->updateDraftPreview();
        }
    }

    public function toggleBlockEnabled(int $index)
    {
        if (isset($this->blocks[$index])) {
            $this->blocks[$index]['enabled'] = ! ($this->blocks[$index]['enabled'] ?? true);
            $this->syncBlocksToSettings();
            $this->updateDraftPreview();
        }
    }

    public function editBlockConfig(int $index)
    {
        if (isset($this->blocks[$index])) {
            $this->editing_block_index = $index;
            $this->editing_block_config = $this->blocks[$index]['config'] ?? [];
        }
    }

    public function closeEditBlockModal()
    {
        $this->editing_block_index = null;
        $this->editing_block_config = [];
    }

    public function saveBlockConfig()
    {
        if ($this->editing_block_index !== null && isset($this->blocks[$this->editing_block_index])) {
            $this->blocks[$this->editing_block_index]['config'] = $this->editing_block_config;

            // Sync hero block config back to hero settings if type is hero
            if ($this->blocks[$this->editing_block_index]['type'] === 'hero') {
                if (isset($this->editing_block_config['title'])) {
                    $this->settings['hero_title'] = $this->editing_block_config['title'];
                }
                if (isset($this->editing_block_config['subtitle'])) {
                    $this->settings['hero_subtitle'] = $this->editing_block_config['subtitle'];
                }
                if (isset($this->editing_block_config['tagline'])) {
                    $this->settings['hero_tagline'] = $this->editing_block_config['tagline'];
                }
            }

            $this->closeEditBlockModal();
            $this->updateDraftPreview();
        }
    }

    private function getDefaultConfigForBlockType(string $type, $tenant): array
    {
        return match ($type) {
            'hero' => [
                'title' => $tenant->hero_title ?? ($tenant->name ?? 'Barbershop Studio'),
                'subtitle' => $tenant->hero_subtitle ?? 'Layanan potong rambut pria presisi dan perawatan jenggot.',
                'tagline' => $tenant->hero_tagline ?? 'Toko Buka • Siap Menerima Reservasi Waktu Pangkas',
            ],
            'services' => [
                'title' => 'Layanan Utama',
                'subtitle' => 'Pilihan cukur dan perawatan gaya rambut pria terbaik.',
            ],
            'booking' => [
                'title' => 'Reservasi Jadwal Pangkas',
                'subtitle' => 'Pilih jam & barber favorit tanpa mengantre.',
            ],
            'products' => [
                'title' => 'Produk Grooming Pria',
                'subtitle' => 'Koleksi pomade & perawatan rambut profesional.',
            ],
            'barbers' => [
                'title' => 'Tim Kapster Kami',
                'subtitle' => 'Barber profesional dengan keahlian cukur presisi.',
            ],
            'why_us' => [
                'title' => 'Keunggulan Studio',
                'subtitle' => 'Ruang tunggu nyaman, kopi gratis, & alat selalu steril.',
            ],
            'contact' => [
                'title' => 'Lokasi Outlet & Kontak',
                'subtitle' => $tenant->address ?? 'Kunjungi barbershop kami atau hubungi via WhatsApp.',
            ],
            'footer' => [
                'text' => $tenant->footer_text ?? 'Buka Setiap Hari: 09.00 - 21.00 WIB',
                'copyright' => $tenant->footer_copyright ?? ('© '.date('Y').' '.($tenant->name ?? 'Barbershop').'. All rights reserved.'),
            ],
            default => [],
        };
    }

    // Sync blocks state to classic section settings & vice versa
    private function syncBlocksToSettings()
    {
        $enabledTypes = [];
        $order = [];
        foreach ($this->blocks as $blk) {
            if ($blk['enabled'] ?? true) {
                $enabledTypes[] = $blk['type'];
                $order[] = $blk['type'];
            }
        }

        $this->settings['show_services'] = in_array('services', $enabledTypes);
        $this->settings['show_products'] = in_array('products', $enabledTypes);
        $this->settings['show_barbers'] = in_array('barbers', $enabledTypes);
        $this->settings['show_why_us'] = in_array('why_us', $enabledTypes);
        $this->settings['show_contact'] = in_array('contact', $enabledTypes);
        $this->settings['section_order'] = $order;
    }

    // --- CLASSIC BUILDER UPDATES ---

    public function updatedSettings()
    {
        $this->updateDraftPreview();
    }

    public function updatedNewHeroBanner()
    {
        if ($this->new_hero_banner) {
            try {
                $tenant = auth()->user()->tenant;
                if ($tenant) {
                    $ext = method_exists($this->new_hero_banner, 'getClientOriginalExtension') ? $this->new_hero_banner->getClientOriginalExtension() : 'png';
                    $filename = 'hero_banner_'.$tenant->id.'_'.time().'.'.$ext;
                    $this->new_hero_banner->storeAs('tenant_banners', $filename, 'public');
                    $bannerPath = 'storage/tenant_banners/'.$filename;

                    $tenant->update(['hero_banner' => $bannerPath]);
                    $this->current_hero_banner = $bannerPath;
                }
            } catch (\Throwable $e) {
                // Ignore gracefully
            } finally {
                $this->reset('new_hero_banner');
            }
        }

        $this->updateDraftPreview();
    }

    public function updateDraftPreview()
    {
        $this->preview_key = time();
    }

    // --- SAVE AND PUBLISH ---

    public function saveCustomization()
    {
        $tenant = auth()->user()->tenant;
        if (! $tenant) {
            return;
        }

        // Compile complete theme configuration object
        $fullConfig = [
            'builder_mode' => $this->builder_mode,
            'settings' => $this->settings,
            'blocks' => $this->blocks,
        ];

        $heroTitle = $tenant->name;
        $heroSubtitle = $tenant->description ?? '';
        $heroTagline = 'Toko Buka • Siap Menerima Reservasi Waktu Pangkas';

        // Extract hero block info if available
        foreach ($this->blocks as $blk) {
            if ($blk['type'] === 'hero' && isset($blk['config'])) {
                $heroTitle = $blk['config']['title'] ?? $heroTitle;
                $heroSubtitle = $blk['config']['subtitle'] ?? $heroSubtitle;
                $heroTagline = $blk['config']['tagline'] ?? $heroTagline;
                break;
            }
        }

        // Section order array from active blocks
        $sectionOrder = array_values(array_map(fn ($b) => $b['type'], array_filter($this->blocks, fn ($b) => $b['enabled'] ?? true)));

        // Update database: JSON theme_config + Top-level attributes for 100% backwards compatibility
        $tenant->update([
            'theme_config' => $fullConfig,
            'primary_color' => $this->settings['primary_color'] ?? 'amber',
            'button_style' => $this->settings['button_style'] ?? 'rounded-xl',
            'layout_pos' => $this->settings['layout_pos'] ?? 'left',
            'show_wa_button' => (bool) ($this->settings['show_wa_button'] ?? true),
            'show_services' => (bool) ($this->settings['show_services'] ?? true),
            'show_products' => (bool) ($this->settings['show_products'] ?? true),
            'hero_title' => $heroTitle,
            'hero_subtitle' => $heroSubtitle,
            'hero_tagline' => $heroTagline,
            'hero_banner' => $this->current_hero_banner,
            'section_order' => json_encode($sectionOrder),
            'footer_text' => $this->settings['footer']['text'] ?? '',
            'footer_copyright' => $this->settings['footer']['copyright'] ?? '',
        ]);

        $this->success_message = 'Kustomisasi Tema ('.strtoupper($this->builder_mode).' BUILDER) Berhasil Disimpan & Dipublikasikan!';
        $this->updateDraftPreview();
    }

    public function render()
    {
        $tenant = auth()->user()->tenant;
        $slug = $tenant->slug ?? 'gentlemen-barber';
        $themeName = $tenant->theme ?? 'classic-light';

        // Extract current hero values for preview query string
        $heroTitle = $this->settings['hero_title'] ?? ($tenant->hero_title ?? $tenant->name);
        $heroSubtitle = $this->settings['hero_subtitle'] ?? ($tenant->hero_subtitle ?? '');
        $heroTagline = $this->settings['hero_tagline'] ?? ($tenant->hero_tagline ?? '');

        foreach ($this->blocks as $blk) {
            if ($blk['type'] === 'hero' && isset($blk['config'])) {
                $heroTitle = $blk['config']['title'] ?? $heroTitle;
                $heroSubtitle = $blk['config']['subtitle'] ?? $heroSubtitle;
                $heroTagline = $blk['config']['tagline'] ?? $heroTagline;
                break;
            }
        }

        $activeBlockTypes = array_values(array_map(fn ($b) => $b['type'], array_filter($this->blocks, fn ($b) => $b['enabled'] ?? true)));

        // Build Iframe Preview Query Parameters
        $previewUrl = url($slug).'?'.http_build_query([
            'preview' => 1,
            'mode' => $this->builder_mode,
            'color' => $this->settings['primary_color'] ?? 'amber',
            'title' => $heroTitle,
            'subtitle' => $heroSubtitle,
            'tagline' => $heroTagline,
            'banner' => $this->current_hero_banner,
            'font' => $this->settings['font_family'] ?? 'Plus Jakarta Sans',
            'btn' => $this->settings['button_style'] ?? 'rounded-xl',
            'pos' => $this->settings['layout_pos'] ?? 'left',
            'wa' => ($this->settings['show_wa_button'] ?? true) ? 1 : 0,
            'srv' => ($this->settings['show_services'] ?? true) ? 1 : 0,
            'prd' => ($this->settings['show_products'] ?? true) ? 1 : 0,
            'order' => implode(',', $activeBlockTypes),
            'ftext' => $this->settings['footer']['text'] ?? '',
            'fcopy' => $this->settings['footer']['copyright'] ?? '',
            'v' => $this->preview_key,
        ]);

        return view('livewire.settings.theme-customizer', [
            'tenant' => $tenant,
            'slug' => $slug,
            'themeName' => $themeName,
            'previewUrl' => $previewUrl,
        ])->layout('layouts.app');
    }
}
