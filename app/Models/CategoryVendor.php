<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryVendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'name', 'icon', 'color', 'description', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'category', 'slug');
    }
}
