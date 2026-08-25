<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements PasskeyUser
{
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
        'current_tenant_id',
        'role',
        'phone',
        'allowed_outlets',
        'purchased_themes',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'allowed_outlets' => 'integer',
            'purchased_themes' => 'array',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function currentTenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'current_tenant_id');
    }

    public function ownedTenants()
    {
        return $this->hasMany(Tenant::class, 'owner_id');
    }

    public function getActiveTenantIdAttribute()
    {
        return $this->current_tenant_id ?: $this->tenant_id;
    }

    public function getActiveTenantAttribute()
    {
        $id = $this->active_tenant_id;
        if (! $id) {
            return $this->tenant;
        }

        return Tenant::find($id) ?: $this->tenant;
    }

    public function hasPurchasedTheme(string $themeSlug): bool
    {
        $userPurchases = is_array($this->purchased_themes) ? $this->purchased_themes : (json_decode($this->purchased_themes ?? '[]', true) ?: []);
        if (in_array($themeSlug, $userPurchases)) {
            return true;
        }

        if ($this->tenant && $this->tenant->hasPurchasedTheme($themeSlug)) {
            return true;
        }

        return false;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    public function isBarber(): bool
    {
        return $this->role === 'barber';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(AppFeedback::class);
    }

    public function hasSubmittedFeedback(): bool
    {
        return $this->feedback()->exists();
    }

    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }
}
