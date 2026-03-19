<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = DB::table('hotels')->orderBy('id')->get();

        // High quality static room images representing different types
        $roomImages = [
            'single' => 'https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=800',
            'double' => 'https://images.pexels.com/photos/271618/pexels-photo-271618.jpeg?auto=compress&cs=tinysrgb&w=800',
            'suite'  => 'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=800',
            'deluxe' => 'https://images.pexels.com/photos/262048/pexels-photo-262048.jpeg?auto=compress&cs=tinysrgb&w=800',
        ];

        foreach ($hotels as $hotel) {
            $roomsPerHotel = 4;

            for ($i = 1; $i <= $roomsPerHotel; $i++) {
                $roomNumber = (string)($i . $i . $hotel->id % 100); 

                $types = ['single', 'double', 'suite', 'deluxe'];
                $type = $types[($i - 1) % count($types)];

                $price = round(mt_rand(900, 2600) / 100, 2);

                DB::table('rooms')->updateOrInsert(
                    [
                        'hotel_id' => $hotel->id,
                        'room_number' => $roomNumber,
                    ],
                    [
                        'type'        => $type,
                        'price'       => $price,
                        'image'       => $roomImages[$type],
                        'description' => "Notre magnifique chambre {$type} située à {$hotel->name}, offrant un confort exceptionnel.",
                        'status'      => 'available',
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]
                );
            }
        }
    }
}