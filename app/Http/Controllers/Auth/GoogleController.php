<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Buscar si el usuario ya existe
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Crear nuevo usuario
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(str()->random(16)), // Contraseña aleatoria
                    'email_verified_at' => now(),
                    'google_id' => $googleUser->getId(),
                ]);
            } else {
                // Actualizar el google_id si no existe
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }
            }

            // Autenticar al usuario
            Auth::login($user, true);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Error al autenticar con Google. Por favor, intenta de nuevo.']);
        }
    }
}


