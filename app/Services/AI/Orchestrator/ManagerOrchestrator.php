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
            return "Bonjour ! Je suis l'assistant Hotelia. Comment puis-je vous aider ? Vous pouvez me demander les hôtels disponibles dans une ville ou consulter nos prix.";
        }

        try {
            // Memory tracking for city context
            $this->updateSessionMemory($session, $cleanMessage);
            
            $intent = $this->detectIntent($cleanMessage);
            $context = $session->context ?? [];
            
            // HYBRID ROUTING
            if ($intent === 'HOTEL_MODE') {
                // Determine relevant data from Knowledge Base
                $jsonData = $this->kb->retrieveRelevantContext($cleanMessage, $session->user_role, $context);
                return $this->renderDeterministicResponse($jsonData, $cleanMessage, $context);
            }

            // GENERAL AI MODE (GROQ)
            return $this->generateGroqAiResponse($cleanMessage, $context);

        } catch (\Exception $e) {
            Log::error("Chatbot Orchestrator Error: " . $e->getMessage());
            return "Désolé, j'ai rencontré une erreur technique. Veuillez réessayer plus tard.";
        }
    }

    private function updateSessionMemory(ChatSession $session, string $msg): void
    {
        $context = $session->context ?? [];
        $lower = mb_strtolower($msg, 'UTF-8');
        
        $cities = \App\Models\City::pluck('name')->toArray();
        foreach ($cities as $city) {
            if (mb_strpos($lower, mb_strtolower($city, 'UTF-8')) !== false) {
                $context['current_city'] = $city;
                break;
            }
        }

        // Detect hotel selection from name
        $hotels = \App\Models\Hotel::pluck('name')->toArray();
        foreach ($hotels as $hotel) {
            if (mb_strpos($lower, mb_strtolower($hotel, 'UTF-8')) !== false) {
                $context['current_hotel'] = $hotel;
                break;
            }
        }

        $session->update(['context' => $context]);
    }

    protected function detectIntent(string $msg): string
    {
        $lower = mb_strtolower($msg, 'UTF-8');
        
        $hotelKeywords = [
            'chambre', 'room', 'hotel', 'hôtel', 'prix', 'tarifs', 'dispo', 'disponibilité',
            'réservation', 'reservation', 'booking', 'ville', 'liste', 'disponible'
        ];

        // Any city name makes it a hotel intent
        $cities = \App\Models\City::pluck('name')->toArray();
        foreach ($cities as $city) {
            if (mb_strpos($lower, mb_strtolower($city, 'UTF-8')) !== false) return 'HOTEL_MODE';
        }

        foreach ($hotelKeywords as $term) {
            if (mb_strpos($lower, $term) !== false) return 'HOTEL_MODE';
        }

        return 'AI_MODE';
    }

    protected function generateGroqAiResponse(string $msg, array $context): string
    {
        $apiKey = config('chatbot.groq.api_key');
        $model  = config('chatbot.groq.model', 'llama3-8b-8192');
        $endpoint = config('chatbot.groq.endpoint');

        if (empty($apiKey)) {
            Log::error("GROQ_API_KEY is missing in .env");
            return "Je suis disponible pour répondre à vos questions générales, mais mon service IA est actuellement déconnecté.";
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])
            ->timeout(20)
            ->post($endpoint, [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => "Tu es un assistant ChatGPT premium spécialisé dans l'hôtellerie. Réponds TOUJOURS en FRANÇAIS. Sois poli, concis et utile. N'invente jamais de données sur les hôtels si tu n'en as pas."],
                    ['role' => 'user', 'content' => $msg]
                ],
                'temperature' => 0.6,
                'max_tokens' => 1000
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                if (!empty($content)) {
                    return trim($content);
                }
            }

            Log::error("Groq API Error Status: " . $response->status(), ['body' => $response->body()]);
            return "Je ne peux pas répondre pour le moment (IA indisponible). Posez-moi une question sur nos hôtels !";

        } catch (\Exception $e) {
            Log::error("Groq Exception: " . $e->getMessage());
            return "Oups, une erreur de communication avec Groq. Comment puis-je vous aider autrement ?";
        }
    }

    protected function renderDeterministicResponse(string $jsonData, string $msg, array $context): string
    {
        $data        = json_decode($jsonData, true);
        $low         = mb_strtolower($msg, 'UTF-8');
        $currentCity = $context['current_city'] ?? null;
        $currentHotel= $context['current_hotel'] ?? null;

        // ── 1. CITY LISTING ──────────────────────────────────────────────────
        $wantsCities = mb_strpos($low, 'ville') !== false
                    || mb_strpos($low, 'destination') !== false
                    || mb_strpos($low, 'où') !== false;

        if ($wantsCities && (mb_strpos($low, 'liste') !== false || mb_strpos($low, 'quel') !== false || mb_strpos($low, 'dispo') !== false || mb_strpos($low, 'toutes') !== false)) {
            $cities = \App\Models\City::withCount('hotels')->get();
            if ($cities->isEmpty()) return "Aucune ville disponible dans notre système actuellement.";

            $res = "Destinations disponibles :\n";
            foreach ($cities as $city) {
                $res .= "- {$city->name} ({$city->hotels_count} hotel" . ($city->hotels_count > 1 ? 's' : '') . ")\n";
            }
            return rtrim($res);
        }

        // ── 2. ROOM / PRICE QUERIES ──────────────────────────────────────────
        $isRoomQuery = mb_strpos($low, 'chambre') !== false
                    || mb_strpos($low, 'room')    !== false
                    || mb_strpos($low, 'prix')    !== false
                    || mb_strpos($low, 'tarif')   !== false
                    || mb_strpos($low, 'cout')    !== false
                    || mb_strpos($low, 'coût')    !== false
                    || mb_strpos($low, 'dispo')   !== false;

        if ($isRoomQuery) {
            $rooms = $data['rooms'] ?? [];

            // If still empty, try fetching without filters so we always return data
            if (empty($rooms)) {
                $rooms = \App\Models\Room::with('hotel.city')
                    ->orderBy('price', 'asc')
                    ->limit(8)
                    ->get()
                    ->map(function ($r) {
                        $hotelName = $r->hotel ? $r->hotel->name : 'Hotel';
                        $cityName  = ($r->hotel && $r->hotel->city) ? $r->hotel->city->name : 'Maroc';
                        return [
                            'hotel'  => $hotelName,
                            'city'   => $cityName,
                            'number' => $r->room_number,
                            'price'  => $r->price,
                            'status' => $r->status,
                            'type'   => $r->type,
                        ];
                    })->toArray();
            }

            if (empty($rooms)) {
                return "Aucune chambre enregistrée dans notre système pour le moment.";
            }

            $title = $currentHotel
                ? "Chambres disponibles - {$currentHotel}"
                : ($currentCity ? "Chambres a {$currentCity}" : "Chambres disponibles dans nos etablissements");

            $res = "{$title} :\n";
            foreach (array_slice($rooms, 0, 6) as $r) {
                $status = $r['status'] === 'available' ? 'Disponible' : ucfirst($r['status']);
                $res .= "- {$r['hotel']} (Ch. {$r['number']}, {$r['type']}) : {$r['price']} DH — {$status}\n";
            }

            if (!$currentHotel && !$currentCity) {
                $res .= "\nPrecisez un hotel ou une ville pour affiner les resultats.";
            }

            return rtrim($res);
        }

        // ── 3. HOTEL LISTING ─────────────────────────────────────────────────
        $isHotelQuery = mb_strpos($low, 'hotel')   !== false
                     || mb_strpos($low, 'hôtel')   !== false
                     || mb_strpos($low, 'etablissement') !== false
                     || mb_strpos($low, 'hébergement')  !== false
                     || mb_strpos($low, 'liste')    !== false
                     || mb_strpos($low, 'cherch')   !== false
                     || mb_strpos($low, 'trouver')  !== false
                     || mb_strpos($low, 'booking')  !== false
                     || mb_strpos($low, 'reservation') !== false;

        if ($isHotelQuery) {
            $hotels = $data['hotels'] ?? [];

            // Fallback: load all hotels if KB returned nothing
            if (empty($hotels)) {
                $hotels = \App\Models\Hotel::with('city')
                    ->orderBy('rating', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function ($h) {
                        $cityName = $h->city ? $h->city->name : 'Maroc';
                        return [
                            'name'    => $h->name,
                            'city'    => $cityName,
                            'rating'  => $h->rating,
                            'address' => $h->address,
                        ];
                    })->toArray();
            }

            if (empty($hotels)) {
                return "Aucun hotel enregistre dans notre systeme actuellement.";
            }

            $title = $currentCity
                ? "Hotels a {$currentCity}"
                : "Nos etablissements";

            $res = "{$title} :\n";
            foreach ($hotels as $h) {
                $stars  = str_repeat('*', (int)($h['rating'] ?? 0));
                $cityTag = !$currentCity && !empty($h['city']) ? " ({$h['city']})" : '';
                $res .= "- {$h['name']}{$cityTag} {$stars}\n";
            }
            return rtrim($res);
        }

        // ── 4. RESERVATION INTENT ────────────────────────────────────────────
        if (mb_strpos($low, 'reserver') !== false || mb_strpos($low, 'réserver') !== false || mb_strpos($low, 'book') !== false) {
            $hotelHint = $currentHotel ? " a {$currentHotel}" : '';
            return "Pour effectuer une reservation{$hotelHint}, connectez-vous sur le site et cliquez sur « Reserver » sur la page de l'hotel souhaite.";
        }

        // ── 5. GENERIC HOTEL-MODE FALLBACK ───────────────────────────────────
        // Return a concise list of all hotels rather than asking a question
        $hotels = \App\Models\Hotel::with('city')->orderBy('rating', 'desc')->limit(8)->get();
        if ($hotels->isEmpty()) {
            return "Je peux vous aider a trouver un hotel ou consulter nos tarifs. Precisez votre destination ou le type de chambre qui vous interesse.";
        }

        $res = "Voici nos principaux etablissements :\n";
        foreach ($hotels as $h) {
            $cityName = $h->city ? $h->city->name : 'Maroc';
            $res .= "- {$h->name} ({$cityName})\n";
        }
        return rtrim($res);
    }
}


