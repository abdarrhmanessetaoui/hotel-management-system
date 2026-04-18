<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    private function getAdminHotel()
    {
        $hotel = Auth::user()->hotel;
        abort_if(!$hotel, 403, 'Aucun hôtel n\'est assigné à votre compte.');
        return $hotel;
    }

    public function index(): View
    {
        $hotel = $this->getAdminHotel();

        $reservations = $hotel->reservations()
            ->with(['room', 'user'])
            ->orderByDesc('check_in')
            ->get();

        return view('admin.reservations.index', compact('hotel', 'reservations'));
    }

    public function show(Reservation $reservation): View
    {
        $hotel = $this->getAdminHotel();
        abort_if($reservation->hotel_id !== $hotel->id, 403);

        $reservation->load(['room', 'user']);

        return view('admin.reservations.show', compact('reservation'));
    }

    /**
     * Update reservation status (confirm / cancel / complete).
     */
    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        $hotel = $this->getAdminHotel();
        abort_if($reservation->hotel_id !== $hotel->id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled,completed'],
        ]);

        $reservation->update(['status' => $validated['status']]);

        return redirect()->route('admin.reservations.index')
            ->with('message', 'Le statut de la réservation a été mis à jour.');
    }
}