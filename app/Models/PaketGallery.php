<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_package_id',
        'video_url',
        'image_video',
        'caption',
    ];

    protected $casts = [];

    public function vendorPackage()
    {
        return $this->belongsTo(VendorPackage::class);
    }
}
