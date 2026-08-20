<?php

namespace App\Livewire\Public;

use App\Models\Product;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Tenant;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class ThemePreviewPage extends Component
{
    public Theme $theme;

    public Tenant $tenant;

    public $customer_name = '';

    public $customer_phone = '';

    public $service_id = '';

    public $barber_user_id = '';

    public $reservation_date = '';

    public $start_time = '10:00';

    public $notes = '';

    public $booking_success = false;

    public $created_reservation_code = '';

    public function mount($themeSlug)
    {
        $previewEnabled = (SiteSetting::get('theme_preview_enabled', '1') === '1');
        $isDevOrSuperadmin = app()->environment('local') || (auth()->check() && auth()->user()->isSuperAdmin());

        if (! $previewEnabled && ! $isDevOrSuperadmin) {
            abort(404, 'Fitur Pratinjau Tema Publik (Preview Mode) saat ini dinonaktifkan oleh Superadmin.');
        }

        $this->theme = Theme::where('slug', $themeSlug)->firstOrFail();
        $this->reservation_date = date('Y-m-d');

        // Create a realistic Demo Tenant in-memory for previewing without DB binding
        $demoTenant = new Tenant([
            'id' => 999,
            'name' => 'Demo Barbershop',
            'slug' => 'demo-preview',
            'primary_color' => 'amber',
            'theme' => $this->theme->slug,
            'hero_title' => 'Pengalaman Cukur Pria Presisi & Mewah',
            'hero_subtitle' => "Pratinjau tampilan live untuk tema '{$this->theme->name}'. Rasakan kenyamanan potongan rambut terbaik.",
            'hero_tagline' => 'PRATINJAU LIVE • TEMA '.strtoupper($this->theme->name),
            'address' => 'Jl. Boulevard Utama No. 88, Jakarta Selatan',
            'phone' => '081234567890',
            'show_wa_button' => true,
            'layout_pos' => 'left',
            'show_services' => true,
            'show_products' => true,
            'section_order' => ['services', 'booking', 'products'],
            'footer_text' => 'Jl. Boulevard Utama No. 88, Jakarta Selatan',
            'footer_copyright' => '© '.date('Y').' Demo Barbershop. All rights reserved.',
        ]);

        $this->tenant = $demoTenant;
    }

    public function createBooking()
    {
        $this->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:30',
            'service_id' => 'required',
            'reservation_date' => 'required|date',
            'start_time' => 'required',
        ]);

        $this->booking_success = true;
        $this->created_reservation_code = 'DEMO-'.strtoupper(Str::random(6));

        $this->reset(['customer_name', 'customer_phone', 'service_id', 'barber_user_id', 'notes']);
    }

    public function render()
    {
        // Demo Services
        $services = collect([
            new Service(['id' => 101, 'name' => 'Gentlemen Haircut & Styling', 'duration_minutes' => 45, 'price' => 50000, 'description' => 'Potong rambut presisi, cuci, pijat kepala ringan, dan pomade styling.']),
            new Service(['id' => 102, 'name' => 'Beard Trim & Hot Towel', 'duration_minutes' => 30, 'price' => 35000, 'description' => 'Rapikan kumis & jenggot dengan pisau cukur steril dan handuk hangat.']),
            new Service(['id' => 103, 'name' => 'Executive Hair Coloring', 'duration_minutes' => 60, 'price' => 120000, 'description' => 'Pewarnaan rambut eksklusif anti-merusak kulit kepala.']),
        ]);

        // Demo Barbers
        $barbers = collect([
            new User(['id' => 201, 'name' => 'Barber Rian (Master Stylist)']),
            new User(['id' => 202, 'name' => 'Barber Alex (Fade Specialist)']),
        ]);

        // Demo Products
        $products = collect([
            new Product(['id' => 301, 'name' => 'Gentlemen Clay Pomade (Matte Hold)', 'category' => 'Hairstyling', 'price' => 85000]),
            new Product(['id' => 302, 'name' => 'Organic Beard & Mustache Oil', 'category' => 'Grooming', 'price' => 65000]),
        ]);

        $viewPath = $this->theme->blade_view ?: 'themes.'.$this->theme->slug;

        return view('livewire.public.theme-preview-page', [
            'viewPath' => $viewPath,
            'services' => $services,
            'barbers' => $barbers,
            'products' => $products,
        ])->layout('layouts.public-shop', ['title' => "Preview Tema {$this->theme->name}"]);
    }
}
