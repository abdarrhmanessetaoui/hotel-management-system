<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Hotel admin dashboard.
     * Shows stats scoped to THIS admin's hotel only.
     */
    public function index(): View
    {
        $hotel = Auth::user()->hotel;

        // Safety: if no hotel is assigned yet, show a holding page
        if (!$hotel) {
            return view('admin.no-hotel');
        }

        $hotel->load('rooms');

        $stats = [
            'total_rooms'       => $hotel->rooms->count(),
            'available_rooms'   => $hotel->rooms->where('status', 'available')->count(),
            'total_reservations'=> $hotel->reservations()->count(),
            'pending'           => $hotel->reservations()
                                         ->where('status', Reservation::STATUS_PENDING)
                                         ->count(),
            'confirmed'         => $hotel->reservations()
                                         ->where('status', Reservation::STATUS_CONFIRMED)
                                         ->count(),
            'revenue'           => $hotel->reservations()
                                         ->whereIn('status', [
                                             Reservation::STATUS_CONFIRMED,
                                             Reservation::STATUS_COMPLETED,
                                         ])
                                         ->with('room')
                                         ->get()
                                         ->sum('total_price'),
        ];

        $recentReservations = $hotel->reservations()
            ->with(['room', 'user'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('admin.index', compact('hotel', 'stats', 'recentReservations'));
    }
}