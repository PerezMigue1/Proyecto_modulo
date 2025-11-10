<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class FacebookAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            $user = User::where('email', $facebookUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $facebookUser->getName() ?: ($facebookUser->user["name"] ?? 'Usuario Facebook'),
                    'email' => $facebookUser->getEmail(),
                    'password' => bcrypt(Str::random(16)),
                    'facebook_id' => $facebookUser->getId(),
                    'email_verified_at' => now(),
                ]);
            } else if (!$user->facebook_id) {
                $user->facebook_id = $facebookUser->getId();
                $user->save();
            }

            // Crear token JWT
            try {
                $token = JWTAuth::fromUser($user);
            } catch (JWTException $e) {
                $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
                return redirect($frontendUrl . '/login?error=' . urlencode('Error al crear el token. Por favor, intenta de nuevo.'));
            }

            $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
            return redirect($frontendUrl . '/auth/callback?token=' . $token . '&provider=facebook');
        } catch (\Exception $e) {
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
            return redirect($frontendUrl . '/login?error=' . urlencode('Error al autenticar con Facebook. Intenta de nuevo.'));
        }
    }
}

