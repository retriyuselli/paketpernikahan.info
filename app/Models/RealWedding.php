<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RealWedding extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'couple_names',
        'wedding_date',
        'published_at',
        'content',
        'cover_image',
        'venue_name',
        'views_count',
        'badge',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'wedding_date'  => 'date',
        'published_at'  => 'datetime',
        'views_count'   => 'integer',
    ];

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class);
    }

    public function vendorPackages()
    {
        return $this->belongsToMany(VendorPackage::class);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }
        if (str_starts_with($this->cover_image, 'http')) {
            return $this->cover_image;
        }
        return Storage::url($this->cover_image);
    }
}
