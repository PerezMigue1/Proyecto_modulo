<?php

/**
 * CONFIGURACIÓN PARA JWT EN LARAVEL
 * 
 * Archivo: config/auth.php
 * 
 * Actualiza la sección de guards para usar JWT
 */

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'mongodb',
            'model' => App\Models\User::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];

// ==========================================
// VARIABLES DE ENTORNO (.env)
// ==========================================

/*
JWT_SECRET=tu_clave_secreta_generada
JWT_TTL=60
JWT_REFRESH_TTL=20160
JWT_ALGO=HS256
*/

// ==========================================
// COMANDOS PARA EJECUTAR
// ==========================================

/*
1. Instalar JWT:
   composer require tymon/jwt-auth

2. Publicar configuración:
   php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

3. Generar clave secreta:
   php artisan jwt:secret

4. Actualizar modelo User para implementar JWTSubject

5. Actualizar controladores para usar JWTAuth

6. Configurar guard en config/auth.php

7. Actualizar rutas para usar middleware auth:api
*/

