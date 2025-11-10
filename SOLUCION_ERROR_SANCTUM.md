# Solución: Error de Laravel Sanctum con MongoDB

## Error Identificado

```
Laravel\Sanctum\NewAccessToken::__construct(): Argument #1 ($accessToken) must be of type Laravel\Sanctum\PersonalAccessToken, App\Models\PersonalAccessToken given
```

## Causa del Problema

El error ocurre porque Laravel Sanctum está intentando usar un modelo personalizado `App\Models\PersonalAccessToken` que no es compatible con MongoDB. Laravel Sanctum por defecto usa Eloquent (MySQL/PostgreSQL), pero cuando se usa MongoDB, necesita una configuración especial.

## Solución 1: Verificar que el Modelo PersonalAccessToken Esté Correctamente Configurado

### Paso 1: Verificar si Existe el Modelo PersonalAccessToken

Verifica si existe el archivo `app/Models/PersonalAccessToken.php`. Si existe, necesitas asegurarte de que esté correctamente configurado.

### Paso 2: Eliminar el Modelo PersonalAccessToken Personalizado (Recomendado)

Si tienes un modelo `App\Models\PersonalAccessToken` personalizado, es mejor eliminarlo y dejar que Laravel Sanctum use su modelo por defecto.

1. Elimina el archivo `app/Models/PersonalAccessToken.php` si existe
2. Asegúrate de que no esté registrado en ningún lugar del código
3. Deja que Laravel Sanctum use su modelo por defecto

### Paso 3: Configurar Sanctum para MongoDB

Si necesitas usar MongoDB con Sanctum, debes configurarlo correctamente. Sin embargo, Laravel Sanctum tiene limitaciones con MongoDB porque está diseñado para bases de datos relacionales.

## Solución 2: Usar el Modelo de Sanctum Correctamente

### Opción A: Usar el Modelo de Sanctum sin Personalizar

1. **Elimina cualquier modelo PersonalAccessToken personalizado:**
   ```bash
   rm app/Models/PersonalAccessToken.php
   ```

2. **Verifica que el modelo User use HasApiTokens correctamente:**
   ```php
   use Laravel\Sanctum\HasApiTokens;
   
   class User extends Authenticatable
   {
       use HasApiTokens, Notifiable;
       // ...
   }
   ```

3. **Verifica la configuración de Sanctum:**
   Asegúrate de que `config/sanctum.php` esté configurado correctamente.

### Opción B: Crear un Modelo PersonalAccessToken Compatible (Si es Necesario)

Si realmente necesitas un modelo personalizado, debes asegurarte de que extienda del modelo correcto:

```php
<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    // Tu código personalizado aquí
}
```

## Solución 3: Verificar la Configuración de Sanctum

### Paso 1: Verificar que Sanctum Esté Instalado Correctamente

```bash
composer show laravel/sanctum
```

### Paso 2: Verificar que el Modelo User Esté Correcto

Asegúrate de que el modelo `User` tenga el trait `HasApiTokens`:

```php
<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    
    // ...
}
```

### Paso 3: Verificar la Configuración de Sanctum

Revisa `config/sanctum.php` y asegúrate de que esté configurado correctamente para tu entorno.

## Solución 4: Limpiar la Cache de Laravel

A veces el problema es que Laravel tiene cache de configuración o rutas:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Solución 5: Reinstalar Sanctum (Si es Necesario)

Si nada funciona, puedes intentar reinstalar Sanctum:

```bash
composer remove laravel/sanctum
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

## Nota sobre MongoDB y Sanctum

**IMPORTANTE:** Laravel Sanctum fue diseñado para bases de datos relacionales (MySQL, PostgreSQL, SQLite). Aunque puede funcionar con MongoDB usando el paquete `mongodb/laravel-mongodb`, puede haber limitaciones.

### Alternativas si Sanctum No Funciona con MongoDB:

1. **Usar JWT (JSON Web Tokens)** con `tymon/jwt-auth`
2. **Usar Passport** (también de Laravel, pero más complejo)
3. **Crear tu propio sistema de tokens** usando MongoDB directamente

## Verificación Rápida

Para verificar que el problema está solucionado:

1. **Elimina el modelo PersonalAccessToken personalizado** (si existe)
2. **Limpia la cache de Laravel**
3. **Intenta hacer login nuevamente**
4. **Revisa los logs** para ver si el error persiste

## Código de Ejemplo: Modelo User Correcto

```php
<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

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
}
```

## Próximos Pasos

1. **Elimina el modelo PersonalAccessToken personalizado** (si existe)
2. **Verifica que el modelo User tenga HasApiTokens**
3. **Limpia la cache de Laravel**
4. **Prueba el login nuevamente**
5. **Revisa los logs** para confirmar que el error se solucionó

## Si el Problema Persiste

Si después de seguir estos pasos el problema persiste, puede ser que Laravel Sanctum no sea completamente compatible con MongoDB. En ese caso, considera:

1. **Usar JWT** con `tymon/jwt-auth`
2. **Usar Passport** (más complejo pero más robusto)
3. **Crear tu propio sistema de tokens** usando MongoDB directamente

