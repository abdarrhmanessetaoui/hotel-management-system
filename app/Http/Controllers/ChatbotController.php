<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatbotSuggestion;
use App\Services\AI\Orchestrator\ManagerOrchestrator;
use App\Services\Analytics\ChatAnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Autonomous Hotel Manager Controller
 * Harnesses the ManagerOrchestrator to provide a fully autonomous support and operation experience.
 */
class ChatbotController extends Controller
{
    public function __construct(
        protected ManagerOrchestrator $orchestrator, 
        protected ChatAnalyticsService $analytics
    ) {}

    public function startSession(Request $request): JsonResponse
    {
        $user = Auth::user();
        $role = $user ? ($user->isSuperAdmin() ? 'superadmin' : ($user->isAdmin() ? 'admin' : 'client')) : 'client';

        $session = ChatSession::create([
            'user_id'   => $user?->id,
            'user_role' => $role,
            'language'  => 'fr',
            'context'   => ['preferences' => []],
            'last_active_at' => now()
        ]);

        return response()->json([
            'session_id'  => $session->id,
            'welcome'     => null,


            'role'        => $role,
            'suggestions' => $this->getDBQuickSuggestions($role)
        ]);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => 'required|exists:chat_sessions,id',
            'message'    => 'required|string|max:2000',
        ]);

        $session = ChatSession::findOrFail($request->session_id);
        
        if ($session->user_id && $session->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $userMessage = trim($request->message);
        
        // Execute through the Autonomous Manager Orchestrator
        $botReply = $this->orchestrator->process($session, $userMessage);

        // Track and analyze autonomic intent
        $this->analytics->logInteraction($session, 'autonomous_process', 1.0);

        return response()->json([
            'reply'       => $botReply,
            'suggestions' => $this->getDBQuickSuggestions($session->user_role)
        ]);
    }

    private function getDBQuickSuggestions(string $role): array
    {
        return ChatbotSuggestion::where('is_active', true)->whereIn('role', [$role, 'all'])->pluck('text')->toArray();
    }
}
