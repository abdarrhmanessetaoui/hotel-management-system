<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotels = DB::table('hotels')->orderBy('id')->get();

        foreach ($hotels as $hotel) {
            
            // Defining specific types per hotel to follow the "dynamic/per-hotel" requirement
            $types = [
                ['name' => 'Standard', 'description' => 'Une option confortable et économique.'],
                ['name' => 'Luxe', 'description' => 'Équipements haut de gamme et vue imprenable.'],
                ['name' => 'Suite Royale', 'description' => 'Le summum du luxe avec plusieurs pièces.'],
                ['name' => 'Chambre Familiale', 'description' => 'Plus d\'espace pour toute la famille.'],
                ['name' => 'Studio Business', 'description' => 'Idéal pour le travail et le repos.'],
            ];

            // Assign a unique subset of types to each hotel so data is diverse
            // We take 3 random types for each hotel
            $hotelTypes = (array) array_rand(array_flip(array_column($types, 'name')), 3);

            foreach ($hotelTypes as $typeName) {
                // Find description
                $desc = '';
                foreach ($types as $t) {
                    if ($t['name'] === $typeName) {
                        $desc = $t['description'];
                        break;
                    }
                }

                DB::table('room_types')->updateOrInsert(
                    [
                        'hotel_id' => $hotel->id,
                        'name'     => $typeName,
                    ],
                    [
                        'description' => $desc,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]
                );
            }
        }
    }
}
