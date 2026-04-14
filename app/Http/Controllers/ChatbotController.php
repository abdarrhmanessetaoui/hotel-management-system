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
        try {
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
                'success'     => true,
                'session_id'  => $session->id,
                'welcome'     => null,
                'role'        => $role,
                'suggestions' => $this->getDBQuickSuggestions($role)
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Chatbot Init Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => 'Une erreur est survenue lors de l\'initialisation de la session.'
            ], 500);
        }
    }

    public function sendMessage(Request $request): JsonResponse
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'session_id' => 'required',
                'message'    => 'required|string|max:2000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error'   => $validator->errors()->first()
                ], 422);
            }

            $session = ChatSession::find($request->session_id);
            if (!$session) {
                return response()->json(['success' => false, 'error' => 'Session introuvable'], 404);
            }

            if ($session->user_id && $session->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'error' => 'Non autorisé'], 403);
            }

            $userMessage = trim($request->message);
            
            // Execute through the Autonomous Manager Orchestrator
            $botReply = $this->orchestrator->process($session, $userMessage);

            // Track and analyze autonomic intent
            try {
                $this->analytics->logInteraction($session, 'autonomous_process', 1.0);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Analytics Error: ' . $e->getMessage());
                // Non-fatal, continue returning bot reply
            }

            return response()->json([
                'success'     => true,
                'reply'       => $botReply,
                'suggestions' => $this->getDBQuickSuggestions($session->user_role)
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Chatbot Send Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => 'Désolé, j\'ai rencontré une erreur technique. Veuillez réessayer plus tard.',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function getDBQuickSuggestions(string $role): array
    {
        return ChatbotSuggestion::where('is_active', true)->whereIn('role', [$role, 'all'])->pluck('text')->toArray();
    }
}
