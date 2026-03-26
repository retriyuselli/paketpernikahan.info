<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroCircle extends Model
{
    protected $fillable = [
        'image_url',
        'alt',
        'size_px',
        'color_from',
        'color_to',
        'animation_delay',
        'animation_duration',
        'position_side',
        'position_x',
        'position_bottom',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'          => 'boolean',
        'animation_delay'    => 'float',
        'animation_duration' => 'float',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getAssetUrlAttribute(): string
    {
        if (!$this->image_url) {
            return '';
        }

        if (str_starts_with($this->image_url, 'http')) {
            return $this->image_url;
        }

        return Storage::disk('public')->url($this->image_url);
    }
}
