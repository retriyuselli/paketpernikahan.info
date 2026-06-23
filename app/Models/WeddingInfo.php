<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeddingInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'groom_name',
        'bride_name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(WeddingEvent::class, 'user_id', 'user_id')
                    ->orderBy('tgl_acara');
    }
}
