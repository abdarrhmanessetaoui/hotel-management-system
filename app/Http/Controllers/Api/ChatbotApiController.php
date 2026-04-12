<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;

/**
 * Production-ready Chatbot API
 * Exposes hotel platform data for AI Orchestration.
 */
class ChatbotApiController extends Controller
{
    public function cities()
    {
        return response()->json(City::all(['id', 'name']));
    }

    public function hotels(Request $request)
    {
        $query = Hotel::with('city:id,name');
        if ($request->city) {
            $query->whereHas('city', fn($q) => $q->where('name', 'LIKE', "%{$request->city}%"));
        }
        return response()->json($query->get());
    }

    public function rooms(Request $request)
    {
        $query = Room::with('hotel:id,name');
        
        if ($request->hotel) {
            $query->whereHas('hotel', fn($q) => $q->where('name', 'LIKE', "%{$request->hotel}%"));
        }
        
        if ($request->city) {
            $query->whereHas('hotel.city', fn($q) => $q->where('name', 'LIKE', "%{$request->city}%"));
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return response()->json($query->limit(10)->get());
    }

    public function bookings(Request $request)
    {
        // Internal data only for auth/admin
        return response()->json(Reservation::with('user:id,name', 'room:id,room_number')->orderBy('id', 'desc')->limit(5)->get());
    }
}
