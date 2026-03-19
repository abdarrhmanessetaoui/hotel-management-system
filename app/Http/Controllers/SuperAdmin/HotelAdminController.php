<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HotelAdminController extends Controller
{
    public function index(): View
    {
        $hotels = Hotel::with('admin')->orderBy('name')->get();
        return view('superadmin.hotel-admins.index', compact('hotels'));
    }

    /**
     * Show form to assign an admin to a hotel.
     */
    public function create(Hotel $hotel): View
    {
        // Only show users who are admins and don't already manage a hotel
        $availableAdmins = User::where('role', User::ROLE_ADMIN)
            ->whereDoesntHave('hotel')
            ->orderBy('name')
            ->get();

        return view('superadmin.hotel-admins.create', compact('hotel', 'availableAdmins'));
    }

    /**
     * Assign an admin user to a hotel.
     */
    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        $validated = $request->validate([
            'admin_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($validated['admin_id']);

        // Ensure selected user has admin role
        if (!$user->isAdmin()) {
            return back()->withErrors(['admin_id' => 'Selected user is not an admin.']);
        }

        $hotel->update(['admin_id' => $user->id]);

        return redirect()->route('superadmin.hotel-admins.index')
            ->with('message', "{$user->name} assigned to {$hotel->name} successfully.");
    }

    /**
     * Remove admin from a hotel.
     */
    public function destroy(Hotel $hotel, User $user): RedirectResponse
    {
        // Only unassign if this user is actually the hotel's admin
        if ($hotel->admin_id === $user->id) {
            $hotel->update(['admin_id' => null]);
        }

        return redirect()->route('superadmin.hotel-admins.index')
            ->with('message', 'Admin removed from hotel.');
    }
}