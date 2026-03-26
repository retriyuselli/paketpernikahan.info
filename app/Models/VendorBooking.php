<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'user_id',
        'vendor_package_id',
        'event_date',
        'phone',
        'notes',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendorPackage()
    {
        return $this->belongsTo(VendorPackage::class);
    }
}

