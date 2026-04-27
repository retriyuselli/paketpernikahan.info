<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomeAd extends Model
{
    protected $fillable = [
        'title',
        'image',
        'caption',
        'link_url',
        'link_label',
        'delay_seconds',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'delay_seconds'  => 'integer',
        'sort_order'     => 'integer',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return Storage::url($this->image);
    }
}
