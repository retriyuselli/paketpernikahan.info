<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'type', 'category', 'location', 'province', 'city', 'description',
        'phone', 'email', 'instagram', 'capacity', 'price_start', 'price_start_raw',
        'experience', 'venue_type', 'facilities', 'events_done',
        'likes', 'comments_count', 'rating', 'badge', 'promo',
        'cover_image', 'cover_video', 'is_active',
    ];

    protected $casts = [
        'badge'           => 'array',
        'promo'           => 'array',
        'is_active'       => 'boolean',
        'rating'          => 'float',
        'price_start_raw' => 'integer',
        'events_done'     => 'integer',
        'likes'           => 'integer',
        'comments_count'  => 'integer',
    ];

    /** Use slug as route key: /vendor/{vendor:slug} */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function galleries()
    {
        return $this->hasMany(VendorGallery::class)->orderBy('sort_order');
    }

    public function videoGalleries()
    {
        return $this->hasMany(VendorGallery::class)->whereNotNull('video_url')->orderBy('sort_order');
    }

    public function packages()
    {
        return $this->hasMany(VendorPackage::class)->orderBy('sort_order');
    }

    public function reviews()
    {
        return $this->hasMany(VendorReview::class)->latest('reviewed_at');
    }

    public function approvedReviews()
    {
        return $this->hasMany(VendorReview::class)
                    ->where('is_approved', true)
                    ->latest('reviewed_at');
    }

    public function categoryVendor()
    {
        return $this->belongsTo(CategoryVendor::class, 'category', 'slug');
    }

    /** Mengembalikan URL gambar cover yang siap dipakai di Blade */
    public function getCoverImageUrlAttribute(): ?string
    {
        if (!$this->cover_image) return null;
        if (str_starts_with($this->cover_image, 'http')) return $this->cover_image;
        return Storage::url($this->cover_image);
    }
}
