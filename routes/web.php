<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentLoanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware(['guest'])->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('documents', DocumentController::class)->only(['index', 'store', 'show']);

    Route::get('/my-tokens', [DocumentLoanController::class, 'myTokens'])->name('loans.my-tokens');
    Route::post('/loans', [DocumentLoanController::class, 'store'])->name('loans.store');

    Route::group(['middleware' => ['role:Manager|Admin']], function () {

        Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        Route::get('/loans/manage', [DocumentLoanController::class, 'manageRequests'])->name('loans.manage');
        Route::patch('/loans/{id}/status', [DocumentLoanController::class, 'updateStatus']);
    });

    Route::group(['middleware' => ['role:Admin']], function () {

        Route::resource('users', UserController::class);

        Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export');
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });
});
