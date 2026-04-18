<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = DB::table('hotels')->orderBy('id')->get();

        $roomImages = [
            'single' => 'https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=800',
            'double' => 'https://images.pexels.com/photos/271618/pexels-photo-271618.jpeg?auto=compress&cs=tinysrgb&w=800',
            'suite'  => 'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=800',
            'deluxe' => 'https://images.pexels.com/photos/262048/pexels-photo-262048.jpeg?auto=compress&cs=tinysrgb&w=800',
        ];

        // Realistic price ranges in Moroccan Dirhams per night
        $priceRanges = [
            'single' => [300,  600],
            'double' => [500,  900],
            'suite'  => [1200, 2500],
            'deluxe' => [800,  1500],
        ];

        // French descriptions per type
        $descriptions = [
            'single' => "Chambre simple confortable et bien équipée, idéale pour un voyageur solo souhaitant profiter d'un séjour agréable à %s.",
            'double' => "Chambre double spacieuse avec un grand lit, parfaite pour les couples ou les voyageurs cherchant plus de confort à %s.",
            'suite'  => "Suite luxueuse avec salon séparé, décoration raffinée et vue imprenable. Une expérience haut de gamme à %s.",
            'deluxe' => "Chambre Deluxe élégante offrant des prestations supérieures, un mobilier de qualité et une atmosphère exclusive à %s.",
        ];

        foreach ($hotels as $hotel) {

            $roomTypes = DB::table('room_types')->where('hotel_id', $hotel->id)->get();

            foreach ($roomTypes as $i => $type) {

                $roomNumber = ($i + 1) . '0' . $hotel->id;

                // Pick a range or default
                $range = [500, 1500];
                if (str_contains(strtolower($type->name), 'royal')) $range = [2000, 5000];
                if (str_contains(strtolower($type->name), 'standard')) $range = [300, 700];

                $price = round(mt_rand($range[0], $range[1]) / 50) * 50;

                DB::table('rooms')->updateOrInsert(
                    [
                        'hotel_id'    => $hotel->id,
                        'room_number' => $roomNumber,
                    ],
                    [
                        'room_type_id' => $type->id,
                        'type'         => $type->name, 
                        'price'        => $price,
                        'image'        => "https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80",
                        'description'  => "Une superbe chambre de type {$type->name} située au {$hotel->name}.",
                        'status'       => 'available',
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]
                );
            }
        }
    }
}