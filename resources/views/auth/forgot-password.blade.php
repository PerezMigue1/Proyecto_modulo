<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="/css/auth.css?v=2.0">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Recuperar contraseña</h1>
                <p>Ingresa tu correo electrónico para continuar</p>
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

            <form method="POST" action="{{ route('password.verify-email') }}" class="login-form">
                @csrf

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        placeholder="tu@correo.com"
                    >
                </div>

                <button type="submit" class="btn-primary">
                    Continuar
                </button>

                <div class="links">
                    <a href="{{ route('login') }}" class="link">← Volver al inicio de sesión</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

