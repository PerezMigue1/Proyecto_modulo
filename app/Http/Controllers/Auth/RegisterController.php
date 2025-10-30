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

        // Hacer login del usuario para que pueda ver la página de verificación
        Auth::login($user);

        // Redirigir a la página de verificación de correo
        // Laravel/Fortify automáticamente redirigirá aquí si el correo no está verificado
        return redirect()->route('verification.notice')->with('status', '¡Registro exitoso! Se ha enviado un enlace de verificación a tu correo electrónico.');
    }
}

