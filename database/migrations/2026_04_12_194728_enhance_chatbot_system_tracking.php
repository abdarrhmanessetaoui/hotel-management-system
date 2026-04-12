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
        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->json('context')->nullable()->after('message_count'); // Memory state
            $table->string('channel')->default('web')->after('context');  // web, whatsapp, telegram
            $table->timestamp('last_active_at')->nullable()->after('channel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->dropColumn(['context', 'channel', 'last_active_at']);
        });
    }
};
