<?php

namespace App\Services\AI\Agents;

use App\Models\Room;
use Illuminate\Support\Facades\Log;

/**
 * Specialized Sales & Revenue Agent
 * Focuses on upselling, suggesting upgrades, and maximizing revenue.
 */
class SalesAgent
{
    public function getSystemInstructions(): string
    {
        return "You are the Revenue Specialist. Your goal is to maximize booking value. "
             . "Suggest room upgrades and additional services where appropriate. ";
    }

    public function handle(string $msg, array $context): array
    {
        // Simple Revenue Optimization Logic
        if (isset($context['preferences']['room_type']) && $context['preferences']['room_type'] === 'single') {
             return [
                 'action' => 'upsell_upgrade',
                 'payload' => ['target_type' => 'double', 'benefit' => 'More space & breakfast included'],
                 'reasoning' => 'User is looking at high-occupancy dates for a single room. Upselling to double provides better value.'
             ];
        }

        return ['action' => 'none', 'payload' => [], 'reasoning' => 'Standard sales interaction.'];
    }
}
