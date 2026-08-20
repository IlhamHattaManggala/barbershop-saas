<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingSlotService
{
    /**
     * Generate available time slots for a given date, service, and optional barber.
     */
    public function generateAvailableSlots(
        int $tenantId,
        string $date,
        ?int $serviceId = null,
        ?int $barberUserId = null,
        string $openTime = '09:00',
        string $closeTime = '21:00',
        int $slotIntervalMinutes = 30
    ): array {
        // 1. Get service duration (default 30 minutes)
        $durationMinutes = 30;
        if ($serviceId) {
            $service = Service::where('tenant_id', $tenantId)->find($serviceId);
            if ($service) {
                $durationMinutes = max(15, (int) $service->duration_minutes);
            }
        }

        // 2. Fetch active reservations on that date
        $reservations = Reservation::where('tenant_id', $tenantId)
            ->whereDate('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'in_service'])
            ->get(['id', 'barber_user_id', 'start_time', 'end_time']);

        // 3. Fetch active barbers for the tenant
        $allBarberIds = User::where('tenant_id', $tenantId)
            ->where('role', 'barber')
            ->pluck('id')
            ->toArray();

        $totalBarbersCount = count($allBarberIds);

        // 4. Generate candidate slot intervals
        $start = Carbon::parse("{$date} {$openTime}");
        $end = Carbon::parse("{$date} {$closeTime}");
        $now = Carbon::now();
        $isToday = Carbon::parse($date)->isToday();

        $slots = [];

        while ($start->copy()->addMinutes($durationMinutes)->lte($end)) {
            $slotStart = $start->format('H:i:s');
            $slotEnd = $start->copy()->addMinutes($durationMinutes)->format('H:i:s');
            $timeLabel = $start->format('H:i');

            $status = 'available';
            $reason = 'Tersedia';

            // Check if slot time has passed for today
            if ($isToday && $start->lte($now)) {
                $status = 'passed';
                $reason = 'Sudah lewat';
            } else {
                // Check reservation conflicts
                if ($barberUserId) {
                    // Specific Barber selected
                    $hasConflict = $this->isTimeRangeBusyForBarber($reservations, $barberUserId, $slotStart, $slotEnd);
                    if ($hasConflict) {
                        $status = 'busy';
                        $reason = 'Barber Sibuk';
                    }
                } else {
                    // "Any Barber" selected: check if ALL barbers are busy
                    if ($totalBarbersCount > 0) {
                        $busyBarbersCount = 0;
                        foreach ($allBarberIds as $bId) {
                            if ($this->isTimeRangeBusyForBarber($reservations, $bId, $slotStart, $slotEnd)) {
                                $busyBarbersCount++;
                            }
                        }

                        if ($busyBarbersCount >= $totalBarbersCount) {
                            $status = 'busy';
                            $reason = 'Semua Barber Full';
                        }
                    } else {
                        // No barbers exist, check global overlap
                        $globalBusy = $this->isTimeRangeBusyGlobal($reservations, $slotStart, $slotEnd);
                        if ($globalBusy) {
                            $status = 'busy';
                            $reason = 'Slot Full';
                        }
                    }
                }
            }

            $slots[] = [
                'time' => $start->format('H:i'),
                'time_full' => $slotStart,
                'end_time' => $slotEnd,
                'label' => $timeLabel.' WIB',
                'status' => $status,
                'available' => $status === 'available',
                'reason' => $reason,
            ];

            $start->addMinutes($slotIntervalMinutes);
        }

        return $slots;
    }

    /**
     * Check if a specific barber is busy during [start_time, end_time].
     */
    private function isTimeRangeBusyForBarber($reservations, int $barberId, string $start, string $end): bool
    {
        return $reservations->where('barber_user_id', $barberId)->contains(function ($res) use ($start, $end) {
            return $this->isOverlap($res->start_time, $res->end_time, $start, $end);
        });
    }

    /**
     * Check if any reservation overlaps during [start_time, end_time] globally.
     */
    private function isTimeRangeBusyGlobal($reservations, string $start, string $end): bool
    {
        return $reservations->contains(function ($res) use ($start, $end) {
            return $this->isOverlap($res->start_time, $res->end_time, $start, $end);
        });
    }

    /**
     * Helper to detect time range overlap: [s1, e1] vs [s2, e2]
     */
    private function isOverlap(string $s1, string $e1, string $s2, string $e2): bool
    {
        return ($s1 < $e2) && ($e1 > $s2);
    }

    /**
     * Check conflict and perform atomic check inside DB transaction.
     */
    public function hasConflict(
        int $tenantId,
        string $date,
        string $startTime,
        string $endTime,
        ?int $barberUserId = null
    ): bool {
        return DB::table('reservations')
            ->where('tenant_id', $tenantId)
            ->whereDate('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'in_service'])
            ->when($barberUserId, function ($query) use ($barberUserId) {
                return $query->where('barber_user_id', $barberUserId);
            })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->exists();
    }
}
