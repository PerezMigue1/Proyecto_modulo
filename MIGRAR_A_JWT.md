# Migración de Laravel Sanctum a JWT

## Por Qué Migrar a JWT

- ✅ **Compatible con MongoDB**: JWT funciona perfectamente con MongoDB
- ✅ **Más simple**: No requiere tablas de base de datos para tokens
- ✅ **Mejor rendimiento**: Los tokens se validan sin consultas a la BD
- ✅ **Más flexible**: Funciona con cualquier tipo de base de datos

## Paso 1: Instalar JWT en el Backend

### 1.1 Instalar el Paquete

```bash
composer require tymon/jwt-auth
```

### 1.2 Publicar la Configuración

```bash
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

### 1.3 Generar la Clave Secreta

```bash
php artisan jwt:secret
```

Este comando generará una clave en tu archivo `.env`:
```
JWT_SECRET=tu_clave_secreta_aqui
```

## Paso 2: Configurar el Modelo User

### 2.1 Actualizar el Modelo User

```php
<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'pregunta_secreta',
        'respuesta_secreta',
        'telefono',
        'google_id',
        'facebook_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
```

## Paso 3: Configurar el Guard de Autenticación

### 3.1 Actualizar config/auth.php

```php
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
```

## Paso 4: Actualizar los Controladores

### 4.1 AuthController (Login)

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $credentials = $request->only('email', 'password');
            $user = User::where('email', $credentials['email'])->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Las credenciales son incorrectas'
                ], 401);
            }

            if (!Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'message' => 'Las credenciales son incorrectas'
                ], 401);
            }

            if (!$token = JWTAuth::fromUser($user)) {
                return response()->json([
                    'message' => 'No se pudo crear el token'
                ], 500);
            }

            return response()->json([
                'user' => $user->makeHidden(['password', 'two_factor_secret', 'two_factor_recovery_codes']),
                'token' => $token,
                'token_type' => 'bearer',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function user(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json(
                $user->makeHidden(['password', 'two_factor_secret', 'two_factor_recovery_codes'])
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Token inválido o expirado'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::parseToken()->invalidate();
            return response()->json([
                'message' => 'Sesión cerrada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cerrar sesión'
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
            return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se pudo refrescar el token'
            ], 401);
        }
    }
}
```

### 4.2 RegisterController

```php
<?php

namespace App\Http\Controllers\Api;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    public function store(Request $request, CreateNewUser $creator)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('mongodb')->collection('usuarios')->where('email', $request->email),
                ],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'pregunta_secreta' => ['required', 'string'],
                'respuesta_secreta' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $creator->create($request->all());

            // Crear token JWT
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'user' => $user->makeHidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'pregunta_secreta']),
                'token' => $token,
                'token_type' => 'bearer',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
```

### 4.3 GoogleAuthController y FacebookAuthController

```php
// Para OAuth, también usar JWT
$token = JWTAuth::fromUser($user);
return redirect($frontendUrl . '/auth/callback?token=' . $token . '&provider=google');
```

## Paso 5: Configurar Middleware

### 5.1 Crear Middleware para JWT

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Token inválido o expirado'], 401);
        }
        return $next($request);
    }
}
```

### 5.2 Registrar el Middleware

En `bootstrap/app.php` o `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'api' => [
        // ... otros middlewares
        \App\Http\Middleware\JWTMiddleware::class,
    ],
];
```

## Paso 6: Actualizar las Rutas

### 6.1 routes/api.php

```php
<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordRecoveryController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SecretQuestionController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/preguntas-secretas', [SecretQuestionController::class, 'index']);

// Password recovery routes
Route::post('/password/verify-email', [PasswordRecoveryController::class, 'verifyEmail']);
Route::post('/password/verify-answer', [PasswordRecoveryController::class, 'verifyAnswer']);
Route::post('/password/update', [PasswordRecoveryController::class, 'updatePassword']);

// Protected routes (require JWT authentication)
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});
```

## Paso 7: Configurar Variables de Entorno

### 7.1 Agregar a .env

```env
JWT_SECRET=tu_clave_secreta_generada
JWT_TTL=60
JWT_REFRESH_TTL=20160
```

### 7.2 En Render

Agrega `JWT_SECRET` como variable de entorno en Render Dashboard.

## Paso 8: Actualizar el Frontend

El frontend necesita cambios mínimos:

1. **El token JWT se maneja igual que el token de Sanctum**
2. **El header de autorización es el mismo**: `Authorization: Bearer {token}`
3. **No hay cambios necesarios en la estructura de la respuesta**

## Ventajas de JWT sobre Sanctum

1. ✅ **No requiere base de datos**: Los tokens se validan sin consultas a la BD
2. ✅ **Compatible con MongoDB**: Funciona perfectamente con MongoDB
3. ✅ **Mejor rendimiento**: Validación más rápida
4. ✅ **Más flexible**: Funciona con cualquier tipo de base de datos
5. ✅ **Stateless**: No requiere almacenar tokens en la base de datos

## Próximos Pasos

1. Instala JWT en el backend
2. Actualiza el modelo User
3. Actualiza los controladores
4. Configura las rutas
5. Prueba el login y registro
6. El frontend debería funcionar sin cambios (o con cambios mínimos)

## Notas Importantes

- **JWT_SECRET**: Debe ser una cadena aleatoria y segura
- **JWT_TTL**: Tiempo de vida del token en minutos (por defecto 60)
- **JWT_REFRESH_TTL**: Tiempo de vida del token de refresco en minutos (por defecto 20160 = 14 días)
- **Tokens expirados**: El frontend debe manejar tokens expirados y refrescarlos si es necesario

