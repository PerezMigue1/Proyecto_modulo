<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=2.0">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Crear cuenta</h1>
                <p>Regístrate para comenzar</p>
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

            <form method="POST" action="{{ route('register') }}" class="login-form">
                @csrf

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus
                        placeholder="Tu nombre"
                    >
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required
                        placeholder="tu@correo.com"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
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

                <div class="form-group">
                    <label for="pregunta_secreta">Pregunta secreta</label>
                    <select 
                        id="pregunta_secreta" 
                        name="pregunta_secreta" 
                        required
                    >
                        <option value="">Selecciona una pregunta</option>
                        @if(isset($preguntas) && is_array($preguntas) && count($preguntas) > 0)
                            @foreach($preguntas as $pregunta)
                                @if(isset($pregunta['pregunta']))
                                    <option value="{{ $pregunta['pregunta'] }}">
                                        {{ $pregunta['pregunta'] }}
                                    </option>
                                @endif
                            @endforeach
                        @else
                            <!-- Preguntas por defecto si no hay en MongoDB -->
                            <option value="¿Cuál es el nombre de tu primera mascota?">¿Cuál es el nombre de tu primera mascota?</option>
                            <option value="¿En qué ciudad naciste?">¿En qué ciudad naciste?</option>
                            <option value="¿Cuál es el nombre de tu mejor amigo de la infancia?">¿Cuál es el nombre de tu mejor amigo de la infancia?</option>
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="respuesta_secreta">Respuesta secreta</label>
                    <input 
                        type="text" 
                        id="respuesta_secreta" 
                        name="respuesta_secreta" 
                        value="{{ old('respuesta_secreta') }}" 
                        required
                        placeholder="Tu respuesta secreta"
                    >
                </div>

                <button type="submit" class="btn-primary">
                    Registrarse
                </button>

                <div class="links">
                    <a href="{{ route('login') }}" class="link">← Ya tengo una cuenta</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

