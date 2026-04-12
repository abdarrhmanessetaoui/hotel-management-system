<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiAuditLog extends Model
{
    protected $fillable = [
        'chat_session_id',
        'agent_name',
        'action_type',
        'payload',
        'reasoning',
        'status',
        'blocked_reason'
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
