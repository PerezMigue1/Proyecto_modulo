<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            \Log::info('Google OAuth callback iniciado');
            
            $googleUser = Socialite::driver('google')->user();
            \Log::info('Google user obtenido:', ['email' => $googleUser->getEmail()]);
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                \Log::info('Creando nuevo usuario de Google');
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                    'google_id' => $googleUser->getId(),
                ]);
                \Log::info('Usuario creado:', ['id' => $user->id, 'email' => $user->email]);
            } else {
                \Log::info('Usuario existente encontrado:', ['id' => $user->id, 'email' => $user->email]);
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
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
            return redirect($frontendUrl . '/auth/callback?token=' . $token . '&provider=google');
        } catch (\Exception $e) {
            \Log::error('Error en Google OAuth callback:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $frontendUrl = env('FRONTEND_URL', 'https://modulo-usuario.netlify.app');
            return redirect($frontendUrl . '/login?error=' . urlencode('Error al autenticar con Google: ' . $e->getMessage()));
        }
    }
}

