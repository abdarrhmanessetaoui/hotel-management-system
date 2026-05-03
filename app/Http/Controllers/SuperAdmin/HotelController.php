<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Hotel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(): View
    {
        $hotels = Hotel::with('city')
            ->withCount(['rooms', 'reservations'])
            ->orderBy('name')
            ->get();

        return view('superadmin.hotels.index', compact('hotels'));
    }

    public function create(): View
    {
        $cities = City::orderBy('name')->get();
        return view('superadmin.hotels.create', compact('cities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'city_id'     => ['required', 'exists:cities,id'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location'    => ['nullable', 'string', 'max:255'],
            'rating'      => ['nullable', 'numeric', 'min:0', 'max:5'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('img/hotels'), $imageName);
            $validated['image'] = 'img/hotels/' . $imageName;
        }

        Hotel::create($validated);

        return redirect()->route('superadmin.hotels.index')
            ->with('message', 'L\'hôtel a été créé avec succès.');
    }

    public function edit(Hotel $hotel): View
    {
        $cities = City::orderBy('name')->get();
        return view('superadmin.hotels.edit', compact('hotel', 'cities'));
    }

    public function update(Request $request, Hotel $hotel): RedirectResponse
    {
        $validated = $request->validate([
            'city_id'     => ['required', 'exists:cities,id'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location'    => ['nullable', 'string', 'max:255'],
            'rating'      => ['nullable', 'numeric', 'min:0', 'max:5'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('img/hotels'), $imageName);
            $validated['image'] = 'img/hotels/' . $imageName;
        }

        $hotel->update($validated);

        return redirect()->route('superadmin.hotels.index')
            ->with('message', 'L\'hôtel a été mis à jour avec succès.');
    }

    public function destroy(Hotel $hotel): RedirectResponse
    {
        $hotel->delete();

        return redirect()->route('superadmin.hotels.index')
            ->with('message', 'L\'hôtel a été supprimé avec succès.');
    }
}
