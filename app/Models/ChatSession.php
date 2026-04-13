<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * ChatSession Model
 *
 * Represents a single conversation session between a user (or guest)
 * and the AI chatbot. Tracks role, language, conversion, and satisfaction.
 */
class ChatSession extends Model
{
    protected $fillable = [
        'user_id',
        'user_role',
        'language',
        'satisfaction',
        'converted',
        'message_count',
        'context',
    ];

    protected $casts = [
        'converted' => 'boolean',
        'context' => 'array',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * The user who owns this session (nullable for guests).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * All messages in this session.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Increment the message counter and save.
     */
    public function incrementMessages(): void
    {
        $this->increment('message_count');
    }
}
