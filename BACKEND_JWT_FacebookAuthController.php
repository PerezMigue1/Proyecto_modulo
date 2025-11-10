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
            \Log::info('Facebook OAuth callback iniciado');
            
            $facebookUser = Socialite::driver('facebook')->user();
            \Log::info('Facebook user obtenido:', ['email' => $facebookUser->getEmail()]);
            
            $user = User::where('email', $facebookUser->getEmail())->first();

            if (!$user) {
                \Log::info('Creando nuevo usuario de Facebook');
                $user = User::create([
                    'name' => $facebookUser->getName() ?: ($facebookUser->user["name"] ?? 'Usuario Facebook'),
                    'email' => $facebookUser->getEmail(),
                    'password' => bcrypt(Str::random(16)),
                    'facebook_id' => $facebookUser->getId(),
                    'email_verified_at' => now(),
                ]);
                \Log::info('Usuario creado:', ['id' => $user->id, 'email' => $user->email]);
            } else {
                \Log::info('Usuario existente encontrado:', ['id' => $user->id, 'email' => $user->email]);
                if (!$user->facebook_id) {
                    $user->facebook_id = $facebookUser->getId();
                    $user->save();
                }
            }

            // Verificar que JWT_SECRET está configurado
            if (!config('jwt.secret')) {
                \Log::error('JWT_SECRET no está configurado');
                $frontendUrl = env('FRONTEND_URL', 'https://modulo-usuario.netlify.app');
                return redirect($frontendUrl . '/login?error=' . urlencode('Error de configuración del servidor. Contacta al administrador.'));
            }

            // Crear token JWT
            try {
                \Log::info('Intentando crear token JWT para usuario:', ['id' => $user->id]);
                $token = JWTAuth::fromUser($user);
                \Log::info('Token JWT creado exitosamente');
            } catch (JWTException $e) {
                \Log::error('Error al crear token JWT:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                $frontendUrl = env('FRONTEND_URL', 'https://modulo-usuario.netlify.app');
                return redirect($frontendUrl . '/login?error=' . urlencode('Error al crear el token. Por favor, intenta de nuevo.'));
            } catch (\Exception $e) {
                \Log::error('Error inesperado al crear token:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                $frontendUrl = env('FRONTEND_URL', 'https://modulo-usuario.netlify.app');
                return redirect($frontendUrl . '/login?error=' . urlencode('Error al crear el token. Por favor, intenta de nuevo.'));
            }

            $frontendUrl = env('FRONTEND_URL', 'https://modulo-usuario.netlify.app');
            \Log::info('Redirigiendo al frontend con token', ['frontendUrl' => $frontendUrl]);
            return redirect($frontendUrl . '/auth/callback?token=' . $token . '&provider=facebook');
        } catch (\Exception $e) {
            \Log::error('Error en Facebook OAuth callback:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $frontendUrl = env('FRONTEND_URL', 'https://modulo-usuario.netlify.app');
            return redirect($frontendUrl . '/login?error=' . urlencode('Error al autenticar con Facebook: ' . $e->getMessage()));
        }
    }
}

