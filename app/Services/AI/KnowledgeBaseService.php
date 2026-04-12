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
    public function retrieveRelevantContext(string $query, string $role): string
    {
        $lowerQuery = mb_strtolower($query, 'UTF-8');

        $data = [
            'hotels'   => $this->getHotels($lowerQuery),
            'rooms'    => $this->getRooms($lowerQuery),
            'bookings' => $this->getBookings($role, $lowerQuery),
            'context'  => [
                'current_time' => now()->toDateTimeString(),
                'user_role'    => $role,
                'detected_entities' => $this->detectEntities($lowerQuery)
            ]
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function getHotels(string $query): array
    {
        $citySearch = null;
        $cities = ['marrakech', 'agadir', 'casablanca', 'tanger'];
        foreach ($cities as $c) {
            if (mb_strpos($query, $c) !== false) {
                $citySearch = ucfirst($c);
                break;
            }
        }

        $q = Hotel::with('city');
        if ($citySearch) {
            $q->whereHas('city', fn($sq) => $sq->where('name', 'LIKE', "%$citySearch%"));
        }

        // Handle "best" or "top" queries by sorting by rating
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

    private function getRooms(string $query): array
    {
        if (mb_strpos($query, 'quel hotel') !== false || mb_strpos($query, 'liste des hotels') !== false) {
            return [];
        }

        $roomQuery = Room::with('hotel.city');

        $cities = ['marrakech', 'agadir', 'casablanca', 'tanger'];
        foreach ($cities as $c) {
            if (mb_strpos($query, $c) !== false) {
                $city = ucfirst($c);
                $roomQuery->whereHas('hotel.city', fn($q) => $q->where('name', 'LIKE', "%$city%"));
                break;
            }
        }

        if (mb_strpos($query, 'dispo') !== false) {
            $roomQuery->where('status', 'available');
        }

        return $roomQuery->orderBy('price', 'asc')->limit(8)->get()->map(fn($r) => [
            'hotel' => $r->hotel->name,
            'rating' => $r->hotel->rating,
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
        $cities = ['marrakech', 'agadir', 'casablanca', 'tanger'];
        foreach ($cities as $c) {
            if (mb_strpos($query, $c) !== false) $entities[] = 'City:' . ucfirst($c);
        }
        return $entities;
    }
}
