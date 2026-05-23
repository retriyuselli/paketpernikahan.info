<?php

namespace App\Models;

use App\Models\VendorBooking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promo extends Model
{
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_amount',
        'max_discount',
        'max_uses',
        'uses_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'value'        => 'integer',
        'min_amount'   => 'integer',
        'max_discount' => 'integer',
        'max_uses'     => 'integer',
        'uses_count'   => 'integer',
        'is_active'    => 'boolean',
        'valid_from'   => 'datetime',
        'valid_until'  => 'datetime',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(VendorBooking::class, 'promo_code', 'code');
    }

    public function vendorPackages(): BelongsToMany
    {
        return $this->belongsToMany(VendorPackage::class, 'promo_vendor_package');
    }

    /** Scope: hanya promo yang aktif dan belum expired */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('valid_from')->orWhere('valid_from', '<=', now()))
            ->where(fn ($q) => $q->whereNull('valid_until')->orWhere('valid_until', '>=', now()));
    }

    /** Scope: belum melewati batas pemakaian */
    public function scopeAvailable($query)
    {
        return $query->where(fn ($q) => $q->whereNull('max_uses')->orWhereColumn('uses_count', '<', 'max_uses'));
    }

    public function isExpired(): bool
    {
        return $this->valid_until !== null && $this->valid_until->isPast();
    }

    public function isMaxUsesReached(): bool
    {
        return $this->max_uses !== null && $this->uses_count >= $this->max_uses;
    }
}
