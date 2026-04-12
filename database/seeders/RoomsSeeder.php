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

            $types = ['single', 'double', 'suite', 'deluxe'];

            foreach ($types as $i => $type) {

                $roomNumber = ($i + 1) . '0' . $hotel->id;

                // ✅ Realistic DH price — rounded to nearest 50
                [$min, $max] = $priceRanges[$type];
                $price = round(mt_rand($min, $max) / 50) * 50;

                DB::table('rooms')->updateOrInsert(
                    [
                        'hotel_id'    => $hotel->id,
                        'room_number' => $roomNumber,
                    ],
                    [
                        'type'        => $type,
                        'price'       => $price,
                        'image'       => $roomImages[$type],
                        'description' => sprintf($descriptions[$type], $hotel->name),
                        'status'      => 'available',
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]
                );
            }
        }
    }
}