<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * ChatbotStatsController
 *
 * Provides analytics data for the AI Chatbot Statistics Dashboard.
 * Accessible only to Admin and Super Admin roles.
 */
class ChatbotStatsController extends Controller
{
    /**
     * Display the chatbot statistics dashboard.
     * Returns the view with pre-loaded aggregate data.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        // ── Overview cards ────────────────────────────────────────────────────

        // Total sessions ever
        $totalSessions = ChatSession::count();

        // Total messages (user + bot)
        $totalMessages = ChatMessage::count();

        // Average satisfaction rating (1-5)
        $avgSatisfaction = ChatSession::whereNotNull('satisfaction')
            ->avg('satisfaction');

        // Sessions that resulted in a booking intent
        $conversions = ChatSession::where('converted', true)->count();
        $conversionRate = $totalSessions > 0
            ? round(($conversions / $totalSessions) * 100, 1)
            : 0;

        // Users currently active (sessions in the last 30 min)
        $activeUsers = ChatSession::where('updated_at', '>=', now()->subMinutes(30))->count();

        // ── Daily usage (last 14 days) ─────────────────────────────────────────

        $dailyUsage = ChatSession::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as sessions'),
            DB::raw('SUM(message_count) as messages')
        )
            ->where('created_at', '>=', now()->subDays(13))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Fill missing days with zeros
        $dailyLabels = [];
        $dailySessions = [];
        $dailyMessages = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $found = $dailyUsage->firstWhere('date', $date);
            $dailyLabels[]   = now()->subDays($i)->format('d/m');
            $dailySessions[] = $found?->sessions ?? 0;
            $dailyMessages[] = $found?->messages ?? 0;
        }

        // ── Intent distribution (pie chart) ──────────────────────────────────

        $intentData = ChatMessage::where('sender', 'user')
            ->whereNotNull('intent')
            ->select('intent', DB::raw('COUNT(*) as count'))
            ->groupBy('intent')
            ->orderByDesc('count')
            ->get();

        // ── Role distribution ─────────────────────────────────────────────────

        $roleData = ChatSession::select('user_role', DB::raw('COUNT(*) as count'))
            ->groupBy('user_role')
            ->get();

        // ── Most asked questions (top user messages) ──────────────────────────

        $topMessages = ChatMessage::where('sender', 'user')
            ->select('message', DB::raw('COUNT(*) as count'))
            ->groupBy('message')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        // ── Recent sessions ───────────────────────────────────────────────────

        $recentSessions = ChatSession::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('superadmin.chatbot', compact(
            'totalSessions',
            'totalMessages',
            'avgSatisfaction',
            'conversionRate',
            'activeUsers',
            'dailyLabels',
            'dailySessions',
            'dailyMessages',
            'intentData',
            'roleData',
            'topMessages',
            'recentSessions'
        ));

    }
}
