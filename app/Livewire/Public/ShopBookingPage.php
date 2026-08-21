<?php

namespace App\Livewire\Public;

use App\Models\Product;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\User;
use App\Services\BookingSlotService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class ShopBookingPage extends Component
{
    public Tenant $tenant;

    public $customer_name = '';

    public $customer_phone = '';

    public $service_id = '';

    public $barber_user_id = '';

    public $reservation_date = '';

    public $start_time = '09:00';

    public $notes = '';

    public $booking_success = false;

    public $created_reservation_code = '';

    public array $available_slots = [];

    public function mount($slug)
    {
        $this->tenant = Tenant::where('slug', $slug)->firstOrFail();
        $this->reservation_date = date('Y-m-d');

        // DRAFT PREVIEW MODE: Override tenant attributes in-memory for iframe preview sandbox
        if (request()->has('preview')) {
            if (request()->has('color')) {
                $this->tenant->primary_color = request()->get('color');
            }
            if (request()->has('title')) {
                $this->tenant->hero_title = request()->get('title');
            }
            if (request()->has('subtitle')) {
                $this->tenant->hero_subtitle = request()->get('subtitle');
            }
            if (request()->has('tagline')) {
                $this->tenant->hero_tagline = request()->get('tagline');
            }
            if (request()->has('wa')) {
                $this->tenant->show_wa_button = (bool) request()->get('wa');
            }
            if (request()->has('pos')) {
                $this->tenant->layout_pos = request()->get('pos');
            }
            if (request()->has('srv')) {
                $this->tenant->show_services = (bool) request()->get('srv');
            }
            if (request()->has('prd')) {
                $this->tenant->show_products = (bool) request()->get('prd');
            }
            if (request()->has('banner')) {
                $this->tenant->hero_banner = request()->get('banner');
            }
            if (request()->has('order')) {
                $rawOrder = explode(',', request()->get('order'));
                $this->tenant->section_order = array_values(array_filter($rawOrder));
            }
            if (request()->has('btn')) {
                $this->tenant->button_style = request()->get('btn');
            }
            if (request()->has('font')) {
                $cfg = $this->tenant->getThemeConfigArray();
                $cfg['settings']['font_family'] = request()->get('font');
                $this->tenant->theme_config = $cfg;
            }
            if (request()->has('ftext')) {
                $this->tenant->footer_text = request()->get('ftext');
            }
            if (request()->has('fcopy')) {
                $this->tenant->footer_copyright = request()->get('fcopy');
            }
        }

        // Set default active service if available
        $firstService = Service::where('tenant_id', $this->tenant->id)->where('is_active', true)->first();
        if ($firstService) {
            $this->service_id = $firstService->id;
        }

        $this->loadSlots();
    }

    public function updatedServiceId()
    {
        $this->loadSlots();
    }

    public function updatedBarberUserId()
    {
        $this->loadSlots();
    }

    public function updatedReservationDate()
    {
        $this->loadSlots();
    }

    public function selectSlot(string $time)
    {
        $this->start_time = $time;
    }

    public function loadSlots()
    {
        $slotService = new BookingSlotService;
        $this->available_slots = $slotService->generateAvailableSlots(
            $this->tenant->id,
            $this->reservation_date ?: date('Y-m-d'),
            $this->service_id ? (int) $this->service_id : null,
            $this->barber_user_id ? (int) $this->barber_user_id : null
        );

        // Auto-select first available time slot if current selected start_time is unavailable
        $currentSlot = collect($this->available_slots)->firstWhere('time', $this->start_time);
        if (! $currentSlot || ! ($currentSlot['available'] ?? false)) {
            $firstAvail = collect($this->available_slots)->firstWhere('available', true);
            if ($firstAvail) {
                $this->start_time = $firstAvail['time'];
            }
        }
    }

    public function createBooking()
    {
        $this->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:30',
            'service_id' => 'required|exists:services,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
        ]);

        $service = Service::find($this->service_id);
        $duration = $service ? (int) $service->duration_minutes : 30;

        $startTimestamp = strtotime("{$this->reservation_date} {$this->start_time}");
        $endTimestamp = $startTimestamp + ($duration * 60);
        $startTimeFormatted = date('H:i:s', $startTimestamp);
        $endTimeFormatted = date('H:i:s', $endTimestamp);

        $barberId = $this->barber_user_id ? (int) $this->barber_user_id : null;
        $slotService = new BookingSlotService;

        try {
            DB::transaction(function () use ($slotService, $startTimeFormatted, $endTimeFormatted, $barberId) {
                // ATOMIC LOCK & CONFLICT CHECK
                $hasConflict = $slotService->hasConflict(
                    $this->tenant->id,
                    $this->reservation_date,
                    $startTimeFormatted,
                    $endTimeFormatted,
                    $barberId
                );

                if ($hasConflict) {
                    throw new \Exception('Maaf, slot waktu jam '.$this->start_time.' WIB baru saja dipesan oleh pelanggan lain. Silakan pilih slot waktu atau barber lain.');
                }

                $code = 'RSV-'.strtoupper(Str::random(6));

                Reservation::create([
                    'tenant_id' => $this->tenant->id,
                    'reservation_code' => $code,
                    'customer_name' => $this->customer_name,
                    'customer_phone' => $this->customer_phone,
                    'service_id' => $this->service_id,
                    'barber_user_id' => $barberId,
                    'reservation_date' => $this->reservation_date,
                    'start_time' => $startTimeFormatted,
                    'end_time' => $endTimeFormatted,
                    'status' => 'pending',
                    'notes' => $this->notes,
                ]);

                $this->created_reservation_code = $code;
            });

            $this->booking_success = true;
            $this->loadSlots();

            // Dispatch automatic WhatsApp booking confirmation if enabled for this tenant
            try {
                $createdReservation = Reservation::where('tenant_id', $this->tenant->id)
                    ->where('reservation_code', $this->created_reservation_code)
                    ->first();
                if ($createdReservation) {
                    (new WhatsAppService)->sendBookingConfirmation($createdReservation);
                }
            } catch (\Throwable $waError) {
                Log::warning('WhatsApp Notification Dispatch Error: '.$waError->getMessage());
            }

            $this->reset(['customer_name', 'customer_phone', 'notes']);

        } catch (\Exception $e) {
            $this->addError('start_time', $e->getMessage());
            $this->loadSlots();
        }
    }

    public function render()
    {
        $services = Service::where('tenant_id', $this->tenant->id)->where('is_active', true)->get();
        $barbers = User::where('tenant_id', $this->tenant->id)->where('role', 'barber')->get();
        $products = Product::where('tenant_id', $this->tenant->id)->where('is_active', true)->get();

        return view('livewire.public.shop-booking-page', [
            'services' => $services,
            'barbers' => $barbers,
            'products' => $products,
        ])->layout('layouts.public-shop', ['title' => $this->tenant->name]);
    }
}
