<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Iniciar sesión y obtener token JWT
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $credentials = $request->only('email', 'password');
            $user = User::where('email', $credentials['email'])->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Las credenciales son incorrectas'
                ], 401);
            }

            if (!Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'message' => 'Las credenciales son incorrectas'
                ], 401);
            }

            // Crear token JWT
            if (!$token = JWTAuth::fromUser($user)) {
                return response()->json([
                    'message' => 'No se pudo crear el token'
                ], 500);
            }

            return response()->json([
                'user' => $user->makeHidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'pregunta_secreta']),
                'token' => $token,
                'token_type' => 'bearer',
            ], 200);

        } catch (JWTException $e) {
            return response()->json([
                'message' => 'No se pudo crear el token',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Obtener el usuario autenticado
     */
    public function user(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
            if (!$user) {
                return response()->json([
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            return response()->json(
                $user->makeHidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'pregunta_secreta'])
            );
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Token inválido o expirado'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el usuario',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Cerrar sesión (invalidar token)
     */
    public function logout(Request $request)
    {
        try {
            JWTAuth::parseToken()->invalidate();
            
            return response()->json([
                'message' => 'Sesión cerrada exitosamente'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Error al cerrar sesión',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Refrescar el token JWT
     */
    public function refresh()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
            
            return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'No se pudo refrescar el token',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 401);
        }
    }
}

