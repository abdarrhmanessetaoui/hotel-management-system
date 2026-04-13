<?php

namespace App\Services\AI\Orchestrator;

use App\Models\ChatSession;
use App\Models\AiAuditLog;
use App\Services\AI\Agents\BookingAgent;
use App\Services\AI\Agents\SupportAgent;
use App\Services\AI\Agents\SalesAgent;
use App\Services\AI\DecisionEngine\PolicyValidator;
use App\Services\AI\MemoryManager;
use App\Services\AI\KnowledgeBaseService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Senior Hybrid AI Manager Orchestrator (Production SaaS V2)
 * Robust handling of AI outages with intelligent deterministic failover.
 */
class ManagerOrchestrator
{
    public function __construct(
        protected MemoryManager $memory,
        protected KnowledgeBaseService $kb,
        protected PolicyValidator $validator,
        protected BookingAgent $booking,
        protected SupportAgent $support,
        protected SalesAgent $sales
    ) {}

    public function process(ChatSession $session, string $userMessage): string
    {
        $cleanMessage = trim($userMessage);
        if (empty($cleanMessage)) {
            return "Bonjour ! Comment puis-je vous aider aujourd'hui ? Je peux vous renseigner sur nos hôtels ou vos réservations.";
        }

        try {
            // First, process memory to capture any new inputs (like cities) before querying KB
            $this->updateSessionMemory($session, $cleanMessage);
            
            $intent = $this->detectIntent($userMessage);
            
            // Context from memory
            $context = $session->context ?? [];
            $jsonData = $this->kb->retrieveRelevantContext($userMessage, $session->user_role, $context);

            return $this->generateHighFidelityResponse($intent, $jsonData, $userMessage, $context);

        } catch (\Exception $e) {
            Log::error("Enterprise AI Failure: " . $e->getMessage());
            return "Une assistance est disponible au +212 599-887766 pour vos questions urgentes.";
        }
    }

    private function updateSessionMemory(ChatSession $session, string $msg): void
    {
        // This mirrors part of MemoryManager, but orchestrator ensures it runs *before* KB retrieval 
        // to immediately catch city contexts. MemoryManager typically saves *after* process().
        $context = $session->context ?? [];
        $lower = mb_strtolower($msg, 'UTF-8');
        
        $cities = \App\Models\City::pluck('name')->toArray();
        foreach ($cities as $city) {
            if (mb_strpos($lower, mb_strtolower($city, 'UTF-8')) !== false) {
                $context['current_city'] = $city;
                break;
            }
        }
        $session->update(['context' => $context]);
    }

    protected function detectIntent(string $msg): string
    {
        $lower = mb_strtolower($msg, 'UTF-8');
        
        // Extended keyword map for robust routing
        $hotelTerms = [
            'chambre', 'room', 'hotel', 'hôtel', 'prix', 'tarifs', 'dispo', 'disponibilité',
            'annul', 'politique', 'réservation', 
            'reservation', 'booking', 'support', 'aide', 'contact', 'client', 'statut', 'ville'
        ];

        // Add dynamic cities
        $cities = \App\Models\City::pluck('name')->toArray();
        foreach ($cities as $city) {
            $hotelTerms[] = mb_strtolower($city, 'UTF-8');
        }

        foreach ($hotelTerms as $term) {
            if (mb_strpos($lower, $term) !== false) return 'HOTEL_MODE';
        }

        return 'AI_MODE';
    }

    protected function generateHighFidelityResponse(string $intent, string $jsonData, string $msg, array $context): string
    {
        $apiKey = config('chatbot.groq.api_key');
        $model  = config('chatbot.groq.model', 'llama-3.3-70b-versatile');
        $endpoint = config('chatbot.groq.endpoint', 'https://api.groq.com/openai/v1/chat/completions');

        if (!empty($apiKey) && $intent === 'AI_MODE') {
            $system = $this->getAiModePrompt();

            try {
                $response = Http::withHeaders([
                        'Authorization' => "Bearer {$apiKey}",
                        'Content-Type' => 'application/json',
                    ])
                    ->timeout(20)
                    ->post($endpoint, [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'system', 'content' => $system], 
                            ['role' => 'user', 'content' => $msg]
                        ],
                        'temperature' => 0.7,
                        'max_tokens' => 800
                    ]);

                if ($response->successful()) {
                    $content = $response->json('choices.0.message.content');
                    if (!empty($content)) {
                        return trim($content);
                    }
                }
                
                Log::warning("GROQ API Error.", ['status' => $response->status(), 'body' => $response->body()]);
            } catch (\Exception $e) {
                Log::error("GROQ API Exception: " . $e->getMessage());
            }
        }

        return $this->renderLocalSaasFallback($jsonData, $msg, $context);
    }

    private function getAiModePrompt(): string
    {
        return "Assistant Concierge (MODE GÉNÉRAL).\nTu dois TOUJOURS répondre en FRANÇAIS. Utilise un français professionnel et formel. Répondez comme un assistant virtuel avancé, de style ChatGPT. N'utilise jamais l'anglais.";
    }

    protected function renderLocalSaasFallback(string $jsonData, string $msg, array $context): string
    {
        $data = json_decode($jsonData, true);
        $low = mb_strtolower($msg, 'UTF-8');

        // 1. Policy & Support
        if (mb_strpos($low, 'annul') !== false || mb_strpos($low, 'politique') !== false) {
            return "📜 **Politique d'annulation** : Annulation gratuite jusqu'à 24h avant l'enregistrement. Après ce délai, la première nuit sera facturée.";
        }
        if (mb_strpos($low, 'support') !== false || mb_strpos($low, 'aide') !== false || mb_strpos($low, 'contact') !== false) {
            return "📞 **Support Client** : Nous sommes joignables au +212 599-887766 ou via support@hotelia.com. Souhaitez-vous être rappelé ?";
        }

        // 2. Reservation Status
        if (mb_strpos($low, 'statut') !== false || mb_strpos($low, 'reservation') !== false || mb_strpos($low, 'réservation') !== false) {
            $bookings = $data['bookings'] ?? [];
            if (empty($bookings)) return "Je ne trouve aucune réservation active pour votre compte actuellement.";
            $b = $bookings[0];
            return "🕒 **Dernière Réservation** : Hotel {$b['hotel']} | Statut: **{$b['status']}**.";
        }
        
        // 3. City Listing Logic
        if (mb_strpos($low, 'ville') !== false && (mb_strpos($low, 'quelle') !== false || mb_strpos($low, 'dispo') !== false || mb_strpos($low, 'liste') !== false)) {
            $cities = \App\Models\City::pluck('name')->toArray();
            if (empty($cities)) return "Aucune donnée disponible actuellement.";
            
            $res = "🌍 **Villes disponibles** :\n";
            foreach ($cities as $city) {
                $res .= "- {$city}\n";
            }
            return $res . "\nDans quelle ville recherchez-vous un hôtel ?";
        }

        // 4. City First Rule for Hotels
        $hasHotelIntent = mb_strpos($low, 'hotel') !== false || mb_strpos($low, 'hôtel') !== false || mb_strpos($low, 'établissement') !== false;
        $hasRoomIntent = mb_strpos($low, 'chambre') !== false || mb_strpos($low, 'room') !== false || mb_strpos($low, 'dispo') !== false;
        
        $currentCity = $context['current_city'] ?? null;
        
        // Treat mentioning a city explicitly as a request for hotels
        if ($currentCity && mb_strpos($low, mb_strtolower($currentCity, 'UTF-8')) !== false && !$hasRoomIntent) {
            $hasHotelIntent = true;
        }
        
        // DEBUG: uncomment for logging
        // Log::info("DEBUG Orchestrator", ['low' => $low, 'currCity' => $currentCity, 'hasHotel' => $hasHotelIntent]);

        if ($hasHotelIntent || $hasRoomIntent) {
            if (empty($currentCity)) {
                return "Dans quelle ville recherchez-vous un hôtel ?";
            }
        }

        // 5. Hotels & Establishments (Strictly by current city context)
        if ($hasHotelIntent) {
            $hotels = $data['hotels'] ?? [];
            if (empty($hotels)) return "Aucune donnée disponible actuellement.";
            $res = "🏨 **Hôtels à {$currentCity}** :\n";
            foreach ($hotels as $h) { 
                $res .= "- **{$h['name']}** | ⭐ {$h['rating']}\n"; 
            }
            return $res . "\nPour quel hôtel souhaitez-vous voir les chambres ?";
        }

        // 6. Room Availability
        if ($hasRoomIntent || mb_strpos($low, 'prix') !== false || mb_strpos($low, 'tarif') !== false) {
            $rooms = $data['rooms'] ?? [];

            if (empty($rooms)) return "Aucune donnée disponible actuellement.";
            $res = "🛏️ **Chambres disponibles** :\n";
            foreach (array_slice($rooms, 0, 4) as $r) {
                $res .= "- {$r['hotel']} (⭐ {$r['rating']}) | Ch. {$r['number']} : **{$r['price']} DH**\n";
            }
            return $res . "\nComment puis-je vous aider pour votre réservation ?";
        }

        // 7. Intelligent Greetings
        if (mb_strpos($low, 'bonjour') !== false || mb_strpos($low, 'hello') !== false || mb_strpos($low, 'salut') !== false) {
            return "Bonjour ! Je suis l'Assistant Hotelia.\nDans quelle ville recherchez-vous un hôtel ?";
        }

        // 8. Fallback AI General Mode if no match found in local routing
        return "Je suis en mode d'assistance. Je peux vous renseigner sur les hôtels, les prix, les annulations ou le support.";
    }
}
