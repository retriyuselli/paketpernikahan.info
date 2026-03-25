<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id', 
        'name', 'price', 
        'price_raw', 
        'discount', 
        'max_guests',
        'card_color', 
        'card_text_color', 
        'items', 
        'sort_order', 
        'is_active',
    ];

    protected $casts = [
        'items'      => 'array',
        'is_active'  => 'boolean',
        'price_raw'  => 'integer',
        'discount'   => 'integer',
        'sort_order' => 'integer',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
