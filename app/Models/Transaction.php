<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'transaction_number',
        'customer_name',
        'customer_phone',
        'customer_user_id',
        'cashier_user_id',
        'subtotal',
        'tax',
        'discount',
        'total_amount',
        'cash_paid',
        'change_due',
        'payment_method',
        'payment_proof',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'cash_paid' => 'decimal:2',
        'change_due' => 'decimal:2',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_user_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
