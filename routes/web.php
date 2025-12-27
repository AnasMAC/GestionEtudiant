<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForgotPasswordController;

// 1. Authentication Routes (Public)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// 2. Protected Routes (Must be logged in)
Route::middleware(['auth'])->group(function () {

    // Resource route handles all CRUD (index, create, store, show, edit, update, destroy)
    Route::resource('students', StudentController::class);

    // Redirect root to students page
    Route::get('/', function () {
        return redirect('/students');
    });
});
