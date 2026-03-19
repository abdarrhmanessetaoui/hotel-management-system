<?php

use Illuminate\Support\Facades\Route;

// ─── Public Controllers ──────────────────────────────────────────────────────
use App\Http\Controllers\PageController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Auth\AuthController;

// ─── Hotel Admin Controllers ─────────────────────────────────────────────────
use App\Http\Controllers\Admin\DashboardController   as AdminDashboard;
use App\Http\Controllers\Admin\RoomController        as AdminRoomController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;

// ─── Super Admin Controllers ─────────────────────────────────────────────────
use App\Http\Controllers\SuperAdmin\DashboardController      as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\CityController           as SuperAdminCityController;
use App\Http\Controllers\SuperAdmin\HotelController          as SuperAdminHotelController;
use App\Http\Controllers\SuperAdmin\HotelAdminController     as SuperAdminHotelAdminController;
use App\Http\Controllers\SuperAdmin\ReservationController    as SuperAdminReservationController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
| Flow: Home (Cities) → Hotels in City → Hotel Detail → Reserve
|--------------------------------------------------------------------------
*/

// Homepage — displays all cities
Route::get('/', [PageController::class, 'index'])->name('home');

// City → Hotels
Route::get('/cities/{city}/hotels', [CityController::class, 'hotels'])->name('cities.hotels');

// Hotel detail page
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::controller(AuthController::class)->group(function () {
    Route::get('register',  'showRegistrationForm')->name('register');
    Route::post('register', 'register');

    Route::get('login',     'showLoginForm')->name('login');
    Route::post('login',    'login');

    Route::post('logout',   'logout')->name('logout');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED CLIENT ROUTES
| Requires login. Handles profile + reservations.
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile',  [PageController::class, 'showProfile'])->name('profile');
    Route::put('/profile',  [PageController::class, 'updateProfile'])->name('profile.update');

    // Reservations — client makes and views their own reservations
    Route::get('/hotels/{hotel}/reserve',   [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations',            [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations',             [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
});

/*
|--------------------------------------------------------------------------
| HOTEL ADMIN ROUTES  [ middleware: auth + role:admin ]
| Each admin manages ONE hotel only.
| Dashboard shows: hotel info, rooms, reservations, statistics
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // Dashboard — hotel stats & overview
        Route::get('/', [AdminDashboard::class, 'index'])->name('index');

        // Rooms — CRUD for rooms belonging to admin's hotel
        Route::resource('rooms', AdminRoomController::class)->except('show');

        // Reservations — view & manage reservations for admin's hotel
        Route::resource('reservations', AdminReservationController::class)
            ->only(['index', 'show', 'update']);
});

/*
|--------------------------------------------------------------------------
| SUPER ADMIN ROUTES  [ middleware: auth + role:superadmin ]
| Platform owner — full control over everything
|--------------------------------------------------------------------------
*/
Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', 'role:superadmin'])
    ->group(function () {

        // Dashboard — platform-wide statistics
        Route::get('/', [SuperAdminDashboard::class, 'index'])->name('index');

        // Cities — full CRUD
        Route::resource('cities', SuperAdminCityController::class)->except('show');

        // Hotels — full CRUD
        Route::resource('hotels', SuperAdminHotelController::class)->except('show');

        // Assign hotel admins to hotels
        Route::get('hotel-admins',                          [SuperAdminHotelAdminController::class, 'index'])->name('hotel-admins.index');
        Route::get('hotels/{hotel}/assign-admin',           [SuperAdminHotelAdminController::class, 'create'])->name('hotel-admins.create');
        Route::post('hotels/{hotel}/assign-admin',          [SuperAdminHotelAdminController::class, 'store'])->name('hotel-admins.store');
        Route::delete('hotels/{hotel}/remove-admin/{user}', [SuperAdminHotelAdminController::class, 'destroy'])->name('hotel-admins.destroy');

        // All reservations across all hotels
        Route::resource('reservations', SuperAdminReservationController::class)
            ->only(['index', 'show', 'update', 'destroy']);
});