<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Explicit imports so $this->call([...]) resolves class names correctly
use Database\Seeders\UsersSeeder;
use Database\Seeders\CitiesSeeder;
use Database\Seeders\HotelsSeeder;
use Database\Seeders\RoomsSeeder;
use Database\Seeders\ReservationsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call all 5 seeders in proper order
        $this->call([
            UsersSeeder::class,       // users table: superadmin, admin, clients
            CitiesSeeder::class,      // cities table
            HotelsSeeder::class,      // hotels table (linked to cities)
            RoomsSeeder::class,       // rooms table (linked to hotels)
            ReservationsSeeder::class // reservations table (linked to users and rooms)
        ]);
    }
}