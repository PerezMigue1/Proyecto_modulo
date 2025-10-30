<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request, CreateNewUser $creator)
    {
        $user = $creator->create($request->all());
        
        // Disparar el evento Registered que enviará el correo de verificación
        event(new Registered($user));

        // NO hacer login automático - redirigir al login con mensaje
        return redirect()->route('login')->with('status', '¡Registro exitoso! Se ha enviado un enlace de verificación a tu correo electrónico. Por favor, verifica tu correo antes de iniciar sesión.');
    }
}

