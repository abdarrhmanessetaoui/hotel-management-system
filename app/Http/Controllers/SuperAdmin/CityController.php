<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(): View
    {
        $cities = City::withCount('hotels')->orderBy('name')->get();
        return view('superadmin.cities.index', compact('cities'));
    }

    public function create(): View
    {
        return view('superadmin.cities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255', 'unique:cities,name'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('img/cities'), $imageName);
            $validated['image'] = 'img/cities/' . $imageName;
        }

        City::create($validated);

        return redirect()->route('superadmin.cities.index')
            ->with('message', 'La ville a été créée avec succès.');
    }

    public function edit(City $city): View
    {
        return view('superadmin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255', 'unique:cities,name,' . $city->id],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('img/cities'), $imageName);
            $validated['image'] = 'img/cities/' . $imageName;
        }

        $city->update($validated);

        return redirect()->route('superadmin.cities.index')
            ->with('message', 'La ville a été mise à jour avec succès.');
    }

    public function destroy(City $city): RedirectResponse
    {
        $city->delete();

        return redirect()->route('superadmin.cities.index')
            ->with('message', 'La ville a été supprimée avec succès.');
    }
}