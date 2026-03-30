<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'category',
        'type',
        'city',
        'province',
        'location',
        'phone',
        'email',
        'instagram',
        'logo_vendor',
        'note',
        'status',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
        'vendor_id',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
