<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\SavedOfferController;
use App\Http\Controllers\JobTypeController;
use App\Http\Controllers\OffreStatusController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\StudentController;




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [LoginController::class, 'login']);

Route::post('/signup', [SignupController::class, 'register']);

// Student routes
Route::middleware('auth:student')->group(function () {
    // Student Profile Routes
    Route::get('/student/profile', [StudentController::class, 'show']);
    Route::put('/student/profile', [StudentController::class, 'update']);

    // Other student routes
    Route::get('/saved-offers', [SavedOfferController::class, 'index']);
    Route::post('/saved-offers', [SavedOfferController::class, 'store']);
    Route::get('/saved-offers/{offerId}', [SavedOfferController::class, 'show']);
    Route::delete('/saved-offers/{savedOfferId}', [SavedOfferController::class, 'destroy']);
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::post('/applications', [ApplicationController::class, 'store']);
});

// Company routes
Route::middleware('auth:company')->group(function () {
    Route::get('/company/profile', function () {
        return auth('company')->user(); // Returns logged-in company
    });
    Route::get('/company/{id}', [CompanyController::class, 'show']);
    Route::put('/company/profile', [CompanyController::class, 'update']);
    Route::post('/Addoffers', [OfferController::class, 'store']);
    Route::put('/EditOffer/{offer}', [OfferController::class, 'update']);
    Route::delete('/deleteoffer/{offerId}', [OfferController::class, 'destroy']);
    Route::get('/offers/{offerId}', [OfferController::class, 'show']);
    Route::get('/profiles', [ProfileController::class, 'index']);
    Route::get('/profiles/random', [ProfileController::class, 'random']);
});


Route::middleware('auth:student')->group(function () {
    Route::get('/offers/{offerId}', [OfferController::class, 'show']);
});

Route::middleware('auth:company')->group(function () {
    Route::post('/Addoffers', [OfferController::class, 'store']);
    Route::put('/EditOffer/{offer}', [OfferController::class, 'update']);
    Route::delete('/deleteoffer/{offerId}', [OfferController::class, 'destroy']);
    Route::get('/offers/{offerId}', [OfferController::class, 'show']);
});


// Public routes
Route::get('/jobtypes', [JobTypeController::class, 'index']);
Route::get('/offrestatus', [OffreStatusController::class, 'index']);
Route::get('/job-listings', [JobListingController::class, 'index']);

// Protected routes (require authentication)
Route::middleware('auth:company,student')->group(function () {
    Route::get('/offers', [OfferController::class, 'index']);
});

// Saved Offers Routes
Route::middleware('auth:student')->group(function () {
    Route::post('/saved-offers', [SavedOfferController::class, 'store']);
});
