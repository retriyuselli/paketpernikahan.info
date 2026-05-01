<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatSession extends Model
{
    protected $fillable = ['guest_name', 'session_token', 'status'];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function unreadAdminMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->where('sender', 'guest');
    }
}
