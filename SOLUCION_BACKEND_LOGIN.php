<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Validar los datos de entrada
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Log para debugging
            Log::info('Intento de login:', ['email' => $request->email]);

            // Buscar el usuario
            $user = User::where('email', $request->email)->first();

            // Verificar que el usuario existe
            if (!$user) {
                Log::warning('Usuario no encontrado:', ['email' => $request->email]);
                return response()->json([
                    'message' => 'Las credenciales son incorrectas'
                ], 401);
            }

            // Verificar la contraseña
            if (!Hash::check($request->password, $user->password)) {
                Log::warning('Contraseña incorrecta:', ['email' => $request->email]);
                return response()->json([
                    'message' => 'Las credenciales son incorrectas'
                ], 401);
            }

            // Verificar que el usuario tiene el trait HasApiTokens
            if (!method_exists($user, 'createToken')) {
                Log::error('El modelo User no tiene el método createToken. Verifica que tenga el trait HasApiTokens.');
                return response()->json([
                    'message' => 'Error de configuración del servidor'
                ], 500);
            }

            // Crear el token
            try {
                $token = $user->createToken('auth-token')->plainTextToken;
                Log::info('Token creado exitosamente:', ['email' => $request->email]);
            } catch (\Exception $e) {
                Log::error('Error al crear token:', [
                    'email' => $request->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'message' => 'Error al crear el token de autenticación',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            // Ocultar campos sensibles
            $userData = $user->makeHidden(['password', 'two_factor_secret', 'two_factor_recovery_codes']);

            // Devolver la respuesta
            return response()->json([
                'user' => $userData,
                'token' => $token,
            ], 200);

        } catch (ValidationException $e) {
            // Errores de validación
            Log::warning('Error de validación en login:', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Otros errores
            Log::error('Error en login:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'message' => 'Error interno del servidor',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}

