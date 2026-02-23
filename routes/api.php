<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventMediaController;
use App\Http\Controllers\EventTicketController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\VenueSeatController;
use App\Http\Controllers\OrganizerController;

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

// Public event routes
Route::prefix('events')->group(function () {
    Route::get('/featured', [EventController::class, 'featuredEvents']);
    Route::get('/categories', [EventController::class, 'categories']);
    Route::get('/venues', [EventController::class, 'venues']);
    Route::get('/', [EventController::class, 'index']);
    Route::get('/{id}', [EventController::class, 'show']);
});

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {

    // Organizer event management
    Route::prefix('events')->group(function () {
        Route::get('/my/events', [EventController::class, 'myEvents']);
        Route::post('/', [EventController::class, 'store']);
        Route::put('/{id}', [EventController::class, 'update']);
        Route::patch('/{id}/status', [EventController::class, 'updateStatus']);
        Route::delete('/{id}', [EventController::class, 'destroy']);
        // Route::get('/{id}/statistics', [EventController::class, 'statistics']);
    });

    // Event media
    Route::prefix('events/{eventId}/media')->group(function () {
        Route::get('/', [EventMediaController::class, 'index']);
        Route::post('/', [EventMediaController::class, 'store']);
        Route::get('/{mediaId}', [EventMediaController::class, 'show']);
        Route::delete('/{mediaId}', [EventMediaController::class, 'destroy']);
    });

    // Bookings
    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingController::class, 'index']);
        Route::post('/', [BookingController::class, 'store']);
        Route::get('/{id}', [BookingController::class, 'show']);
        Route::put('/{id}', [BookingController::class, 'update']);
        Route::delete('/{id}', [BookingController::class, 'destroy']);
    });

    // Event tickets
    Route::prefix('tickets')->group(function () {
        Route::get('/', [EventTicketController::class, 'index']);
        Route::post('/', [EventTicketController::class, 'store']);
        Route::get('/{id}', [EventTicketController::class, 'show']);
        Route::put('/{id}', [EventTicketController::class, 'update']);
        Route::delete('/{id}', [EventTicketController::class, 'destroy']);
    });

    // Venues
    Route::prefix('venues')->group(function () {
        Route::get('/', [VenueController::class, 'index']);
        Route::post('/', [VenueController::class, 'store']);
        Route::get('/{venue}', [VenueController::class, 'show']);
        Route::put('/{venue}', [VenueController::class, 'update']);
        Route::delete('/{venue}', [VenueController::class, 'destroy']);
        // Route::patch('/{venue}/activate', [VenueController::class, 'activate']);
        // Route::patch('/{venue}/deactivate', [VenueController::class, 'deactivate']);

        // Venue seats (nested under venues)
        Route::prefix('/{venueId}/seats')->group(function () {
            Route::get('/', [VenueSeatController::class, 'index']);
            Route::post('/', [VenueSeatController::class, 'store']);
            Route::get('/{seatId}', [VenueSeatController::class, 'show']);
            Route::put('/{seatId}', [VenueSeatController::class, 'update']);
            Route::delete('/{seatId}', [VenueSeatController::class, 'destroy']);
        });
    });

    // Organizers
    Route::prefix('organizers')->group(function () {
        Route::get('/', [OrganizerController::class, 'index']);
        Route::post('/', [OrganizerController::class, 'store']);
        // Route::get('/pending', [OrganizerController::class, 'pending']);
        Route::get('/my-profile', [OrganizerController::class, 'myProfile']);
        Route::get('/{organizer}', [OrganizerController::class, 'show']);
        Route::put('/{organizer}', [OrganizerController::class, 'update']);
        Route::delete('/{organizer}', [OrganizerController::class, 'destroy']);
        // Route::patch('/{organizer}/approve', [OrganizerController::class, 'approve']);
        // Route::patch('/{organizer}/reject', [OrganizerController::class, 'reject']);
        // Route::patch('/{organizer}/suspend', [OrganizerController::class, 'suspend']);
    });
});
