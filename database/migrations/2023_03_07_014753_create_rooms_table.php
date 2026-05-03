<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old rooms table to rebuild with new structure
        Schema::dropIfExists('rooms');

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            // Each room belongs to exactly one hotel
            $table->foreignId('hotel_id')
                  ->constrained('hotels')
                  ->onDelete('cascade');

            $table->string('room_number');           // e.g. "101", "202A"
            $table->string('type');                  // single | double | suite | deluxe
            $table->decimal('price', 10, 2);         // Price per night
            $table->string('image')->nullable();
            $table->text('description')->nullable();

            // available = bookable, unavailable = under maintenance / blocked
            $table->enum('status', ['available', 'unavailable'])->default('available');

            $table->timestamps();

            // Unique constraint: no two rooms in the same hotel share a room number
            $table->unique(['hotel_id', 'room_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
