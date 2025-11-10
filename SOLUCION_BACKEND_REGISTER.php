<?php

namespace App\Http\Controllers\Api;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function store(Request $request, CreateNewUser $creator)
    {
        try {
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('mongodb')->collection('usuarios')->where('email', $request->email),
                ],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'pregunta_secreta' => ['required', 'string'],
                'respuesta_secreta' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                Log::warning('Error de validación en registro:', ['errors' => $validator->errors()]);
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Log para debugging
            Log::info('Intento de registro:', ['email' => $request->email]);

            // Crear el usuario
            try {
                $user = $creator->create($request->all());
                Log::info('Usuario creado exitosamente:', ['email' => $user->email]);
            } catch (\Exception $e) {
                Log::error('Error al crear usuario:', [
                    'email' => $request->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'message' => 'Error al crear el usuario',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            // Verificar que el usuario tiene el trait HasApiTokens
            if (!method_exists($user, 'createToken')) {
                Log::warning('El modelo User no tiene el método createToken. El usuario se creó pero no se puede crear el token.');
                // NO devolver error 500, solo devolver éxito sin token
                return response()->json([
                    'message' => 'Usuario creado exitosamente. Por favor, inicia sesión.',
                ], 201);
            }

            // Intentar crear el token (opcional, ya que redirigimos al login)
            try {
                $token = $user->createToken('auth-token')->plainTextToken;
                Log::info('Token creado exitosamente:', ['email' => $user->email]);
                
                // Ocultar campos sensibles
                $userData = $user->makeHidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'pregunta_secreta']);
                
                return response()->json([
                    'message' => 'Usuario creado exitosamente',
                    'user' => $userData,
                    'token' => $token,
                ], 201);
            } catch (\Exception $e) {
                Log::warning('Error al crear token después del registro:', [
                    'email' => $user->email,
                    'error' => $e->getMessage()
                ]);
                // NO devolver error 500, solo devolver éxito sin token
                return response()->json([
                    'message' => 'Usuario creado exitosamente. Por favor, inicia sesión.',
                ], 201);
            }

        } catch (\Exception $e) {
            Log::error('Error en registro:', [
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

