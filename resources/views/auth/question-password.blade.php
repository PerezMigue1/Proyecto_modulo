<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificar pregunta secreta - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="/css/auth.css?v=2.0">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Pregunta secreta</h1>
                <p>Responde tu pregunta secreta para continuar</p>
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

            <form method="POST" action="{{ route('password.verify-answer') }}" class="login-form">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label for="pregunta_secreta">Tu pregunta secreta:</label>
                    <input 
                        type="text" 
                        id="pregunta_secreta" 
                        value="{{ $pregunta_secreta }}" 
                        readonly
                        style="background-color: #f3f4f6; cursor: not-allowed;"
                    >
                </div>

                <div class="form-group">
                    <label for="respuesta_secreta">Respuesta secreta</label>
                    <input 
                        type="text" 
                        id="respuesta_secreta" 
                        name="respuesta_secreta" 
                        required 
                        autofocus
                        placeholder="Tu respuesta"
                    >
                </div>

                <button type="submit" class="btn-primary">
                    Verificar
                </button>

                <div class="links">
                    <a href="{{ route('password.request') }}" class="link">← Volver</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
