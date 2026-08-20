<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'reservation_code',
        'customer_name',
        'customer_phone',
        'customer_user_id',
        'service_id',
        'barber_user_id',
        'reservation_date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(User::class, 'barber_user_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_user_id');
    }
}
