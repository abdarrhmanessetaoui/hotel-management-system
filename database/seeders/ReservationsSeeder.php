<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationsSeeder extends Seeder
{
    public function run(): void
    {
        // We only want clients to create reservations.
        $clients = DB::table('users')
            ->where('role', 'client')
            ->orderBy('id')
            ->get();

        // Rooms already reference hotels via rooms.hotel_id.
        $rooms = DB::table('rooms')
            ->select(['id', 'hotel_id', 'room_number'])
            ->orderBy('id')
            ->get();

        if ($clients->isEmpty() || $rooms->isEmpty()) {
            // Nothing to seed if prerequisite tables are empty.
            return;
        }

        // We'll create a handful of reservations with deterministic selection.
        // Re-running the seeder won't duplicate rows because we check (user_id, room_id, check_in, check_out).
        $reservationsToCreate = 20;

        for ($k = 0; $k < $reservationsToCreate; $k++) {
            $user = $clients[$k % $clients->count()];
            $room = $rooms[$k % $rooms->count()];

            // Build a date range in the past/future relative to "today".
            // Reservations table stores dates (not datetimes) in this project.
            $checkIn = now()->addDays(3 + $k)->subDays(10)->toDateString();   // shifts around
            $checkOut = now()->addDays(6 + $k)->subDays(10)->toDateString(); // always after check_in

            // Skip invalid ranges (just in case)
            if ($checkOut <= $checkIn) {
                continue;
            }

            $guests = ($k % 4) + 1; // 1..4

            // Choose a status (match project enum / Reservation constants)
            $statusCycle = [
                Reservation::STATUS_PENDING,
                Reservation::STATUS_CONFIRMED,
                Reservation::STATUS_CANCELLED,
                Reservation::STATUS_PENDING,
            ];
            $status = $statusCycle[$k % count($statusCycle)];

            $notes = ($k % 3 === 0) ? 'Guest special request: late check-in.' : null;

            // Prevent duplicates on re-run by checking a composite of key columns.
            $exists = DB::table('reservations')
                ->where('user_id', $user->id)
                ->where('room_id', $room->id)
                ->whereDate('check_in', $checkIn)
                ->whereDate('check_out', $checkOut)
                ->exists();

            if ($exists) {
                continue;
            }

            // Insert reservation respecting relationships:
            // - reservation.user_id references users.id (client)
            // - reservation.room_id references rooms.id
            // - reservation.hotel_id must match room.hotel_id (and thus city -> hotel -> room chain)
            DB::table('reservations')->insert([
                'hotel_id'   => $room->hotel_id,
                'room_id'    => $room->id,
                'user_id'    => $user->id,
                'check_in'   => $checkIn,
                'check_out'  => $checkOut,
                'guests'     => $guests,
                'status'     => $status, // pending|confirmed|cancelled|completed (uses project constants)
                'notes'      => $notes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
