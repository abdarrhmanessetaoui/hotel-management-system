<?php

use Illuminate\Support\Facades\Route;

// ─── Public Controllers ──────────────────────────────────────────────────────
use App\Http\Controllers\PageController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Auth\AuthController;

// ─── Hotel Admin Controllers ─────────────────────────────────────────────────
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;

// ─── Super Admin Controllers ─────────────────────────────────────────────────
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\CityController as SuperAdminCityController;
use App\Http\Controllers\SuperAdmin\HotelController as SuperAdminHotelController;
use App\Http\Controllers\SuperAdmin\HotelAdminController as SuperAdminHotelAdminController;
use App\Http\Controllers\SuperAdmin\ReservationController as SuperAdminReservationController;

/* |-------------------------------------------------------------------------- | PUBLIC ROUTES | Flow: Home (Cities) → Hotels in City → Hotel Detail → Reserve |-------------------------------------------------------------------------- */

Route::get('/', [PageController::class , 'index'])->name('home');

Route::get('/cities/{city}/hotels', [CityController::class , 'hotels'])->name('cities.hotels');

Route::get('/hotels/{hotel}', [HotelController::class , 'show'])->name('hotels.show');

// Newsletter subscription (public — no login required)
Route::post('/newsletter', [NewsletterController::class , 'store'])->name('newsletter.store');

/* |-------------------------------------------------------------------------- | AUTH ROUTES |-------------------------------------------------------------------------- */
Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'showRegistrationForm')->name('register');
    Route::post('register', 'register');

    Route::get('login', 'showLoginForm')->name('login');
    Route::post('login', 'login');

    Route::post('logout', 'logout')->name('logout');
});

/* |-------------------------------------------------------------------------- | AUTHENTICATED CLIENT ROUTES |-------------------------------------------------------------------------- */
Route::middleware('auth')->group(function () {

    // Profile (Unified)
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Reservations
    Route::get('/hotels/{hotel}/reserve', [ReservationController::class , 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class , 'store'])->name('reservations.store');
    Route::get('/reservations', [ReservationController::class , 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class , 'show'])->name('reservations.show');
    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class , 'cancel'])->name('reservations.cancel');
});

/* |-------------------------------------------------------------------------- | HOTEL ADMIN ROUTES  [ middleware: auth + role:admin ] |-------------------------------------------------------------------------- */
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/', [AdminDashboard::class , 'index'])->name('index');

        Route::resource('rooms', AdminRoomController::class)->except('show');

        Route::resource('reservations', AdminReservationController::class)
            ->only(['index', 'show', 'update']);

        // Pointing to unified profile
        Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    });

/* |-------------------------------------------------------------------------- | SUPER ADMIN ROUTES  [ middleware: auth + role:superadmin ] |-------------------------------------------------------------------------- */
Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', 'role:superadmin'])
    ->group(function () {

        Route::get('/', [SuperAdminDashboard::class , 'index'])->name('index');

        Route::resource('cities', SuperAdminCityController::class)->except('show');

        Route::resource('hotels', SuperAdminHotelController::class)->except('show');

        Route::resource('users', \App\Http\Controllers\SuperAdmin\UserController::class);
        Route::patch('users/{user}/assign-hotel', [\App\Http\Controllers\SuperAdmin\UserController::class, 'assignHotel'])->name('users.assign-hotel');
        Route::patch('users/{user}/toggle-status', [\App\Http\Controllers\SuperAdmin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::patch('users/{user}/role', [\App\Http\Controllers\SuperAdmin\UserController::class, 'updateRole'])->name('users.update-role');

        /*
        Route::resource('reservations', SuperAdminReservationController::class)
            ->only(['index', 'show', 'update', 'destroy']);
        */

        Route::resource('chatbot-suggestions', \App\Http\Controllers\SuperAdmin\ChatbotSuggestionController::class);


        Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

        Route::get('/testing', function () {
            return view('pages.testing-checklist');
        }
        )->name('testing');
    });

// ─── Chatbot API (Web Native) ───────────────────────────────────────────────
Route::prefix('api/chatbot')->group(function() {
    Route::post('/init', [App\Http\Controllers\ChatbotController::class, 'startSession'])->name('chatbot.init');
    Route::post('/send', [App\Http\Controllers\ChatbotController::class, 'sendMessage'])->name('chatbot.send');
    Route::post('/rate', [App\Http\Controllers\ChatbotController::class, 'rateSession'])->name('chatbot.rate');
});