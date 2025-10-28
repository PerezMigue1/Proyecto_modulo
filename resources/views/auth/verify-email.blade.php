<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificar email - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Verificar email</h1>
                <p>Confirma tu dirección de correo electrónico</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="links">
                <p>Se ha enviado un enlace de verificación a tu correo electrónico.</p>
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-primary">
                        Reenviar enlace de verificación
                    </button>
                </form>
                
                <a href="{{ route('logout') }}" class="link">Cerrar sesión</a>
            </div>
        </div>
    </div>
</body>
</html>
