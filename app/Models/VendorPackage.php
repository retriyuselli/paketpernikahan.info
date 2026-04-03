<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id', 
        'category_vendor_id',
        'name', 
        'price', 
        'price_raw', 
        'discount', 
        'dp_paket',
        'max_guests',
        'card_color', 
        'card_text_color', 
        'image_path',
        'items', 
        'type',
        'capacity',
        'facilities',
        'sort_order', 
        'is_active',
    ];

    protected $casts = [
        'image_path' => 'array',
        'items'      => 'array',
        'facilities' => 'array',
        'is_active'  => 'boolean',
        'price_raw'  => 'integer',
        'discount'   => 'integer',
        'dp_paket'   => 'integer',
        'category_vendor_id' => 'integer',
        'sort_order' => 'integer',
        'capacity'   => 'integer',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function categoryVendor()
    {
        return $this->belongsTo(CategoryVendor::class);
    }

    public function galleries()
    {
        return $this->hasMany(PaketGallery::class, 'vendor_package_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        $paths = $this->image_path;
        $path = is_array($paths) ? ($paths[0] ?? null) : $paths;
        if (!$path) return null;
        if (is_string($path) && str_starts_with($path, 'http')) return $path;
        return \Illuminate\Support\Facades\Storage::url($path);
    }
}
