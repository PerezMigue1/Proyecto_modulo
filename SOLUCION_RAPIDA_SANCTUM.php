<?php

/**
 * SOLUCIÓN RÁPIDA PARA EL ERROR DE SANCTUM CON MONGODB
 * 
 * Error: Laravel\Sanctum\NewAccessToken::__construct(): Argument #1 ($accessToken) 
 * must be of type Laravel\Sanctum\PersonalAccessToken, App\Models\PersonalAccessToken given
 * 
 * CAUSA: Tienes un modelo PersonalAccessToken personalizado que no es compatible
 * 
 * SOLUCIÓN: Elimina el modelo PersonalAccessToken personalizado y deja que Sanctum use su modelo por defecto
 */

// PASO 1: Elimina el archivo app/Models/PersonalAccessToken.php si existe
// PASO 2: Verifica que el modelo User tenga HasApiTokens correctamente
// PASO 3: Limpia la cache de Laravel

// ==========================================
// VERIFICACIÓN DEL MODELO USER
// ==========================================

// Asegúrate de que tu modelo User se vea así:

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'pregunta_secreta',
        'respuesta_secreta',
        'telefono',
        'google_id',
        'facebook_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

// ==========================================
// COMANDOS PARA EJECUTAR EN EL BACKEND
// ==========================================

// 1. Eliminar el modelo PersonalAccessToken personalizado (si existe):
//    rm app/Models/PersonalAccessToken.php

// 2. Limpiar la cache de Laravel:
//    php artisan config:clear
//    php artisan cache:clear
//    php artisan route:clear

// 3. Verificar que Sanctum esté instalado:
//    composer show laravel/sanctum

// 4. Si es necesario, reinstalar Sanctum:
//    composer remove laravel/sanctum
//    composer require laravel/sanctum
//    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

// ==========================================
// CONTROLADOR DE LOGIN CORRECTO
// ==========================================

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Las credenciales son incorrectas'
                ], 401);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Las credenciales son incorrectas'
                ], 401);
            }

            // Verificar que el método createToken existe
            if (!method_exists($user, 'createToken')) {
                Log::error('El modelo User no tiene el método createToken');
                return response()->json([
                    'message' => 'Error de configuración del servidor'
                ], 500);
            }

            // Crear el token - Esto debería funcionar ahora
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'user' => $user->makeHidden(['password', 'two_factor_secret', 'two_factor_recovery_codes']),
                'token' => $token,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error en login:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error interno del servidor',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}

