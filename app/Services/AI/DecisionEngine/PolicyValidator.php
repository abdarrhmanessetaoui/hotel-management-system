<?php

namespace App\Services\AI\DecisionEngine;

use App\Models\Reservation;
use Illuminate\Support\Facades\Log;

/**
 * Enterprise Decision Engine
 * Validates AI Agent action requests against strict hotel policies.
 */
class PolicyValidator
{
    /**
     * Validate a proposed action
     * Returns ['valid' => bool, 'reason' => ?string]
     */
    public function validate(string $agent, string $action, array $payload): array
    {
        Log::info("DecisionEngine: Validating {$agent} -> {$action}");

        return match ($action) {
            'cancel_booking' => $this->validateCancellation($payload),
            'process_refund' => $this->validateRefund($payload),
            'create_booking' => $this->validateBookingIntent($payload),
            default => ['valid' => true, 'reason' => null] // Default to passive actions
        };
    }

    private function validateCancellation(array $payload): array
    {
        $id = $payload['reservation_id'] ?? null;
        if (!$id) return ['valid' => false, 'reason' => 'No reservation ID provided.'];

        $res = Reservation::find($id);
        if (!$res) return ['valid' => false, 'reason' => 'Reservation not found.'];

        // Policy: No autonomous cancellations if start date is within 24 hours
        if ($res->check_in->diffInHours(now()) < 24) {
            return ['valid' => false, 'reason' => 'Strict Policy: Cancellations within 24h require human staff approval.'];
        }

        return ['valid' => true, 'reason' => null];
    }

    private function validateRefund(array $payload): array
    {
        // Autonomous refunds are strictly capped at 200€ without staff approval
        $amount = $payload['amount'] ?? 0;
        if ($amount > 200) {
            return ['valid' => false, 'reason' => 'Amount exceeds autonomous refund limit (200€).'];
        }

        return ['valid' => true, 'reason' => null];
    }

    private function validateBookingIntent(array $payload): array
    {
        // Ensure guests count is allowed
        if (($payload['guests'] ?? 0) > 10) {
            return ['valid' => false, 'reason' => 'Groups larger than 10 require manual coordination.'];
        }

        return ['valid' => true, 'reason' => null];
    }
}

