<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE — Show reservation form
    | Route: GET /hotels/{hotel}/reserve
    |
    | The hotel detail page links here with an optional ?room=ID query param:
    |   /hotels/5/reserve?room=12
    | If present, that room will be pre-selected in the form.
    |--------------------------------------------------------------------------
    */
    public function create(Hotel $hotel): View
    {
        // Only offer available rooms — unavailable ones are not bookable
        $rooms = $hotel->rooms()
            ->where('status', 'available')
            ->get();

        // Pre-select a room if passed via query string from the hotel detail page
        // e.g. clicking "Reserve" on a specific room card
        $selectedRoomId = request()->query('room') ?? request()->query('room_id');
        if (!is_null($selectedRoomId) && !is_numeric($selectedRoomId)) {
            $selectedRoomId = null;
        }

        return view('pages.reservation-form', compact('hotel', 'rooms', 'selectedRoomId'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE — Process and save the reservation
    | Route: POST /reservations
    |--------------------------------------------------------------------------
    */
    public function store(Request $request): RedirectResponse
    {
        // ── Step 1: Validate all inputs ──────────────────────────────────────
        $validated = $request->validate([
            'room_id'   => ['required', 'exists:rooms,id'],
            'check_in'  => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests'    => ['required', 'integer', 'min:1'],
            // Optional: if provided, we will verify it matches the selected room.
            // If omitted, we derive it from the room itself.
            'hotel_id'  => ['nullable', 'exists:hotels,id'],
            'notes'     => ['nullable', 'string', 'max:500'],
        ], [
            // ── Human-readable error messages ────────────────────────────────
            'hotel_id.exists'    => 'The selected hotel does not exist.',
            'room_id.required'   => 'Please select a room.',
            'room_id.exists'     => 'The selected room does not exist.',
            'check_in.required'  => 'Please enter a check-in date.',
            'check_in.after_or_equal' => 'Check-in date must be today or a future date.',
            'check_out.required' => 'Please enter a check-out date.',
            'check_out.after'    => 'Check-out must be after the check-in date.',
            'guests.required'    => 'Please enter the number of guests.',
            'guests.min'         => 'At least 1 guest is required.',
        ]);

        // ── Step 2: Verify room belongs to the given hotel ───────────────────
        // This prevents a user from spoofing a room_id from a different hotel.
        // If `hotel_id` is not provided, we derive it from the room.
        $roomQuery = Room::where('id', $validated['room_id'])
            ->where('status', 'available');

        if (!empty($validated['hotel_id'])) {
            $roomQuery->where('hotel_id', $validated['hotel_id']);
        }

        $room = $roomQuery->first();

        if (!$room) {
            return back()
                ->withInput()
                ->withErrors([
                    'room_id' => 'The selected room is not available.',
                ]);
        }

        $resolvedHotelId = (int) $room->hotel_id;

        // ── Step 3: Check for date conflicts on this room ────────────────────
        // Checks no active reservation overlaps the requested dates.
        // Overlap rule: existing.check_in < new.check_out AND existing.check_out > new.check_in
        $hasConflict = Reservation::where('room_id', $room->id)
            ->whereIn('status', [Reservation::STATUS_PENDING, Reservation::STATUS_CONFIRMED])
            ->where(function ($query) use ($validated) {
                $query->whereDate('check_in', '<', $validated['check_out'])
                    ->whereDate('check_out', '>', $validated['check_in']);
            })
            ->exists();

        if ($hasConflict) {
            $message = 'This room is not available for the selected dates.';
            return back()
                ->withInput()
                ->withErrors([
                    'room_id'  => $message,
                    'check_in' => $message,
                ]);
        }

        // ── Step 4: Create the reservation ───────────────────────────────────
        $reservation = Reservation::create([
            'hotel_id'  => $resolvedHotelId,
            'room_id'   => $room->id,
            'user_id'   => Auth::id(),
            'check_in'  => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'guests'    => $validated['guests'],
            'notes'     => $validated['notes'] ?? null,
            'status'    => Reservation::STATUS_PENDING,
        ]);

        // ── Step 5: Redirect with success feedback ────────────────────────────
        return redirect()
            ->route('reservations.show', $reservation)
            ->with('message', 'Reservation confirmed successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX — List the authenticated user's reservations
    | Route: GET /reservations
    |--------------------------------------------------------------------------
    */
    public function index(): View
    {
        $reservations = Auth::user()
            ->reservations()
            ->with(['hotel', 'room'])
            ->orderByDesc('check_in')
            ->get();

        // Group by status so the view can display tabs: upcoming / past / cancelled
        $grouped = [
            'active'    => $reservations->whereIn('status', [
                                Reservation::STATUS_PENDING,
                                Reservation::STATUS_CONFIRMED,
                           ]),
            'completed' => $reservations->where('status', Reservation::STATUS_COMPLETED),
            'cancelled' => $reservations->where('status', Reservation::STATUS_CANCELLED),
        ];

        return view('pages.reservations', compact('reservations', 'grouped'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW — View a single reservation's detail
    | Route: GET /reservations/{reservation}
    |--------------------------------------------------------------------------
    */
    public function show(Reservation $reservation): View
    {
        // Clients can only view their own reservations
        abort_if(
            $reservation->user_id !== Auth::id(),
            403,
            'You are not authorized to view this reservation.'
        );

        $reservation->load(['hotel.city', 'room']);

        return view('pages.reservation-detail', compact('reservation'));
    }

    /*
    |--------------------------------------------------------------------------
    | CANCEL — Client cancels their own reservation
    | Route: PATCH /reservations/{reservation}/cancel
    |--------------------------------------------------------------------------
    */
    public function cancel(Reservation $reservation): RedirectResponse
    {
        // Security: only the reservation owner can cancel
        abort_if(
            $reservation->user_id !== Auth::id(),
            403,
            'You are not authorized to cancel this reservation.'
        );

        // Only pending or confirmed reservations can be cancelled
        // Completed and already-cancelled ones are immutable
        if (!in_array($reservation->status, [
            Reservation::STATUS_PENDING,
            Reservation::STATUS_CONFIRMED,
        ])) {
            return back()->withErrors([
                'status' => 'This reservation cannot be cancelled as it is already '
                          . $reservation->status . '.',
            ]);
        }

        $reservation->update(['status' => Reservation::STATUS_CANCELLED]);

        return redirect()
            ->route('reservations.index')
            ->with('message', 'Your reservation has been cancelled successfully.');
    }
}
