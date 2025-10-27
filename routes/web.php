<?php

use App\Http\Controllers\PasswordRecoveryController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Ruta personalizada para registro
Route::post('register', [RegisterController::class, 'store'])
    ->name('register');

Route::get('password/request', [PasswordRecoveryController::class, 'show'])
    ->name('password.request');

Route::post('password/verify-email', [PasswordRecoveryController::class, 'verifyEmail'])
    ->name('password.verify-email');

Route::post('password/verify-answer', [PasswordRecoveryController::class, 'verifyAnswer'])
    ->name('password.verify-answer');

Route::post('password/update-new', [PasswordRecoveryController::class, 'updatePassword'])
    ->name('password.update-new');

Route::get('dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
