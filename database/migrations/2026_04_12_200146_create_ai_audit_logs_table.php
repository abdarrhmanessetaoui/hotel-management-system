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
        Schema::create('ai_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_session_id')->constrained()->onDelete('cascade');
            $table->string('agent_name');   // BookingAgent, SalesAgent, etc.
            $table->string('action_type');  // create_booking, cancel_booking, refund_requested
            $table->json('payload');        // Data sent/received
            $table->text('reasoning');      // LLM reasoning for the action
            $table->string('status');       // validated, blocked, pending_approval, executed
            $table->string('blocked_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_audit_logs');
    }
};

