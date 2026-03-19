<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_cities'       => City::count(),
            'total_hotels'       => Hotel::count(),
            'total_clients'      => User::where('role', User::ROLE_CLIENT)->count(),
            'total_reservations' => Reservation::count(),
            'pending'            => Reservation::where('status', Reservation::STATUS_PENDING)->count(),
            'confirmed'          => Reservation::where('status', Reservation::STATUS_CONFIRMED)->count(),
        ];

        $recentReservations = Reservation::with(['hotel', 'room', 'user'])
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $topHotels = Hotel::withCount('reservations')
            ->orderByDesc('reservations_count')
            ->take(5)
            ->get();

        return view('superadmin.index', compact('stats', 'recentReservations', 'topHotels'));
    }
}