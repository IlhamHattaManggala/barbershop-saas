<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendUpcomingReservationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-upcoming-reservation-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automatic WhatsApp reminders to customers for upcoming reservations in the next 2 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = date('Y-m-d');
        $nowTime = date('H:i:s');
        $twoHoursLater = date('H:i:s', strtotime('+2 hours'));

        $upcomingReservations = Reservation::whereDate('reservation_date', $today)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereBetween('start_time', [$nowTime, $twoHoursLater])
            ->get();

        $count = 0;
        $waService = new WhatsAppService;

        foreach ($upcomingReservations as $reservation) {
            try {
                $waService->sendBookingConfirmation($reservation);
                $count++;
            } catch (\Throwable $e) {
                Log::warning("WhatsApp reminder failed for reservation {$reservation->reservation_code}: {$e->getMessage()}");
            }
        }

        $this->info("Successfully sent {$count} upcoming reservation WhatsApp reminders.");

        return Command::SUCCESS;
    }
}
