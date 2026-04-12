<?php

namespace Database\Seeders;

use App\Models\ChatbotSuggestion;
use Illuminate\Database\Seeder;

class ChatbotSuggestionSeeder extends Seeder
{
    public function run(): void
    {
        $suggestions = [
            ['text' => 'Voir les chambres disponibles', 'role' => 'all'],
            ['text' => 'Quels sont les hôtels disponibles ?', 'role' => 'all'],
            ['text' => 'Statut de ma réservation', 'role' => 'client'],
            ['text' => "Politique d'annulation", 'role' => 'all'],
            ['text' => 'Contacter le support', 'role' => 'all'],
            ['text' => 'Rapport de performance globale', 'role' => 'superadmin'],
            ['text' => 'Liste des derniers bookings', 'role' => 'admin'],
        ];

        foreach ($suggestions as $suggestion) {
            ChatbotSuggestion::updateOrCreate(['text' => $suggestion['text']], $suggestion);
        }
    }
}
