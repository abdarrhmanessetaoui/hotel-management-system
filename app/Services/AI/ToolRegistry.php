<?php

namespace App\Services\AI;

use App\Services\Booking\BookingAssistantService;
use Illuminate\Support\Facades\Log;

/**
 * Registry for executable actions by the AI Agent
 */
class ToolRegistry
{
    private BookingAssistantService $booking;

    public function __construct(BookingAssistantService $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Define availability tools
     */
    public function checkAvailability(array $args): array
    {
        return $this->booking->findAvailableRooms($args['hotel_id'] ?? null, $args['dates'] ?? []);
    }

    /**
     * Define direct booking tool
     */
    public function processBooking(array $args): array
    {
        return $this->booking->createReservation($args);
    }
}

