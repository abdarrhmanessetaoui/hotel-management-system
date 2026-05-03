<?php

namespace App\Services\AI;

use App\Models\Room;
use App\Models\Hotel;
use App\Models\City;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;

/**
 * Advanced Data Retrieval Service (MySQL to AI Context)
 * Operates with keyword extraction to provide targeted backend data.
 */
class KnowledgeBaseService
{
    public function retrieveRelevantContext(string $query, string $role, array $context = []): string
    {
        $lowerQuery = mb_strtolower($query, 'UTF-8');
        
        $currentCity = $context['current_city'] ?? null;
        $currentHotel = $context['current_hotel'] ?? null;

        $data = [
            'hotels'   => $this->getHotels($lowerQuery, $currentCity),
            'rooms'    => $this->getRooms($lowerQuery, $currentCity, $currentHotel),
            'bookings' => $this->getBookings($role, $lowerQuery),
            'context'  => [
                'current_time' => now()->toDateTimeString(),
                'user_role'    => $role,
                'detected_entities' => $this->detectEntities($lowerQuery)
            ]
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function getHotels(string $query, ?string $currentCity): array
    {
        $cities = City::pluck('name')->toArray();
        $citySearch = $currentCity;

        foreach ($cities as $c) {
            if (mb_strpos($query, mb_strtolower($c, 'UTF-8')) !== false) {
                $citySearch = $c;
                break;
            }
        }

        $q = Hotel::with('city');
        if ($citySearch) {
            $q->whereHas('city', fn($sq) => $sq->where('name', 'LIKE', "%$citySearch%"));
        }

        if (mb_strpos($query, 'meilleur') !== false || mb_strpos($query, 'top') !== false || mb_strpos($query, 'mieux') !== false) {
            $q->orderBy('rating', 'desc');
        }

        return $q->limit(8)->get()->map(fn($h) => [
            'name' => $h->name,
            'city' => $h->city->name ?? 'Maroc',
            'rating' => $h->rating,
            'address' => $h->address
        ])->toArray();
    }

    private function getRooms(string $query, ?string $currentCity, ?string $currentHotel): array
    {
        if (mb_strpos($query, 'quel hotel') !== false || mb_strpos($query, 'liste des hotels') !== false) {
            return [];
        }

        $roomQuery = Room::with('hotel.city');
        $cities = City::pluck('name')->toArray();
        $citySearch = $currentCity;

        foreach ($cities as $c) {
            if (mb_strpos($query, mb_strtolower($c, 'UTF-8')) !== false) {
                $citySearch = $c;
                break;
            }
        }

        if ($currentHotel) {
            $roomQuery->whereHas('hotel', fn($q) => $q->where('name', 'LIKE', "%$currentHotel%"));
        } elseif ($citySearch) {
            $roomQuery->whereHas('hotel.city', fn($q) => $q->where('name', 'LIKE', "%$citySearch%"));
        }

        if (mb_strpos($query, 'dispo') !== false) {
            $roomQuery->where('status', 'available');
        }

        return $roomQuery->orderBy('price', 'asc')->limit(8)->get()->map(fn($r) => [
            'hotel' => $r->hotel->name ?? 'Hôtel',
            'rating' => $r->hotel->rating ?? 0,
            'city' => $r->hotel->city->name ?? 'Maroc',
            'number' => $r->room_number,
            'price' => $r->price,
            'status' => $r->status,
            'type' => $r->type
        ])->toArray();
    }

    private function getBookings(string $role, string $query): array
    {
        if ($role === 'client') return [];
        return Reservation::with('user', 'room.hotel')->orderBy('id', 'desc')->limit(3)->get()->map(fn($b) => [
            'user' => $b->user->name ?? 'Guest',
            'hotel' => $b->room->hotel->name ?? 'N/A',
            'status' => $b->status
        ])->toArray();
    }

    private function detectEntities(string $query): array
    {
        $entities = [];
        $cities = City::pluck('name')->toArray();
        foreach ($cities as $c) {
            if (mb_strpos($query, mb_strtolower($c, 'UTF-8')) !== false) $entities[] = 'City:' . $c;
        }
        return $entities;
    }
}

