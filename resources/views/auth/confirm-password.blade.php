<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmar contraseña - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="/css/auth.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Confirmar contraseña</h1>
                <p>Por favor confirma tu contraseña antes de continuar</p>
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

            <form method="POST" action="{{ route('password.confirm') }}" class="login-form">
                @csrf

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        autofocus
                        placeholder="••••••••"
                    >
                </div>

                <button type="submit" class="btn-primary">
                    Confirmar contraseña
                </button>

                <div class="links">
                    <a href="{{ route('password.request') }}" class="link">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

