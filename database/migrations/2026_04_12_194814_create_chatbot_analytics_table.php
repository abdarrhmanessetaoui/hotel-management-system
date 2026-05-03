<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chatbot_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('intent');
            $table->float('confidence')->default(0);
            $table->string('role')->nullable();
            $table->boolean('is_fallback')->default(false);
            $table->boolean('booking_triggered')->default(false);
            $table->foreignId('chat_session_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_analytics');
    }
};

