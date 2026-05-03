<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomTypeController extends Controller
{
    /**
     * Get the hotel assigned to the current admin.
     */
    private function getAdminHotel()
    {
        $hotel = Auth::user()->hotel;
        abort_if(!$hotel, 403, 'No hotel assigned to your account.');
        return $hotel;
    }

    public function index(): View
    {
        $hotel = $this->getAdminHotel();
        $roomTypes = $hotel->roomTypes()->orderBy('name')->get();

        return view('admin.roomtypes.index', compact('hotel', 'roomTypes'));
    }

    public function create(): View
    {
        $hotel = $this->getAdminHotel();
        return view('admin.roomtypes.create', compact('hotel'));
    }

    public function store(Request $request): RedirectResponse
    {
        $hotel = $this->getAdminHotel();

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $hotel->roomTypes()->create($validated);

        return redirect()->route('admin.roomtypes.index')
            ->with('message', 'Type de chambre créé avec succès.');
    }

    public function edit(RoomType $roomtype): View
    {
        $hotel = $this->getAdminHotel();
        abort_if($roomtype->hotel_id !== $hotel->id, 403);

        return view('admin.roomtypes.edit', compact('hotel', 'roomtype'));
    }

    public function update(Request $request, RoomType $roomtype): RedirectResponse
    {
        $hotel = $this->getAdminHotel();
        abort_if($roomtype->hotel_id !== $hotel->id, 403);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $roomtype->update($validated);

        return redirect()->route('admin.roomtypes.index')
            ->with('message', 'Type de chambre mis à jour avec succès.');
    }

    public function destroy(RoomType $roomtype): RedirectResponse
    {
        $hotel = $this->getAdminHotel();
        abort_if($roomtype->hotel_id !== $hotel->id, 403);

        $roomtype->delete();

        return redirect()->route('admin.roomtypes.index')
            ->with('message', 'Type de chambre supprimé avec succès.');
    }
}

