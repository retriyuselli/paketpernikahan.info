<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'slug', 
        'type', 
        'category', 
        'location', 
        'province', 
        'city', 
        'description',
        'phone', 
        'email', 
        'instagram', 
        'capacity', 
        'price_start', 
        'discount',
        'experience', 
        'venue_type', 
        'facilities', 
        'events_done',
        'likes', 
        'comments_count', 
        'rating', 
        'badge', 
        'promo',
        'cover_image', 
        'cover_video', 
        'is_active',
    ];

    protected $casts = [
        'badge'           => 'array',
        'promo'           => 'array',
        'is_active'       => 'boolean',
        'rating'          => 'float',
        'discount'        => 'integer',
        'events_done'     => 'integer',
        'likes'           => 'integer',
        'comments_count'  => 'integer',
        'price_start'     => 'integer',
        'discount'        => 'integer',
        'cover_image'     => 'array',
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

    public function cheapestPackage()
    {
        return $this->hasOne(VendorPackage::class)
                    ->where('is_active', true)
                    ->orderBy('price_raw');
    }

    public function reviews()
    {
        return $this->hasMany(VendorReview::class);
    }

    /**
     * Get the users who have liked this vendor.
     */
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'vendor_user_likes')->withTimestamps();
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

    /** Harga setelah potongan */
    public function getFinalPriceAttribute(): int
    {
        $raw = (int) preg_replace('/[^\d]/', '', $this->price_start ?? '0');
        return max(0, $raw - ($this->discount ?? 0));
    }

    /** Mengembalikan URL gambar cover yang siap dipakai di Blade */
    public function getCoverImageUrlAttribute(): ?string
    {
        $value = $this->cover_image;
        if (!$value) return null;
        $path = is_array($value) ? ($value[0] ?? null) : $value;
        if (!$path) return null;
        if (str_starts_with($path, 'http')) return $path;
        return Storage::url($path);
    }
}
