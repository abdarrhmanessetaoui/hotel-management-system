<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use Illuminate\Support\Facades\DB;

class ChatbotConsoleController extends Controller
{
    public function index()
    {
        $totalSessions = ChatSession::count();
        
        $conversionRate = 12;
        
        $dailyLabels = collect(range(0, 6))->map(function($d) {
            return now()->subDays(6 - $d)->format('D');
        })->toArray();
        
        $dailyMessages = [15, 22, 18, 30, 25, 45, 50]; 
        
        $topMessages = [
            (object)['message' => 'Hôtels à Marrakech', 'count' => 140],
            (object)['message' => 'Politique d\'annulation', 'count' => 95],
            (object)['message' => 'Disponibilités pour ce soir', 'count' => 60],
            (object)['message' => 'Prix des suites', 'count' => 45],
        ];

        return view('superadmin.chatbot', compact('totalSessions', 'conversionRate', 'dailyLabels', 'dailyMessages', 'topMessages'));
    }
}
