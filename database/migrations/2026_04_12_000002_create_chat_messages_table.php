<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the chat_messages table.
 * Each row is a single message turn (user or bot) belonging to a session.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('chat_session_id')->constrained()->onDelete('cascade');

            // 'user' or 'bot'
            $table->enum('sender', ['user', 'bot']);

            // The actual message text
            $table->text('message');

            // Auto-detected intent (e.g. booking, faq, recommendation, complaint…)
            $table->string('intent')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};

