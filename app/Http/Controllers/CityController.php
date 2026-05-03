<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Contracts\View\View;

class CityController extends Controller
{
    /**
     * Show all hotels belonging to a given city.
     * Route: GET /cities/{city}/hotels
     */
    public function hotels(City $city): View
    {
        $hotels = $city->hotels()
            ->withCount('rooms')
            ->orderByDesc('rating')
            ->get();

        return view('pages.hotels', compact('city', 'hotels'));
    }
}
