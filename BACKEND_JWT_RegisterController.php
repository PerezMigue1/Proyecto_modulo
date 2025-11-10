<?php

namespace App\Http\Controllers\Api;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class RegisterController extends Controller
{
    /**
     * Registrar nuevo usuario y obtener token JWT
     */
    public function store(Request $request, CreateNewUser $creator)
    {
        try {
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
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear el usuario
            $user = $creator->create($request->all());

            // Crear token JWT
            try {
                $token = JWTAuth::fromUser($user);
            } catch (JWTException $e) {
                // Si no se puede crear el token, aún así devolver éxito
                // El usuario puede hacer login después
                return response()->json([
                    'message' => 'Usuario creado exitosamente. Por favor, inicia sesión.',
                ], 201);
            }

            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'user' => $user->makeHidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'pregunta_secreta']),
                'token' => $token,
                'token_type' => 'bearer',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}

