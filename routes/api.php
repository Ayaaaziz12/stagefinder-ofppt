<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\SavedOfferController;




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [LoginController::class, 'login']);

Route::post('/signup', [SignupController::class, 'register']);

// Student routes
Route::middleware('auth:student')->group(function () {
    Route::get('/student/profile', function () {
        return auth('student')->user(); // Returns logged-in student
    });
});

// Company routes
Route::middleware('auth:company')->group(function () {
    Route::get('/company/profile', function () {
        return auth('company')->user(); // Returns logged-in company
    });
});


Route::middleware('auth:student')->group(function () {
    Route::get('/offers/{offerId}', [OfferController::class, 'show']);
});

Route::middleware('auth:company')->group(function () {
    Route::post('/offers', [OfferController::class, 'store']);
    Route::put('/offers/{offer}', [OfferController::class, 'update']);
    Route::delete('/offers/{offerId}', [OfferController::class, 'destroy']);
    Route::get('/offers/{offerId}', [OfferController::class, 'show']);
});


// Student-protected routes
Route::middleware('auth:student')->group(function () {
    // Saved Offers
    Route::get('/saved-offers', [SavedOfferController::class, 'index']);
    Route::post('/saved-offers', [SavedOfferController::class, 'store']);
    Route::get('/saved-offers/{offerId}', [SavedOfferController::class, 'show']);
    Route::delete('/saved-offers/{savedOfferId}', [SavedOfferController::class, 'destroy']);
});



// Student applications
Route::middleware('auth:student')->group(function () {
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::post('/applications', [ApplicationController::class, 'store']);
    // Route::delete('/applications/{application}', [ApplicationController::class, 'destroy']);
});
