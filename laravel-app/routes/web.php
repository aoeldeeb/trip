<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['web', 'admin'])->group(function () {
    // Dashboard home
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User management
    Route::get('/users', [DashboardController::class, 'users'])->name('users');
    
    // Company management
    Route::get('/companies', [DashboardController::class, 'companies'])->name('companies');
    Route::post('/companies/{id}/approve', [DashboardController::class, 'approveCompany'])->name('companies.approve');
    Route::post('/companies/{id}/reject', [DashboardController::class, 'rejectCompany'])->name('companies.reject');
    
    // Agent management
    Route::get('/agents', [DashboardController::class, 'agents'])->name('agents');
    
    // Booking management
    Route::get('/bookings', [DashboardController::class, 'bookings'])->name('bookings');
    
    // Commission management
    Route::get('/commissions', [DashboardController::class, 'commissions'])->name('commissions');
    Route::post('/commissions/{id}/pay', [DashboardController::class, 'payCommission'])->name('commissions.pay');
    
    // Reports
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
});

/*
|--------------------------------------------------------------------------
| Public Admin Login (Simple for demo)
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::post('/admin/login', function () {
    // Simple admin login for demo - in production use proper authentication
    session(['admin_logged_in' => true]);
    return redirect()->route('admin.dashboard');
})->name('admin.login.post');
