<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotAnalytic extends Model
{
    use HasFactory;

    protected $table = 'chatbot_analytics';

    protected $fillable = [
        'intent',
        'confidence',
        'role',
        'is_fallback',
        'booking_triggered',
        'chat_session_id'
    ];

    protected $casts = [
        'confidence' => 'float',
        'is_fallback' => 'boolean',
        'booking_triggered' => 'boolean'
    ];

    public function chatSession()
    {
        return $this->belongsTo(ChatSession::class);
    }
}
