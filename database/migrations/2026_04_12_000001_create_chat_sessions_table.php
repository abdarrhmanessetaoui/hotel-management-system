<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the chat_sessions table.
 * Each session belongs to a user (nullable for guests) and tracks
 * metadata like role, language, and satisfaction rating.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();

            // Nullable so guests can also use the chatbot
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            // The role at session start (client / admin / superadmin / guest)
            $table->string('user_role')->default('guest');

            // Language detected / selected (en / fr / ar)
            $table->string('language', 5)->default('fr');

            // Optional: user rating of the session (1-5)
            $table->unsignedTinyInteger('satisfaction')->nullable();

            // Did the session result in a booking action?
            $table->boolean('converted')->default(false);

            // Total messages in this session
            $table->unsignedInteger('message_count')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_sessions');
    }
};

