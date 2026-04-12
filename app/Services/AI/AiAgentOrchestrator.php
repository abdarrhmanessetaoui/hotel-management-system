<?php

namespace App\Services\AI;

use App\Models\ChatSession;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAgentOrchestrator
{
    private MemoryManager $memory;
    private KnowledgeBaseService $kb;
    private ToolRegistry $tools;

    public function __construct(
        MemoryManager $memory, 
        KnowledgeBaseService $kb, 
        ToolRegistry $tools
    ) {
        $this->memory = $memory;
        $this->kb = $kb;
        $this->tools = $tools;
    }

    /**
     * Process User Message and Return Intelligent Response
     */
    public function processMessage(ChatSession $session, string $userMessage): string
    {
        try {
            // 1. Detect Intent and Language
            $language = $this->detectLanguage($userMessage);
            
            // 2. Retrieve Context (Short-term & Long-term)
            $history = $this->memory->getHistory($session);
            $preferences = $this->memory->getPreferences($session);
            
            // 3. RAG: Retrieve relevant hotel data
            $localContext = $this->kb->retrieveRelevantContext($userMessage, $session->user_role);
            
            // 4. Build Prompt with RAG + Context + System Instructions
            $systemPrompt = $this->buildSystemPrompt($session->user_role, $language, $localContext, $preferences);
            
            // 5. Call LLM (OpenAI / Gemini)
            $response = $this->callLLM($systemPrompt, $history, $userMessage);

            // 6. Update Memory & Analytics
            $this->memory->saveInteraction($session, $userMessage, $response);
            
            return $response;

        } catch (\Exception $e) {
            Log::error("AiAgentOrchestrator Error: " . $e->getMessage());
            return $this->getFallbackResponse($session->language ?? 'fr');
        }
    }

    private function detectLanguage(string $text): string
    {
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $text)) return 'ar';
        if (preg_match('/\b(the|is|are|how|what|where|when|book)\b/i', $text)) return 'en';
        return 'fr';
    }

    private function buildSystemPrompt(string $role, string $lang, string $context, array $prefs): string
    {
        $base = "You are the Hotelia AI Agent. Multi-lingual support: Arabic, English, French. ";
        $base .= "Current Context: " . $context . ". ";
        $base .= "User Role: " . $role . ". ";
        $base .= "User Preferences: " . json_encode($prefs) . ". ";
        $base .= "Instructions: Be concise, professional, and helpful. Guide the user towards booking if they express interest.";
        
        return $base;
    }

    private function callLLM(string $system, array $history, string $userMsg): string
    {
        $apiKey = config('chatbot.openai_key');
        if (!$apiKey) return "AI Service Unavailable. Use rule-based fallback.";

        $messages = array_merge([['role' => 'system', 'content' => $system]], $history);
        $messages[] = ['role' => 'user', 'content' => $userMsg];

        $response = Http::withHeaders(['Authorization' => "Bearer {$apiKey}"])
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4-turbo-preview',
                'messages' => $messages,
                'temperature' => 0.7
            ]);

        return $response->json('choices.0.message.content', 'Error fetching reply.');
    }

    private function getFallbackResponse(string $lang): string
    {
        return $lang === 'ar' ? 'عذراً، واجهت مشكلة. سأساعدك قريباً.' : 'Désolé, j\'ai rencontré un problème. Je vous aiderai bientôt.';
    }
}
