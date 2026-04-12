<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ChatMessage Model
 *
 * Represents a single message turn inside a ChatSession.
 * sender: 'user' | 'bot'
 * intent: auto-classified intent string
 */
class ChatMessage extends Model
{
    protected $fillable = [
        'chat_session_id',
        'sender',
        'message',
        'intent',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * The session this message belongs to.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }
}
