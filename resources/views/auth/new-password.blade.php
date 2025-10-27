<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva contraseña - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=2.0">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Nueva contraseña</h1>
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

            <form method="POST" action="{{ route('password.update-new') }}" class="login-form">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label for="new_password">Nueva contraseña</label>
                    <input 
                        type="password" 
                        id="new_password" 
                        name="new_password" 
                        required 
                        autofocus
                        placeholder="••••••••"
                    >
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Confirmar nueva contraseña</label>
                    <input 
                        type="password" 
                        id="new_password_confirmation" 
                        name="new_password_confirmation" 
                        required
                        placeholder="••••••••"
                    >
                </div>

                <button type="submit" class="btn-primary">
                    Cambiar contraseña
                </button>

                <div class="links">
                    <a href="{{ route('login') }}" class="link">← Volver al inicio de sesión</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

