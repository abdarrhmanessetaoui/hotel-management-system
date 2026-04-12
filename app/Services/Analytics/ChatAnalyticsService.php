<?php

namespace App\Services\Analytics;

use App\Models\ChatbotAnalytic;
use App\Models\ChatSession;

class ChatAnalyticsService
{
    /**
     * Log interaction analytics
     */
    public function logInteraction(ChatSession $session, string $intent, float $confidence, bool $isFallback = false): void
    {
        ChatbotAnalytic::create([
            'chat_session_id' => $session->id,
            'intent' => $intent,
            'confidence' => $confidence,
            'role' => $session->user_role,
            'is_fallback' => $isFallback,
            'booking_triggered' => in_array($intent, ['booking', 'reservation'])
        ]);
    }

    /**
     * General dashboard stats
     */
    public function getAnalyticsSummary(): array
    {
        return [
            'total_interactions' => ChatbotAnalytic::count(),
            'conversion_rate' => ChatSession::where('converted', true)->count() / max(1, ChatSession::count()),
            'top_intents' => ChatbotAnalytic::select('intent')
                ->selectRaw('count(*) as total')
                ->groupBy('intent')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get()
        ];
    }
}
