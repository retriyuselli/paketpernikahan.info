<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class VendorApplication extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    protected $fillable = [
        'user_id',
        'business_name',
        'category',
        'categories',
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
        'categories' => 'array',
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
