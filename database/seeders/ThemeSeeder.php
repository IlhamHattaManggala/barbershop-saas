<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            [
                'name' => 'Gentlemen Classic',
                'slug' => 'gentlemen-classic',
                'type' => 'free',
                'price' => 0,
                'description' => 'Desain bersih dan terang dengan estetika potong rambut klasik.',
                'thumbnail' => 'images/themes/classic-light.jpg',
                'blade_view' => 'themes.gentlemen-classic',
                'is_active' => true,
            ],
            [
                'name' => 'Modern Minimalist',
                'slug' => 'modern-minimalist',
                'type' => 'free',
                'price' => 0,
                'description' => 'Tampilan Scandinavian Studio UI yang bersih, tenang, dan terstruktur rapi.',
                'thumbnail' => 'images/themes/modern-light.jpg',
                'blade_view' => 'themes.modern-minimalist',
                'is_active' => true,
            ],
            [
                'name' => 'Vintage Noir',
                'slug' => 'vintage-noir',
                'type' => 'premium',
                'price' => 20000,
                'description' => 'Tema 1920s Speakeasy Gentleman Club eksklusif dengan aksen emas & ornamen klasik.',
                'thumbnail' => 'images/themes/dark-barber.jpg',
                'blade_view' => 'themes.vintage-noir',
                'is_active' => true,
            ],
            [
                'name' => 'Urban Streetwear',
                'slug' => 'urban-streetwear',
                'type' => 'premium',
                'price' => 20000,
                'description' => 'Gaya Brutalist Neon Studio dengan warna Electric Lime & aksen Cyber Cyan.',
                'thumbnail' => 'images/themes/urban-street.jpg',
                'blade_view' => 'themes.urban-streetwear',
                'is_active' => true,
            ],
            [
                'name' => 'Cyberpunk Neon',
                'slug' => 'cyberpunk-neon',
                'type' => 'premium',
                'price' => 25000,
                'description' => 'Tema futuristik bercahaya Magenta Neon & fitur Matrix Haircut 2088.',
                'thumbnail' => 'images/themes/cyberpunk-neon.jpg',
                'blade_view' => 'themes.cyberpunk-neon',
                'is_active' => true,
            ],
            [
                'name' => 'Royal Emerald',
                'slug' => 'royal-emerald',
                'type' => 'premium',
                'price' => 25000,
                'description' => 'Tema kelas bangsawan (Forest Green & Champagne Gold) dengan VIP Regency Suite.',
                'thumbnail' => 'images/themes/royal-emerald.jpg',
                'blade_view' => 'themes.royal-emerald',
                'is_active' => true,
            ],
            [
                'name' => 'Tokyo Minimal',
                'slug' => 'tokyo-minimal',
                'type' => 'premium',
                'price' => 20000,
                'description' => 'Estetika Jepang Wabi-Sabi yang tenang, presisi, dengan motif Sakura & Hinomaru Seal.',
                'thumbnail' => 'images/themes/tokyo-minimal.jpg',
                'blade_view' => 'themes.tokyo-minimal',
                'is_active' => true,
            ],
            [
                'name' => 'Retro Synthwave 80s',
                'slug' => 'retro-synthwave',
                'type' => 'premium',
                'price' => 20000,
                'description' => 'Nuansa 1980s Retro Arcade dengan gradien Sunset Cyan-Fuchsia & vibes synthwave.',
                'thumbnail' => 'images/themes/retro-synthwave.jpg',
                'blade_view' => 'themes.retro-synthwave',
                'is_active' => true,
            ],
            [
                'name' => 'Executive Titanium',
                'slug' => 'executive-titanium',
                'type' => 'premium',
                'price' => 30000,
                'description' => 'Tema VIP Executive Lounge bercita rasa Brushed Titanium & Silver Metallic.',
                'thumbnail' => 'images/themes/executive-titanium.jpg',
                'blade_view' => 'themes.executive-titanium',
                'is_active' => true,
            ],
            [
                'name' => 'Batik Heritage',
                'slug' => 'batik-heritage',
                'type' => 'premium',
                'price' => 25000,
                'description' => 'Tema kebudayaan Indonesia eksklusif dengan ornamen Batik Parang & warna Terracotta Keraton.',
                'thumbnail' => 'images/themes/batik-heritage.jpg',
                'blade_view' => 'themes.batik-heritage',
                'is_active' => true,
            ],
        ];

        foreach ($themes as $theme) {
            Theme::updateOrCreate(['slug' => $theme['slug']], $theme);
        }

        // Clean up legacy test slugs if any exist
        Theme::whereIn('slug', ['classic-light', 'modern-light', 'dark-barber', 'urban-street', 'dark'])->delete();
    }
}
