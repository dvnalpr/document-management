<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Guest Routes (Not Authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('documents', DocumentController::class);
Route::resource('users', UserController::class);

// Authenticated Routes
// Route::middleware('auth')->group(function () {
//     // Logout
//     Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//     // Dashboard Routes
//     Route::get('/dashboard', function () {
//         return view('dashboard.index');
//     })->name('dashboard');

//     Route::get('/admin/dashboard', function () {
//         return view('dashboard.admin');
//     })->name('admin.dashboard')->middleware('role:admin');

//     Route::get('/staff/dashboard', function () {
//         return view('dashboard.staff');
//     })->name('staff.dashboard')->middleware('role:qa_staff|engineering_staff');

//     // Document Routes (will be implemented later)
//     // Route::resource('documents', DocumentController::class);

//     // Loan Routes (will be implemented later)
//     // Route::resource('loans', DocumentLoanController::class);

//     // User Management Routes (will be implemented later)
//     // Route::resource('users', UserController::class)->middleware('role:admin');
// });

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});
