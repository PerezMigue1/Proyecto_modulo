<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordRecoveryController extends Controller
{
    // Mostrar vista de recuperación
    public function show(Request $request)
    {
        return view('auth.forgot-password');
    }

    // Paso 1: Verificar email y mostrar pregunta
    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Buscar el usuario por email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'No se encontró un usuario con ese correo electrónico.'])
                ->withInput();
        }

        // Verificar que tenga pregunta secreta
        if (!isset($user->pregunta_secreta) || !is_array($user->pregunta_secreta)) {
            return back()
                ->withErrors(['email' => 'Este usuario no tiene una pregunta secreta configurada.'])
                ->withInput();
        }

        // Guardar email en sesión
        $request->session()->put('password_recovery_email', $request->email);
        $request->session()->put('password_recovery_pregunta', $user->pregunta_secreta['pregunta']);

        return view('auth.question-password', [
            'email' => $request->email,
            'pregunta_secreta' => $user->pregunta_secreta['pregunta']
        ]);
    }

    // Paso 2: Verificar respuesta secreta
    public function verifyAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'respuesta_secreta' => 'required|string',
        ]);

        if ($validator->fails()) {
            return view('auth.question-password', [
                'email' => $request->email,
                'pregunta_secreta' => session('password_recovery_pregunta'),
            ])->withErrors($validator);
        }

        // Buscar el usuario
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return view('auth.question-password', [
                'email' => $request->email,
                'pregunta_secreta' => session('password_recovery_pregunta'),
            ])->withErrors(['respuesta_secreta' => 'Usuario no encontrado.']);
        }

        // Verificar la respuesta
        $respuestaCorrecta = isset($user->pregunta_secreta['respuesta']) && 
                            strtolower($user->pregunta_secreta['respuesta']) === strtolower($request->respuesta_secreta);

        if (!$respuestaCorrecta) {
            return view('auth.question-password', [
                'email' => $request->email,
                'pregunta_secreta' => session('password_recovery_pregunta'),
            ])->withErrors(['respuesta_secreta' => 'La respuesta secreta no es correcta.']);
        }

        // Guardar en sesión que la verificación es correcta
        $request->session()->put('password_recovery_verified', true);

        return view('auth.new-password', [
            'email' => $request->email
        ]);
    }

    // Paso 3: Actualizar contraseña
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return view('auth.new-password', [
                'email' => $request->email
            ])->withErrors($validator);
        }

        // Verificar que se haya completado la verificación
        if (!$request->session()->has('password_recovery_verified')) {
            return redirect()->route('password.request')
                ->withErrors(['error' => 'Debe completar la verificación primero.']);
        }

        // Buscar el usuario
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return view('auth.new-password', [
                'email' => $request->email
            ])->withErrors(['error' => 'Usuario no encontrado.']);
        }

        // Actualizar la contraseña
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Limpiar sesión
        $request->session()->forget([
            'password_recovery_email', 
            'password_recovery_pregunta', 
            'password_recovery_verified'
        ]);

        return redirect()->route('login')
            ->with('status', 'Contraseña recuperada exitosamente. Ahora puedes iniciar sesión.');
    }
}
