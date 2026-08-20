<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'price',
        'description',
        'thumbnail',
        'blade_view',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function isFree(): bool
    {
        return $this->type === 'free';
    }

    public function isPremium(): bool
    {
        return $this->type === 'premium';
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->type === 'free' || $this->price <= 0) {
            return 'Gratis';
        }

        return 'Rp '.number_format($this->price, 0, ',', '.');
    }
}
