<?php

namespace App\Services\Booking;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingAssistantService
{
    /**
     * Real-time availability check
     */
    public function findAvailableRooms(?int $hotelId, array $dates): array
    {
        $query = Room::where('is_available', true);
        
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }

        return $query->with('hotel')->limit(5)->get()->map(function($room) {
            return [
                'id' => $room->id,
                'hotel' => $room->hotel->name,
                'price' => $room->price_per_night,
                'room_number' => $room->room_number
            ];
        })->toArray();
    }

    /**
     * Create reservation from chat flow
     */
    public function createReservation(array $data): array
    {
        try {
            return DB::transaction(function() use ($data) {
                $reservation = Reservation::create([
                    'room_id' => $data['room_id'],
                    'user_id' => $data['user_id'] ?? null, // Fallback for guest
                    'check_in' => $data['check_in'],
                    'check_out' => $data['check_out'],
                    'guests' => $data['guests'] ?? 1,
                    'status' => 'pending'
                ]);

                return ['success' => true, 'id' => $reservation->id];
            });
        } catch (\Exception $e) {
            Log::error("BookingAssistant Error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

