<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatbotApiController;

/*
|--------------------------------------------------------------------------
| Chatbot Data APIs (Production SaaS Layer)
|--------------------------------------------------------------------------
*/
Route::prefix('chatbot-data')->group(function () {
    Route::get('/cities', [ChatbotApiController::class, 'cities']);
    Route::get('/hotels', [ChatbotApiController::class, 'hotels']);
    Route::get('/rooms', [ChatbotApiController::class, 'rooms']);
    Route::get('/bookings', [ChatbotApiController::class, 'bookings']);
});
