<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatSession extends Model
{
    protected $fillable = ['guest_name', 'session_token', 'status', 'vendor_id', 'vendor_package_id'];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function vendor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Vendor::class);
    }

    public function vendorPackage(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\VendorPackage::class);
    }

    public function unreadGuestMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->where('sender', 'guest');
    }
}
