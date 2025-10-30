<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\RateLimiter;

class RegisterController extends Controller
{
    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request, CreateNewUser $creator)
    {
        // Rate limiting: máximo 3 registros por hora por IP
        $key = 'register:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()
                ->withInput($request->only('name', 'email'))
                ->withErrors(['email' => "Demasiados intentos de registro. Por favor intenta de nuevo en {$seconds} segundos."]);
        }

        RateLimiter::hit($key, 3600); // 1 hora

        $user = $creator->create($request->all());
        
        // Disparar el evento Registered que enviará el correo de verificación
        event(new Registered($user));

        // Guardar el email en la sesión para mostrar en la página de verificación
        $request->session()->put('registered_email', $user->email);

        // Redirigir a la página de verificación pendiente
        return redirect()->route('verification.pending')->with('status', '¡Registro exitoso! Se ha enviado un enlace de verificación a tu correo electrónico.');
    }
}

