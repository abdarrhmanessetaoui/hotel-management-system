<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    /**
     * Homepage — display all cities for the client to choose from.
     * Flow entry point: City → Hotels → Hotel Detail → Reserve
     */
    public function index(): View
    {
        $cities = City::withCount('hotels')->get();

        return view('pages.home', compact('cities'));
    }

    /**
     * Show authenticated user's profile.
     */
    public function showProfile(): View
    {
        return view('pages.profile', ['user' => Auth::user()]);
    }

    /**
     * Update authenticated user's profile details.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:20'],
        ]);

        $user            = Auth::user();
        $user->name      = $request->name;
        $user->last_name = $request->last_name;
        $user->phone     = $request->phone;
        $user->save();

        return redirect()->route('profile')
            ->with('message', 'Profile updated successfully.');
    }
}