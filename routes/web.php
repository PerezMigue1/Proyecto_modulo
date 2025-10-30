<?php

use App\Http\Controllers\PasswordRecoveryController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FacebookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Ruta de login
Route::get('login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

// La ruta POST de login la maneja Fortify automáticamente
// No necesitamos definirla manualmente

// Ruta de logout
Route::post('logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');

// Rutas de registro
Route::get('register', function () {
    // Obtener las preguntas secretas desde MongoDB
    try {
        $client = new \MongoDB\Client(env('MONGODB_URI'));
        $database = $client->selectDatabase('equipo');
        $collection = $database->selectCollection('recuperar-password');
        
        $cursor = $collection->find();
        $preguntas = [];
        foreach ($cursor as $document) {
            $preguntas[] = iterator_to_array($document);
        }
        
        return view('auth.register', ['preguntas' => $preguntas]);
    } catch (\Exception $e) {
        return view('auth.register', ['preguntas' => []]);
    }
})->name('register');
Route::post('register', [RegisterController::class, 'store']);

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
})->middleware(['auth'])->name('dashboard');

// Rutas para autenticación con Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Rutas para autenticación con Facebook
Route::get('auth/facebook', [FacebookController::class, 'redirect'])->name('facebook.login');
Route::get('auth/facebook/callback', [FacebookController::class, 'callback'])->name('facebook.callback');

// Página de Políticas de Privacidad
Route::get('privacy', fn() => view('privacy'))->name('privacy');

// Página de Eliminación de Datos
Route::get('delete-data', fn() => view('delete-data'))->name('delete-data');

require __DIR__.'/settings.php';
