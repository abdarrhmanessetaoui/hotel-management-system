<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(): View
    {
        $reservations = Reservation::with(['hotel', 'room', 'user'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('superadmin.reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation): View
    {
        $reservation->load(['hotel.city', 'room', 'user']);
        return view('superadmin.reservations.show', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled,completed'],
        ]);

        $reservation->update(['status' => $validated['status']]);

        return redirect()->route('superadmin.reservations.index')
            ->with('message', 'Le statut de la réservation a été mis à jour.');
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $reservation->delete();

        return redirect()->route('superadmin.reservations.index')
            ->with('message', 'La réservation a été supprimée.');
    }
}
