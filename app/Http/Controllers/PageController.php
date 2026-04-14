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
}