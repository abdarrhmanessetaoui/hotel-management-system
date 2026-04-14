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
        $totalUsers = User::count();
        $assignedUserIds = Hotel::whereNotNull('admin_id')->pluck('admin_id')->unique();
        $assignedCount = User::whereIn('id', $assignedUserIds)->count();

        $stats = [
            'total_cities'   => City::count(),
            'total_hotels'   => Hotel::count(),
            'total_users'    => $totalUsers,
            'assigned'       => $assignedCount,
            'unassigned'     => $totalUsers - $assignedCount,
        ];

        // Fetch recent users instead of reservations
        $recentUsers = User::orderByDesc('created_at')->take(5)->get();

        // Fetch cities with most hotels for insight
        $topCities = City::withCount('hotels')->orderByDesc('hotels_count')->take(5)->get();

        return view('superadmin.index', compact('stats', 'recentUsers', 'topCities'));
    }
}