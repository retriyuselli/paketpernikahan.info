<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id', 
        'image_path', 
        'video_url',
        'cover_video',
        'caption', 
        'sort_order', 
        'is_cover',
    ];

    protected $casts = [
        'image_path' => 'array',
        'is_cover'   => 'boolean',
        'sort_order' => 'integer',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /** Returns the first usable image URL from image_path (stored as JSON array) */
    public function getImageUrlAttribute(): ?string
    {
        $paths = $this->image_path; // already decoded as array by cast
        $path = is_array($paths) ? ($paths[0] ?? null) : $paths;
        if (!$path) return null;
        if (str_starts_with($path, 'http')) return $path;
        return \Illuminate\Support\Facades\Storage::url($path);
    }
}
