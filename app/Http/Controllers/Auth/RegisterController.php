<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

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

        // Guardar el email en la sesión para mostrar en la página de verificación
        $request->session()->put('registered_email', $user->email);

        // Redirigir a la página de verificación pendiente
        return redirect()->route('verification.pending')->with('status', '¡Registro exitoso! Se ha enviado un enlace de verificación a tu correo electrónico.');
    }
}

