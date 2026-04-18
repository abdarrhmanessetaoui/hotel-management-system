<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Get the hotel assigned to the current admin.
     * Aborts with 403 if no hotel is assigned.
     */
    private function getAdminHotel()
    {
        $hotel = Auth::user()->hotel;
        abort_if(!$hotel, 403, 'Aucun hôtel n\'est assigné à votre compte.');
        return $hotel;
    }

    public function index(): View
    {
        $hotel = $this->getAdminHotel();
        $rooms = $hotel->rooms()->orderBy('room_number')->get();

        return view('admin.rooms.index', compact('hotel', 'rooms'));
    }

    public function create(): View
    {
        $hotel = $this->getAdminHotel();
        $roomTypes = $hotel->roomTypes;
        return view('admin.rooms.create', compact('hotel', 'roomTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $hotel = $this->getAdminHotel();

        $validated = $request->validate([
            'room_number'  => ['required', 'string', 'max:10'],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'price'        => ['required', 'numeric', 'min:0'],
            'description'  => ['nullable', 'string'],
            'image'        => ['nullable', 'image', 'max:2048'],
            'status'       => ['required', 'in:available,unavailable'],
        ]);

        // Security: Ensure the room_type_id belongs to the admin's hotel
        $type = $hotel->roomTypes()->find($validated['room_type_id']);
        abort_if(!$type, 403, 'Type de chambre invalide pour cet hôtel.');

        // Ensure room number is unique within this hotel
        $exists = $hotel->rooms()->where('room_number', $validated['room_number'])->exists();
        if ($exists) {
            return back()->withInput()
                ->withErrors(['room_number' => 'Ce numéro de chambre existe déjà dans cet hôtel.']);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('img/rooms'), $imageName);
            $imagePath = 'img/rooms/' . $imageName;
        }

        $hotel->rooms()->create([
            'room_number'  => $validated['room_number'],
            'room_type_id' => $validated['room_type_id'],
            'price'        => $validated['price'],
            'description'  => $validated['description'],
            'image'        => $imagePath,
            'status'       => $validated['status'],
        ]);

        return redirect()->route('admin.rooms.index')
            ->with('message', 'Chambre créée avec succès.');
    }

    public function edit(Room $room): View
    {
        $hotel = $this->getAdminHotel();
        abort_if($room->hotel_id !== $hotel->id, 403);

        $roomTypes = $hotel->roomTypes;
        return view('admin.rooms.edit', compact('hotel', 'room', 'roomTypes'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $hotel = $this->getAdminHotel();
        abort_if($room->hotel_id !== $hotel->id, 403);

        $validated = $request->validate([
            'room_number'  => ['required', 'string', 'max:10'],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'price'        => ['required', 'numeric', 'min:0'],
            'description'  => ['nullable', 'string'],
            'image'        => ['nullable', 'image', 'max:2048'],
            'status'       => ['required', 'in:available,unavailable'],
        ]);

        // Security check
        $type = $hotel->roomTypes()->find($validated['room_type_id']);
        abort_if(!$type, 403);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('img/rooms'), $imageName);
            $validated['image'] = 'img/rooms/' . $imageName;
        }

        $room->update($validated);

        return redirect()->route('admin.rooms.index')
            ->with('message', 'Chambre mise à jour avec succès.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $hotel = $this->getAdminHotel();
        abort_if($room->hotel_id !== $hotel->id, 403);

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('message', 'Chambre supprimée avec succès.');
    }
}