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
                <h1>Verifica tu correo</h1>
                <p>Confirma tu dirección de correo electrónico</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="links">
                <p style="margin-bottom: 15px;">Se ha enviado un enlace de verificación a <strong>{{ session('registered_email') }}</strong></p>
                <p style="margin-bottom: 20px;">Por favor, revisa tu bandeja de entrada y haz clic en el enlace para verificar tu correo electrónico.</p>
                <p style="margin-bottom: 20px;">Si no recibiste el correo, puedes solicitarlo nuevamente haciendo clic en el botón de abajo.</p>
                
                <form method="POST" action="{{ route('verification.resend') }}" style="margin-top: 20px;">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('registered_email') }}">
                    <button type="submit" class="btn-primary">
                        Reenviar enlace de verificación
                    </button>
                </form>
                
                <div style="margin-top: 20px; text-align: center;">
                    <a href="{{ route('login') }}" class="link">Volver al login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

