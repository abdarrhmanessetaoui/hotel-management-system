<?php

namespace App\Services\AI\Agents;

use App\Services\Booking\BookingAssistantService;
use Illuminate\Support\Facades\Log;

/**
 * Specialized Booking Agent
 * Handles all CRUD operations related to reservations.
 */
class BookingAgent
{
    private BookingAssistantService $service;

    public function __construct(BookingAssistantService $service)
    {
        $this->service = $service;
    }

    public function getSystemInstructions(): string
    {
        return "You are the Booking Specialist. You can find rooms, check availability, and manage reservations. "
             . "Always confirm dates and guest counts accurately. ";
    }

    /**
     * Parse intent and return proposed action
     */
    public function handle(string $msg, array $context): array
    {
        $lower = strtolower($msg);
        
        if (str_contains($lower, 'cancel') || str_contains($lower, 'annuler')) {
             return [
                 'action' => 'cancel_booking',
                 'payload' => ['reservation_id' => $context['last_reservation_id'] ?? null],
                 'reasoning' => 'User explicitly requested a cancellation of their last known booking.'
             ];
        }

        if (str_contains($lower, 'book') || str_contains($lower, 'réserver')) {
            return [
                'action' => 'create_booking',
                'payload' => [
                    'check_in' => $context['check_in'] ?? null,
                    'guests' => $context['guests'] ?? 1
                ],
                'reasoning' => 'User expressed intent to start a new booking flow.'
            ];
        }

        return ['action' => 'none', 'payload' => [], 'reasoning' => 'Informational query only.'];
    }
}

