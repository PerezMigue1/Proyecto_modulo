<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificar email - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="/css/auth.css?v=2.0">
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
                <p>Antes de continuar, por favor verifica tu correo electrónico mediante el enlace que te hemos enviado.</p>
                <p>Si no recibiste el correo, puedes solicitarlo nuevamente haciendo clic en el botón de abajo.</p>
                
                <form method="POST" action="{{ route('verification.send') }}" style="margin-top: 20px;">
                    @csrf
                    <button type="submit" class="btn-primary">
                        Reenviar enlace de verificación
                    </button>
                </form>
                
                <div style="margin-top: 20px; text-align: center;">
                    <a href="{{ route('logout') }}" class="link">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
