<?php

namespace App\Services\AI;

use App\Models\ChatSession;
use App\Models\ChatMessage;

class MemoryManager
{
    /**
     * Get recent message history formatted for AI
     */
    public function getHistory(ChatSession $session, int $limit = 10): array
    {
        return $session->messages()
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get()
            ->map(function ($msg) {
                return [
                    'role' => $msg->sender === 'user' ? 'user' : 'assistant',
                    'content' => $msg->message
                ];
            })
            ->toArray();
    }

    /**
     * Get user preferences stored in session context
     */
    public function getPreferences(ChatSession $session): array
    {
        return $session->context['preferences'] ?? [];
    }

    /**
     * Save interaction and extract new context/preferences
     */
    public function saveInteraction(ChatSession $session, string $userMsg, string $botReply): void
    {
        // Save user message
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'user',
            'message' => $userMsg
        ]);

        // Save bot reply
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'bot',
            'message' => $botReply
        ]);

        $session->incrementMessages();

        // Simple Preference Extraction Logic
        $this->updateContext($session, $userMsg);
    }

    private function updateContext(ChatSession $session, string $msg): void
    {
        $context = $session->context ?? ['preferences' => []];
        $lower = strtolower($msg);

        // Extract potential room preferences
        if (str_contains($lower, 'suite')) $context['preferences']['room_type'] = 'suite';
        if (str_contains($lower, 'single')) $context['preferences']['room_type'] = 'single';
        if (str_contains($lower, 'double')) $context['preferences']['room_type'] = 'double';
        
        // Extract budget signals
        if (str_contains($lower, 'cheap') || str_contains($lower, 'low cost')) {
            $context['preferences']['budget_level'] = 'low';
        }

        $session->update(['context' => $context]);
    }
}
