<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelsSeeder extends Seeder
{
    public function run(): void
    {
        $cities = DB::table('cities')->orderBy('name')->get();
        $admins = DB::table('users')->where('role', 'admin')->orderBy('id')->pluck('id')->toArray();
        $adminCount = count($admins);
        $adminIndex = 0;

        $hotelData = [
            'Marrakech' => [
                [
                    'name' => 'La Mamounia',
                    'image' => 'https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'description' => 'Un palais légendaire 5 étoiles offrant un luxe inégalé, des jardins luxuriants et une hospitalité marocaine exceptionnelle.',
                    'location' => 'Avenue Bab Jdid, Marrakech',
                    'rating' => 4.9
                ],
                [
                    'name' => 'Royal Mansour',
                    'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'description' => 'Découvrez des riads privés exquis, une cuisine de classe mondiale et une oasis de calme inoubliable dans la Médina.',
                    'location' => 'Rue Abou Abbas El Sebti, Marrakech',
                    'rating' => 4.8
                ]
            ],
            'Casablanca' => [
                [
                    'name' => 'Four Seasons Hotel Casablanca',
                    'image' => 'https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'description' => 'Un superbe sanctuaire face à l\'océan offrant des vues majestueuses, un spa somptueux et une détente de style complexe hôtelier en ville.',
                    'location' => 'Boulevard de la Corniche, Casablanca',
                    'rating' => 4.7
                ],
                [
                    'name' => 'Hyatt Regency Casablanca',
                    'image' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'description' => 'Situé au cœur du quartier des affaires avec une vue imprenable sur la mosquée Hassan II et l\'ancienne Médina.',
                    'location' => 'Place des Nations Unies, Casablanca',
                    'rating' => 4.5
                ]
            ],
            'Tanger' => [
                [
                    'name' => 'El Minzah Hotel',
                    'image' => 'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'description' => 'Un hôtel historique 5 étoiles présentant une architecture andalouse authentique et des vues imprenables sur la baie de Tanger.',
                    'location' => '85 Rue de la Liberté, Tanger',
                    'rating' => 4.6
                ],
                [
                    'name' => 'Hilton Tanger City Center',
                    'image' => 'https://images.unsplash.com/photo-1596436889106-be35e843f974?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'description' => 'Hébergements modernes et sophistiqués offrant une piscine sur le toit, une cuisine exquise et des vues panoramiques sur la ligne d\'horizon moderne.',
                    'location' => 'Place du Maghreb Arab, Tanger',
                    'rating' => 4.4
                ]
            ],
            'Agadir' => [
                [
                    'name' => 'Sofitel Agadir Thalassa Sea & Spa',
                    'image' => 'https://images.unsplash.com/photo-1496417263034-38ec4f0b665a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'description' => 'Un complexe serein en bord de mer axé sur le bien-être et la thalassothérapie, conçu pour une escapade de détente ultime.',
                    'location' => 'Baie des Palmiers, Agadir',
                    'rating' => 4.6
                ],
                [
                    'name' => 'Iberostar Founty Beach',
                    'image' => 'https://images.pexels.com/photos/189296/pexels-photo-189296.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'description' => 'Un excellent complexe familial tout compris situé directement sur la plage, doté de grandes piscines et de diverses options de divertissement.',
                    'location' => 'BP 1039, Cité Founty, Agadir',
                    'rating' => 4.3
                ]
            ]
        ];

        foreach ($cities as $city) {
            $hotels = $hotelData[$city->name] ?? [];

            foreach ($hotels as $hotel) {
                // Pick an admin in round-robin fashion
                $adminId = null;
                if ($adminCount > 0) {
                    $adminId = $admins[$adminIndex % $adminCount];
                    $adminIndex++;
                }

                DB::table('hotels')->updateOrInsert(
                    [
                        'city_id' => $city->id,
                        'name' => $hotel['name'],
                    ],
                    [
                        'admin_id'    => $adminId,
                        'description' => $hotel['description'],
                        'location'    => $hotel['location'],
                        'image'       => $hotel['image'],
                        'rating'      => $hotel['rating'],
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]
                );
            }
        }
    }
}