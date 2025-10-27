<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificación de dos factores - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Verificación de dos factores</h1>
                <p>Ingresa el código de verificación</p>
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

            <form method="POST" action="{{ route('two-factor.login') }}" class="login-form">
                @csrf

                <div class="form-group">
                    <label for="code">Código</label>
                    <input 
                        type="text" 
                        id="code" 
                        name="code" 
                        required 
                        autofocus
                        placeholder="123456"
                    >
                </div>

                <button type="submit" class="btn-primary">
                    Verificar
                </button>

                <div class="links">
                    <a href="{{ route('login') }}" class="link">← Volver al inicio</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

