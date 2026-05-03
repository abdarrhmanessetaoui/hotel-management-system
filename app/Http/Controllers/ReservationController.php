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
     | CREATE — Afficher le formulaire de réservation
     | Route: GET /hotels/{hotel}/reserve
     |--------------------------------------------------------------------------
     */
    public function create(Hotel $hotel): View
    {
        $rooms = $hotel->rooms()
            ->where('status', 'available')
            ->get();

        $selectedRoomId = request()->query('room') ?? request()->query('room_id');
        if (!is_null($selectedRoomId) && !is_numeric($selectedRoomId)) {
            $selectedRoomId = null;
        }

        return view('pages.reservation-form', compact('hotel', 'rooms', 'selectedRoomId'));
    }

    /*
     |--------------------------------------------------------------------------
     | STORE — Enregistrer la réservation
     | Route: POST /reservations
     |--------------------------------------------------------------------------
     */
    public function store(Request $request): RedirectResponse
    {
        // ── Étape 1 : Validation ─────────────────────────────────────────────
        $validated = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1'],
            'hotel_id' => ['nullable', 'exists:hotels,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'hotel_id.exists' => "L'hôtel sélectionné n'existe pas.",
            'room_id.required' => 'Veuillez sélectionner une chambre.',
            'room_id.exists' => "La chambre sélectionnée n'existe pas.",
            'check_in.required' => "Veuillez saisir une date d'arrivée.",
            'check_in.after_or_equal' => "La date d'arrivée doit être aujourd'hui ou une date future.",
            'check_out.required' => 'Veuillez saisir une date de départ.',
            'check_out.after' => "La date de départ doit être postérieure à la date d'arrivée.",
            'guests.required' => 'Veuillez indiquer le nombre de voyageurs.',
            'guests.min' => 'Au moins 1 voyageur est requis.',
        ]);

        // ── Étape 2 : Vérifier que la chambre appartient à l'hôtel ──────────
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
                'room_id' => "La chambre sélectionnée n'est pas disponible.",
            ]);
        }

        $resolvedHotelId = (int)$room->hotel_id;

        // ── Étape 3 : Vérifier les conflits de dates ─────────────────────────
        $hasConflict = Reservation::where('room_id', $room->id)
            ->whereIn('status', [Reservation::STATUS_PENDING, Reservation::STATUS_CONFIRMED])
            ->where(function ($query) use ($validated) {
            $query->whereDate('check_in', '<', $validated['check_out'])
                ->whereDate('check_out', '>', $validated['check_in']);
        })
            ->exists();

        if ($hasConflict) {
            $message = 'Cette chambre n\'est pas disponible pour les dates sélectionnées.';
            return back()
                ->withInput()
                ->withErrors([
                'room_id' => $message,
                'check_in' => $message,
            ]);
        }

        // ── Étape 4 : Créer la réservation ───────────────────────────────────
        $reservation = Reservation::create([
            'hotel_id' => $resolvedHotelId,
            'room_id' => $room->id,
            'user_id' => Auth::id(),
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'guests' => $validated['guests'],
            'notes' => $validated['notes'] ?? null,
            'status' => Reservation::STATUS_PENDING,
        ]);

        // ── Étape 5 : Redirection avec message de succès ─────────────────────
        return redirect()
            ->route('reservations.show', $reservation)
            ->with('message', 'Votre réservation a été confirmée avec succès.');
    }

    /*
     |--------------------------------------------------------------------------
     | INDEX — Liste des réservations du client connecté
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

        $grouped = [
            'active' => $reservations->whereIn('status', [
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
     | SHOW — Détail d'une réservation
     | Route: GET /reservations/{reservation}
     |--------------------------------------------------------------------------
     */
    public function show(Reservation $reservation): View
    {
        abort_if(
            $reservation->user_id !== Auth::id(),
            403,
            "Vous n'êtes pas autorisé à consulter cette réservation."
        );

        $reservation->load(['hotel.city', 'room']);

        return view('pages.reservation-detail', compact('reservation'));
    }

    /*
     |--------------------------------------------------------------------------
     | CANCEL — Annulation par le client
     | Route: PATCH /reservations/{reservation}/cancel
     |--------------------------------------------------------------------------
     */
    public function cancel(Reservation $reservation): RedirectResponse
    {
        abort_if(
            $reservation->user_id !== Auth::id(),
            403,
            "Vous n'êtes pas autorisé à annuler cette réservation."
        );

        if (!in_array($reservation->status, [
        Reservation::STATUS_PENDING,
        Reservation::STATUS_CONFIRMED,
        ])) {
            return back()->withErrors([
                'status' => 'Cette réservation ne peut pas être annulée car elle est déjà '
                . $reservation->status . '.',
            ]);
        }

        $reservation->update(['status' => Reservation::STATUS_CANCELLED]);

        return redirect()
            ->route('reservations.index')
            ->with('message', 'Votre réservation a été annulée avec succès.');
    }
}
