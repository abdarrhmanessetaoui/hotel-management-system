<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            [
                'name' => 'Marrakech',
                'image' => 'https://images.unsplash.com/photo-1597212618440-806262de4f6b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'description' => 'Surnommée la « Ville Rouge », Marrakech offre un mélange dynamique de souks traditionnels, de palais historiques et de jardins magnifiques.',
            ],
            [
                'name' => 'Casablanca',
                'image' => 'https://images.unsplash.com/photo-1539020140153-e479b8c22e70?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'description' => 'Casablanca est le centre économique animé du Maroc, mêlant une architecture moderne saisissante à des monuments historiques riches comme la mosquée Hassan II.',
            ],
            [
                'name' => 'Tanger',
                'image' => 'https://images.pexels.com/photos/3225531/pexels-photo-3225531.jpeg?auto=compress&cs=tinysrgb&w=800',
                'description' => 'Surplombant le détroit de Gibraltar, Tanger possède une histoire multiculturelle unique et sert de porte d\'entrée entre l\'Afrique et l\'Europe.',
            ],
            [
                'name' => 'Agadir',
                'image' => 'https://images.pexels.com/photos/1320686/pexels-photo-1320686.jpeg?auto=compress&cs=tinysrgb&w=800',
                'description' => 'Réputée pour sa magnifique plage en croissant et sa promenade ensoleillée, Agadir est la destination idéale pour la détente et les stations balnéaires.',
            ],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->updateOrInsert(
                ['name' => $city['name']],
                [
                    'image' => $city['image'],
                    'description' => $city['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
