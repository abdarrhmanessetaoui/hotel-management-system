<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old orders table — fully replaced by reservations
        Schema::dropIfExists('orders');

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // Direct hotel_id allows hotel admin to query reservations
            // without joining through rooms
            $table->foreignId('hotel_id')
                  ->constrained('hotels')
                  ->onDelete('cascade');

            $table->foreignId('room_id')
                  ->constrained('rooms')
                  ->onDelete('cascade');

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->date('check_in');
            $table->date('check_out');

            $table->unsignedTinyInteger('guests')->default(1); // Number of guests

            // Reservation lifecycle: pending → confirmed → completed | cancelled
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])
                  ->default('pending');

            $table->text('notes')->nullable(); // Optional guest requests

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('orders'); // Safety net
    }
};
