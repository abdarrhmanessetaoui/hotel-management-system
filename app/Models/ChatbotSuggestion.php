<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotSuggestion extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'role', 'is_active'];
}


