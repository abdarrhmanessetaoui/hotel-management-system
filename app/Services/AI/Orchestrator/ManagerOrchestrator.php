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
            $intent = $this->detectIntent($userMessage);
            $jsonData = $this->kb->retrieveRelevantContext($userMessage, $session->user_role);

            return $this->generateHighFidelityResponse($intent, $jsonData, $userMessage);

        } catch (\Exception $e) {
            Log::error("Enterprise AI Failure: " . $e->getMessage());
            return "Une assistance est disponible au +212 599-887766 pour vos questions urgentes.";
        }
    }

    protected function detectIntent(string $msg): string
    {
        $lower = mb_strtolower($msg, 'UTF-8');
        
        // Extended keyword map for robust routing
        $hotelTerms = [
            'chambre', 'room', 'hotel', 'hôtel', 'prix', 'tarifs', 'dispo', 'disponibilité',
            'marrackech', 'agadir', 'casablanca', 'annul', 'politique', 'réservation', 
            'reservation', 'booking', 'support', 'aide', 'contact', 'client', 'statut'
        ];

        foreach ($hotelTerms as $term) {
            if (mb_strpos($lower, $term) !== false) return 'HOTEL_MODE';
        }

        return 'AI_MODE';
    }

    protected function generateHighFidelityResponse(string $intent, string $jsonData, string $msg): string
    {
        $apiKey = config('chatbot.openai_key');
        $model  = config('chatbot.openai_model', 'gpt-3.5-turbo');

        if ($apiKey) {
            $system = ($intent === 'HOTEL_MODE') 
                ? $this->getHotelModePrompt($jsonData)
                : $this->getAiModePrompt();

            $response = Http::withHeaders(['Authorization' => "Bearer {$apiKey}"])
                ->timeout(12)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [['role' => 'system', 'content' => $system], ['role' => 'user', 'content' => $msg]],
                    'temperature' => ($intent === 'HOTEL_MODE') ? 0.2 : 0.7
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }
            Log::warning("AI Service Down (429/500). Activating Intelligent Deterministic Backup.", ['status' => $response->status()]);
        }

        return $this->renderLocalSaasFallback($jsonData, $msg);
    }

    private function getHotelModePrompt(string $jsonData): string
    {
        return "Assistant Hotelia (MODE RÉEL).\nHIÉRARCHIE : Ville -> Hôtel -> Chambre.\nDATA : {$jsonData}.\nSTRICT : Pas d'invention. Si absent -> 'Aucune donnée disponible actuellement.'";
    }

    private function getAiModePrompt(): string
    {
        return "Assistant Concierge (MODE GÉNÉRAL).\nRépondez comme ChatGPT. Style naturel, conversationnel et utile.";
    }

    protected function renderLocalSaasFallback(string $jsonData, string $msg): string
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

        // 3. Hotels & Establishments
        if (mb_strpos($low, 'hotel') !== false || mb_strpos($low, 'établissement') !== false) {
            $hotels = $data['hotels'] ?? [];
            if (empty($hotels)) return "Aucune donnée hôtelière disponible actuellement.";
            $res = "🏨 **Nos Établissements** :\n";
            foreach ($hotels as $h) { 
                $res .= "- **{$h['name']}** ({$h['city']}) | ⭐ {$h['rating']}\n"; 
            }
            return $res . "\nLequel de ces établissements vous intéresse ?";
        }


        // 4. Room Availability
        if (mb_strpos($low, 'chambre') !== false || mb_strpos($low, 'dispo') !== false || 
            mb_strpos($low, 'marrakech') !== false || mb_strpos($low, 'agadir') !== false || 
            mb_strpos($low, 'tanger') !== false || mb_strpos($low, 'casablanca') !== false) {
            $rooms = $data['rooms'] ?? [];

            if (empty($rooms)) return "Aucune disponibilité trouvée pour ces critères actuellement.";
            $res = "🛏️ **Disponibilités sélectionnées** :\n";
            foreach (array_slice($rooms, 0, 4) as $r) {
                $res .= "- {$r['hotel']} (⭐ {$r['rating']}) | Ch. {$r['number']} : **{$r['price']} DH**\n";
            }
            return $res . "\nComment puis-je vous aider pour votre réservation ?";
        }

        // 5. Intelligent Greetings
        if (mb_strpos($low, 'bonjour') !== false || mb_strpos($low, 'hello') !== false || mb_strpos($low, 'salut') !== false) {
            return "Bonjour ! Je suis l'Assistant Hotelia. Comment puis-je vous aider avec vos recherches d'hôtels ou de chambres aujourd'hui ? (Mode local actif)";
        }

        return "Je suis en mode d'assistance limitée. Je peux vous renseigner sur les hôtels, les prix, les annulations ou le support.";
    }
}
