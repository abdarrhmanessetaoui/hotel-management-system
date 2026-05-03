<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = User::where('role', 'client')->get();

        if ($clients->isEmpty()) {
            return;
        }

        $reviews = [
            [
                'user_id' => $clients[0]->id ?? null,
                'author_name' => ($clients[0]->name ?? 'Karim') . ' ' . ($clients[0]->last_name ?? 'Alaoui'),
                'content' => 'Séjour incroyable dans cet hôtel ! Le service est impeccable, la chambre était spacieuse et très propre. Le petit-déjeuner sous forme de buffet offre un large choix. Je recommande vivement pour une escapade en famille ou en couple.',
                'rating' => 5,
                'status' => 'accepted',
            ],
            [
                'user_id' => $clients[1]->id ?? null,
                'author_name' => ($clients[1]->name ?? 'Sarah') . ' ' . ($clients[1]->last_name ?? 'Benjelloun'),
                'content' => "Expérience correcte mais quelques bémols. L'attente à la réception était un peu longue à notre arrivée et la décoration de notre chambre semblait un peu vieillotte. Cependant, le personnel est très chaleureux et l'emplacement est idéal.",
                'rating' => 3,
                'status' => 'accepted',
            ],
            [
                'user_id' => $clients[2]->id ?? null,
                'author_name' => ($clients[2]->name ?? 'Youssef') . ' ' . ($clients[2]->last_name ?? 'Fahimi'),
                'content' => "Absolument parfait. De la réservation jusqu'au départ, on se sent chouchoutés. Le spa est un vrai plus et le restaurant de l'hôtel vaut vraiment le détour. C'est l'un des meilleurs établissements que j'ai pu visiter dans la région.",
                'rating' => 5,
                'status' => 'accepted',
            ],
            [
                'user_id' => $clients[3]->id ?? null,
                'author_name' => ($clients[3]->name ?? 'Leila') . ' ' . ($clients[3]->last_name ?? 'Chraibi'),
                'content' => "Vraiment déçu. Le bruit dans les couloirs était insupportable tard dans la nuit et impossible de trouver une place sur le parking. Je ne pense pas revenir. Dommage car la piscine était belle.",
                'rating' => 2,
                'status' => 'pending',
            ],
            [
                'user_id' => $clients[4]->id ?? null,
                'author_name' => ($clients[4]->name ?? 'Omar') . ' ' . ($clients[4]->last_name ?? 'Tazi'),
                'content' => 'Mauvaise foi du manager, pire hôtel fhijshfui hsui fhsdf spam gratuit cryptomonnaie http spam.',
                'rating' => 1,
                'status' => 'rejected',
            ],
            [
                'user_id' => $clients[5]->id ?? null,
                'author_name' => ($clients[5]->name ?? 'Nada') . ' ' . ($clients[5]->last_name ?? 'Elmrini'),
                'content' => "Très bonne expérience globale ! J'ai adoré la piscine chauffée et le calme de ma chambre. Petit point d'amélioration : le signal Wi-Fi qui sautait par moments le soir.",
                'rating' => 4,
                'status' => 'pending',
            ],
            [
                'user_id' => $clients[6]->id ?? null,
                'author_name' => ($clients[6]->name ?? 'Mehdi') . ' ' . ($clients[6]->last_name ?? 'Bennani'),
                'content' => "Fausse publicité !! l'hôtel ne correspond en rien aux photos affichées. La chambre était sale. À fuir.",
                'rating' => 1,
                'status' => 'rejected',
            ],
        ];

        // Clean table first to avoid duplicates safely
        DB::table('reviews')->truncate();

        foreach ($reviews as $review) {
            if (!empty($review['user_id'])) {
                DB::table('reviews')->insert(array_merge($review, [
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}

