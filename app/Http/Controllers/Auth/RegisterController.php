<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request, CreateNewUser $creator)
    {
        $user = $creator->create($request->all());
        
        // IMPORTANTE: No hacer login automático, solo redirigir al login
        return redirect()->route('login')->with('status', '¡Registro exitoso! Por favor inicia sesión.');
    }
}

