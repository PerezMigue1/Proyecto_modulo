<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordRecoveryController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SecretQuestionController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/preguntas-secretas', [SecretQuestionController::class, 'index']);

// Password recovery routes
Route::post('/password/verify-email', [PasswordRecoveryController::class, 'verifyEmail']);
Route::post('/password/verify-answer', [PasswordRecoveryController::class, 'verifyAnswer']);
Route::post('/password/update', [PasswordRecoveryController::class, 'updatePassword']);

// Rutas para autenticación con Google (OAuth - no requieren JWT)
Route::get('auth/google', [\App\Http\Controllers\Api\GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('auth/google/callback', [\App\Http\Controllers\Api\GoogleAuthController::class, 'callback'])->name('google.callback');

// Rutas para autenticación con Facebook (OAuth - no requieren JWT)
Route::get('auth/facebook', [\App\Http\Controllers\Api\FacebookAuthController::class, 'redirect'])->name('facebook.login');
Route::get('auth/facebook/callback', [\App\Http\Controllers\Api\FacebookAuthController::class, 'callback'])->name('facebook.callback');

// Protected routes (require JWT authentication)
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

