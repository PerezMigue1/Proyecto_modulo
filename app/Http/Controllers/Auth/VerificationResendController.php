<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class VerificationResendController extends Controller
{
    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No encontramos una cuenta con ese correo electrónico.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Tu correo ya ha sido verificado. Puedes iniciar sesión.');
        }

        // Rate limiting: máximo 1 correo cada 60 segundos
        $key = 'verification-resend:' . $request->email;
        
        if (RateLimiter::tooManyAttempts($key, 1)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['email' => "Por favor espera {$seconds} segundos antes de solicitar otro correo."]);
        }

        RateLimiter::hit($key, 60);

        // Reenviar el correo de verificación
        event(new Registered($user));

        return back()->with('status', 'Se ha reenviado el enlace de verificación a tu correo electrónico.');
    }
}

