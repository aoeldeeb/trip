<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TripController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\AgentController;
use App\Http\Controllers\API\AdminController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    // Registration routes
    Route::post('/register/user', [AuthController::class, 'registerUser']);
    Route::post('/register/agent', [AuthController::class, 'registerAgent']);
    Route::post('/register/company', [AuthController::class, 'registerCompany']);
    
    // Login route
    Route::post('/login', [AuthController::class, 'login']);
    
    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});

/*
|--------------------------------------------------------------------------
| Public Trip Routes
|--------------------------------------------------------------------------
*/
Route::prefix('trips')->group(function () {
    Route::get('/', [TripController::class, 'index']); // List all trips with filters
    Route::get('/{id}', [TripController::class, 'show']); // Show trip details
});

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | Trip Management Routes (Company Only)
    |--------------------------------------------------------------------------
    */
    Route::prefix('trips')->group(function () {
        Route::post('/', [TripController::class, 'store']); // Create trip
        Route::put('/{id}', [TripController::class, 'update']); // Update trip
        Route::delete('/{id}', [TripController::class, 'destroy']); // Delete trip
        Route::get('/my/trips', [TripController::class, 'myTrips']); // Company's trips
    });
    
    /*
    |--------------------------------------------------------------------------
    | Booking Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('bookings')->group(function () {
        Route::post('/', [BookingController::class, 'store']); // Create booking
        Route::get('/user', [BookingController::class, 'userBookings']); // User's bookings
        Route::get('/company', [BookingController::class, 'companyBookings']); // Company's bookings
        Route::put('/{id}/status', [BookingController::class, 'updateStatus']); // Update booking status
        Route::put('/{id}/cancel', [BookingController::class, 'cancel']); // Cancel booking
        Route::get('/{id}', [BookingController::class, 'show']); // Show booking details
    });
    
    /*
    |--------------------------------------------------------------------------
    | Review Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('reviews')->group(function () {
        Route::post('/', [ReviewController::class, 'store']); // Add review
        Route::get('/my', [ReviewController::class, 'myReviews']); // User's reviews
        Route::put('/{id}', [ReviewController::class, 'update']); // Update review
        Route::delete('/{id}', [ReviewController::class, 'destroy']); // Delete review
    });
    
    /*
    |--------------------------------------------------------------------------
    | Agent Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('agents')->group(function () {
        Route::get('/dashboard', [AgentController::class, 'dashboard']); // Agent dashboard
        Route::get('/commissions', [AgentController::class, 'commissions']); // Commission history
        Route::get('/referrals', [AgentController::class, 'referrals']); // Referred users
        Route::post('/withdraw', [AgentController::class, 'requestWithdrawal']); // Request withdrawal
    });
    
    /*
    |--------------------------------------------------------------------------
    | Admin Routes (Admin only)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware('admin')->group(function () {
        // User management
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/users/{id}', [AdminController::class, 'showUser']);
        Route::put('/users/{id}/status', [AdminController::class, 'updateUserStatus']);
        
        // Company management
        Route::get('/companies', [AdminController::class, 'companies']);
        Route::put('/companies/{id}/approve', [AdminController::class, 'approveCompany']);
        Route::put('/companies/{id}/reject', [AdminController::class, 'rejectCompany']);
        
        // Agent management
        Route::get('/agents', [AdminController::class, 'agents']);
        Route::get('/agents/{id}', [AdminController::class, 'showAgent']);
        
        // Booking management
        Route::get('/bookings', [AdminController::class, 'bookings']);
        Route::get('/bookings/{id}', [AdminController::class, 'showBooking']);
        
        // Commission management
        Route::get('/commissions', [AdminController::class, 'commissions']);
        Route::put('/commissions/{id}/pay', [AdminController::class, 'payCommission']);
        Route::get('/commissions/pending', [AdminController::class, 'pendingCommissions']);
        
        // Reports
        Route::get('/reports/bookings', [AdminController::class, 'bookingReports']);
        Route::get('/reports/revenue', [AdminController::class, 'revenueReports']);
        Route::get('/reports/commissions', [AdminController::class, 'commissionReports']);
    });
});

/*
|--------------------------------------------------------------------------
| Public Review Routes
|--------------------------------------------------------------------------
*/
Route::prefix('reviews')->group(function () {
    Route::get('/trip/{trip_id}', [ReviewController::class, 'tripReviews']); // Show trip reviews
});
