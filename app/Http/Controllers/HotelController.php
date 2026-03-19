<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Contracts\View\View;

class HotelController extends Controller
{
    /**
     * Show hotel detail page with available rooms.
     * Route: GET /hotels/{hotel}
     */
    public function show(Hotel $hotel): View
    {
        // Eager-load city for breadcrumbs and available rooms only
        $hotel->load('city');

        $rooms = $hotel->rooms()
            ->where('status', 'available')
            ->get();

        return view('pages.hotel-detail', compact('hotel', 'rooms'));
    }
}