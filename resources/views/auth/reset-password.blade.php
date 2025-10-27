<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer contraseña - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Restablecer contraseña</h1>
                <p>Ingresa tu nueva contraseña</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="login-form">
                @csrf
                <input type="hidden" name="token" value="{{ $request->token }}">

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $request->email) }}" 
                        required 
                        readonly
                        placeholder="tu@correo.com"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Nueva contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        autofocus
                        placeholder="••••••••"
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required
                        placeholder="••••••••"
                    >
                </div>

                <button type="submit" class="btn-primary">
                    Restablecer contraseña
                </button>

                <div class="links">
                    <a href="{{ route('login') }}" class="link">← Volver al inicio de sesión</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

