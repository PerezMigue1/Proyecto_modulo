<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request)
    {
        $userId = $request->route('id');
        $hash = $request->route('hash');
        
        // MongoDB usa _id como primary key
        $user = \App\Models\User::where('_id', $userId)->first();

        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }

        // Verificar el hash del correo
        if (!hash_equals((string) $hash, sha1($user->email))) {
            abort(403, 'Enlace de verificación inválido');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Tu correo ya ha sido verificado. Puedes iniciar sesión.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Limpiar la sesión de verificación pendiente
        $request->session()->forget('registered_email');

        // Redirigir al login después de verificar (sin autenticar)
        return redirect()->route('login')->with('status', '¡Correo verificado exitosamente! Ya puedes iniciar sesión.');
    }
}

