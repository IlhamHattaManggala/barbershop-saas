<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'phone',
        'address',
        'logo',
        'qris_image',
        'bank_info',
        'description',
        'theme',
        'primary_color',
        'hero_title',
        'hero_subtitle',
        'hero_tagline',
        'button_style',
        'hero_banner',
        'purchased_themes',
        'show_wa_button',
        'layout_pos',
        'show_services',
        'show_products',
        'section_order',
        'footer_text',
        'footer_copyright',
        'status',
        'barber_commission_percentage',
        'cashier_commission_percentage',
        'theme_config',
        'receipt_settings',
        'wa_settings',
    ];

    protected $casts = [
        'section_order' => 'array',
        'purchased_themes' => 'array',
        'theme_config' => 'array',
        'receipt_settings' => 'array',
        'wa_settings' => 'array',
        'barber_commission_percentage' => 'decimal:2',
        'cashier_commission_percentage' => 'decimal:2',
    ];

    public function hasPurchasedTheme(string $themeSlug): bool
    {
        $theme = Theme::where('slug', $themeSlug)->first();
        if (! $theme || $theme->isFree()) {
            return true;
        }

        $purchased = is_array($this->purchased_themes) ? $this->purchased_themes : (json_decode($this->purchased_themes ?? '[]', true) ?: []);

        return in_array($themeSlug, $purchased);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function getThemeConfigArray(): array
    {
        $config = $this->theme_config;
        if (is_string($config)) {
            $config = json_decode($config, true);
        }

        if (! is_array($config) || empty($config)) {
            return [
                'builder_mode' => 'block',
                'settings' => [
                    'primary_color' => $this->primary_color ?? 'amber',
                    'font_family' => 'Plus Jakarta Sans',
                    'button_style' => $this->button_style ?? 'rounded-xl',
                    'layout_pos' => $this->layout_pos ?? 'left',
                    'show_wa_button' => (bool) ($this->show_wa_button ?? true),
                    'header' => [
                        'show_logo' => true,
                        'show_status_badge' => true,
                        'status_text' => 'Buka Hari Ini',
                    ],
                    'footer' => [
                        'text' => $this->footer_text ?? ($this->address ?? 'Buka Setiap Hari: 09.00 - 21.00 WIB'),
                        'copyright' => $this->footer_copyright ?? ('© '.date('Y').' '.$this->name.'. All rights reserved.'),
                    ],
                ],
                'blocks' => [
                    [
                        'id' => 'blk_hero',
                        'type' => 'hero',
                        'name' => 'Hero Banner & Tagline',
                        'enabled' => true,
                        'config' => [
                            'title' => $this->hero_title ?? $this->name,
                            'subtitle' => $this->hero_subtitle ?? ($this->description ?? 'Layanan potong rambut pria presisi dan perawatan jenggot.'),
                            'tagline' => $this->hero_tagline ?? 'Toko Buka • Siap Menerima Reservasi Waktu Pangkas',
                            'banner_image' => $this->hero_banner ?? '',
                        ],
                    ],
                    [
                        'id' => 'blk_services',
                        'type' => 'services',
                        'name' => 'Katalog Layanan Potong',
                        'enabled' => (bool) ($this->show_services ?? true),
                        'config' => [
                            'title' => 'Layanan Utama',
                            'subtitle' => 'Pilihan paket cukur dan perawatan gaya rambut terbaik.',
                        ],
                    ],
                    [
                        'id' => 'blk_booking',
                        'type' => 'booking',
                        'name' => 'Form Reservasi Online',
                        'enabled' => true,
                        'config' => [
                            'title' => 'Reservasi Jadwal Pangkas',
                            'subtitle' => 'Pilih slot jam dan barber favorit tanpa perlu mengantre.',
                        ],
                    ],
                    [
                        'id' => 'blk_products',
                        'type' => 'products',
                        'name' => 'Katalog Produk Pomade & Haircare',
                        'enabled' => (bool) ($this->show_products ?? true),
                        'config' => [
                            'title' => 'Produk Grooming Pria',
                            'subtitle' => 'Koleksi pomade dan produk perawatan pilihan barber profesional.',
                        ],
                    ],
                    [
                        'id' => 'blk_barbers',
                        'type' => 'barbers',
                        'name' => 'Tim Barber & Kapster',
                        'enabled' => true,
                        'config' => [
                            'title' => 'Tim Barber Profesional',
                            'subtitle' => 'Kapster berpengalaman yang siap memberikan potongan terbaik.',
                        ],
                    ],
                    [
                        'id' => 'blk_why_us',
                        'type' => 'why_us',
                        'name' => 'Keunggulan & Fasilitas',
                        'enabled' => true,
                        'config' => [
                            'title' => 'Mengapa Memilih Kami',
                            'subtitle' => 'Kenyamanan dan kepuasan pelanggan adalah prioritas utama.',
                        ],
                    ],
                    [
                        'id' => 'blk_contact',
                        'type' => 'contact',
                        'name' => 'Lokasi & Jam Operasional',
                        'enabled' => true,
                        'config' => [
                            'title' => 'Lokasi Outlet & Kontak',
                            'subtitle' => 'Kunjungi barbershop kami atau hubungi via WhatsApp.',
                        ],
                    ],
                ],
            ];
        }

        return $config;
    }
}
