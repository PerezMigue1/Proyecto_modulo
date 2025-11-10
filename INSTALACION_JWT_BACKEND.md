# Instalación de JWT en el Backend - Guía Paso a Paso

## Paso 1: Instalar el Paquete JWT

```bash
composer require tymon/jwt-auth
```

## Paso 2: Publicar la Configuración

```bash
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

Esto creará el archivo `config/jwt.php` con la configuración de JWT.

## Paso 3: Generar la Clave Secreta

```bash
php artisan jwt:secret
```

Este comando:
- Genera una clave secreta aleatoria
- La agrega a tu archivo `.env` como `JWT_SECRET`
- La usa para firmar y verificar los tokens JWT

## Paso 4: Actualizar el Modelo User

1. Abre `app/Models/User.php`
2. Implementa la interfaz `JWTSubject`
3. Agrega los métodos `getJWTIdentifier()` y `getJWTCustomClaims()`

Ver archivo: `BACKEND_JWT_User_Model.php`

## Paso 5: Configurar el Guard de Autenticación

1. Abre `config/auth.php`
2. Actualiza la sección `guards` para usar JWT:

```php
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

Ver archivo: `BACKEND_JWT_Config.php`

## Paso 6: Actualizar los Controladores

### 6.1 AuthController

Reemplaza el contenido de `app/Http/Controllers/Api/AuthController.php` con el código de `BACKEND_JWT_AuthController.php`

### 6.2 RegisterController

Reemplaza el contenido de `app/Http/Controllers/Api/RegisterController.php` con el código de `BACKEND_JWT_RegisterController.php`

### 6.3 GoogleAuthController

Reemplaza el contenido de `app/Http/Controllers/Api/GoogleAuthController.php` con el código de `BACKEND_JWT_GoogleAuthController.php`

### 6.4 FacebookAuthController

Reemplaza el contenido de `app/Http/Controllers/Api/FacebookAuthController.php` con el código de `BACKEND_JWT_FacebookAuthController.php`

## Paso 7: Actualizar las Rutas

1. Abre `routes/api.php`
2. Reemplaza el contenido con el código de `BACKEND_JWT_Routes.php`

## Paso 8: Configurar Variables de Entorno

### 8.1 En .env (Local)

Agrega estas variables:

```env
JWT_SECRET=tu_clave_secreta_generada
JWT_TTL=60
JWT_REFRESH_TTL=20160
JWT_ALGO=HS256
```

### 8.2 En Render (Producción)

1. Ve a Render Dashboard
2. Selecciona tu servicio backend
3. Ve a la pestaña "Environment"
4. Agrega las variables de entorno:
   - `JWT_SECRET`: La clave generada (debe ser la misma que en .env)
   - `JWT_TTL`: 60 (opcional, tiempo de vida del token en minutos)
   - `JWT_REFRESH_TTL`: 20160 (opcional, tiempo de vida del token de refresco)
   - `JWT_ALGO`: HS256 (opcional, algoritmo de firma)

## Paso 9: Eliminar Referencias a Sanctum

### 9.1 Eliminar el Trait HasApiTokens del Modelo User

En `app/Models/User.php`, elimina:
```php
use Laravel\Sanctum\HasApiTokens;
```

Y en la clase:
```php
use HasApiTokens, Notifiable;
```

Reemplázalo con:
```php
use Notifiable;
```

### 9.2 Eliminar Sanctum (Opcional)

Si ya no vas a usar Sanctum:

```bash
composer remove laravel/sanctum
```

### 9.3 Limpiar Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Paso 10: Probar la Instalación

### 10.1 Probar Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"tu@email.com","password":"tu_contraseña"}'
```

Deberías recibir una respuesta con un token JWT.

### 10.2 Probar Registro

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Nombre","email":"nuevo@email.com","password":"contraseña123","password_confirmation":"contraseña123","pregunta_secreta":"¿Cuál es tu color favorito?","respuesta_secreta":"azul"}'
```

### 10.3 Probar Obtener Usuario

```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer tu_token_jwt"
```

## Paso 11: Desplegar a Render

1. Haz commit de todos los cambios
2. Haz push a tu repositorio
3. Render desplegará automáticamente
4. Verifica que las variables de entorno estén configuradas en Render
5. Prueba el login y registro desde el frontend

## Verificación

Después de completar todos los pasos:

1. ✅ El login debería funcionar y devolver un token JWT
2. ✅ El registro debería funcionar y devolver un token JWT
3. ✅ OAuth (Google/Facebook) debería funcionar y devolver un token JWT
4. ✅ Las rutas protegidas deberían requerir el token JWT
5. ✅ El frontend debería poder usar el token sin cambios (o con cambios mínimos)

## Solución de Problemas

### Error: "Class 'Tymon\JWTAuth\Facades\JWTAuth' not found"

**Solución:** Verifica que el paquete esté instalado:
```bash
composer show tymon/jwt-auth
```

### Error: "JWT_SECRET not set"

**Solución:** Ejecuta:
```bash
php artisan jwt:secret
```

### Error: "Token could not be parsed"

**Solución:** Verifica que el token esté siendo enviado correctamente en el header:
```
Authorization: Bearer {token}
```

### Error: "Token has expired"

**Solución:** El token ha expirado. Usa el endpoint `/api/refresh` para obtener un nuevo token.

## Notas Importantes

1. **JWT_SECRET**: Debe ser una cadena aleatoria y segura. Nunca la compartas públicamente.
2. **JWT_TTL**: Tiempo de vida del token en minutos. Por defecto 60 minutos.
3. **JWT_REFRESH_TTL**: Tiempo de vida del token de refresco. Por defecto 20160 minutos (14 días).
4. **Tokens expirados**: El frontend debe manejar tokens expirados y refrescarlos si es necesario.

## Próximos Pasos

1. Instala JWT en el backend
2. Actualiza todos los controladores
3. Configura las variables de entorno
4. Prueba el login y registro
5. El frontend debería funcionar sin cambios (o con cambios mínimos)

