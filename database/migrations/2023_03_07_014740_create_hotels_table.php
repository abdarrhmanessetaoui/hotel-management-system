<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();

            // Which city this hotel belongs to
            $table->foreignId('city_id')
                  ->constrained('cities')
                  ->onDelete('cascade');

            // The hotel admin user — nullable so hotel can exist before admin is assigned
            $table->foreignId('admin_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();  // Address or landmark text
            $table->string('image')->nullable();      // Main hotel cover image

            // Rating stored as decimal: 0.0 – 5.0
            $table->decimal('rating', 2, 1)->default(0.0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
