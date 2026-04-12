<?php

namespace App\Services\AI\Agents;

use Illuminate\Support\Facades\Log;

/**
 * Specialized Support Agent
 * Handles complaints, issue resolution, and general support logic.
 */
class SupportAgent
{
    public function getSystemInstructions(): string
    {
        return "You are the Guest Support Manager. Your goal is to resolve complaints and provide information. "
             . "Show empathy but stay objective. Follow refund policies strictly. ";
    }

    public function handle(string $msg, array $context): array
    {
        $lower = strtolower($msg);
        
        if (str_contains($lower, 'dirty') || str_contains($lower, 'problem') || str_contains($lower, 'complain')) {
             return [
                 'action' => 'process_compensation',
                 'payload' => ['reason' => 'Service complaint', 'suggested_discount' => 15],
                 'reasoning' => 'User reported a service issue. Proposing a standard 15% discount as initial resolution logic.'
             ];
        }

        return ['action' => 'none', 'payload' => [], 'reasoning' => 'Informational/Support query.'];
    }
}
