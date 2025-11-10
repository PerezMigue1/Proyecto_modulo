# Errores del Backend - Lista Completa

## Errores Identificados Basados en los Logs

### 1. Error 500 en Login (`/api/login`)

**Sintoma:**
- El frontend recibe un error 500 al intentar hacer login
- El usuario no puede autenticarse

**Posibles Causas:**
1. **Laravel Sanctum no está instalado o configurado**
   - El método `createToken()` no existe
   - El modelo User no tiene el trait `HasApiTokens`
   - Error: `Call to undefined method createToken()`

2. **Problema con el modelo User**
   - El modelo User no extiende de `Authenticatable`
   - Falta el trait `HasApiTokens`
   - Error: `Method createToken does not exist`

3. **Problema con la base de datos MongoDB**
   - La conexión a MongoDB falla
   - La colección `usuarios` no existe
   - El usuario no existe en la base de datos
   - Error: `MongoDB connection failed`

4. **Problema con el hash de contraseña**
   - La contraseña no está hasheada correctamente
   - El método `Hash::check()` falla
   - Error: `Hash check failed`

5. **Problema con la respuesta JSON**
   - El controlador no devuelve JSON correctamente
   - Falta el header `Content-Type: application/json`
   - Error: `JSON encoding failed`

6. **Problema con las excepciones**
   - No hay manejo de excepciones en el controlador
   - Las excepciones no se capturan
   - Error: `Unhandled exception`

---

### 2. Error 500 en Registro (`/api/register`)

**Sintoma:**
- El frontend recibe un error 500 al intentar registrarse
- El usuario se crea en la base de datos pero hay un error después

**Posibles Causas:**
1. **Error al crear el token después del registro**
   - El método `createToken()` falla después de crear el usuario
   - El modelo User no tiene el trait `HasApiTokens`
   - Error: `Call to undefined method createToken()`

2. **Problema con la estructura de datos**
   - El campo `pregunta_secreta` no se guarda correctamente
   - La estructura de datos no coincide con el modelo
   - Error: `Data structure mismatch`

3. **Problema con la validación única**
   - La validación de email único falla
   - El campo `email` no es único en la colección
   - Error: `Unique validation failed`

4. **Problema con la respuesta JSON**
   - El controlador no devuelve JSON correctamente
   - Falta el header `Content-Type: application/json`
   - Error: `JSON encoding failed`

5. **Problema con las excepciones**
   - No hay manejo de excepciones en el controlador
   - Las excepciones no se capturan
   - Error: `Unhandled exception`

---

### 3. Error en OAuth (Google/Facebook)

**Sintoma:**
- El usuario no puede autenticarse con Google o Facebook
- El callback de OAuth falla

**Posibles Causas:**
1. **Problema con Laravel Socialite**
   - Socialite no está instalado o configurado
   - Las credenciales de OAuth son incorrectas
   - Error: `Socialite configuration failed`

2. **Problema con el redirect**
   - El redirect URL no coincide con la configuración
   - El callback URL es incorrecto
   - Error: `Redirect URL mismatch`

3. **Problema con la creación del usuario**
   - El usuario de OAuth no se crea correctamente
   - Falta el campo `email` en el usuario de OAuth
   - Error: `User creation failed`

4. **Problema con el token**
   - El token no se crea después de OAuth
   - El método `createToken()` falla
   - Error: `Token creation failed`

---

### 4. Error en Preguntas Secretas (`/api/preguntas-secretas`)

**Sintoma:**
- Las preguntas secretas no se cargan
- El endpoint devuelve un error

**Posibles Causas:**
1. **Problema con la conexión a MongoDB**
   - La conexión a MongoDB falla
   - La colección `recuperar-password` no existe
   - Error: `MongoDB connection failed`

2. **Problema con la consulta**
   - La consulta a MongoDB falla
   - La estructura de datos no coincide
   - Error: `Query failed`

3. **Problema con la respuesta JSON**
   - El controlador no devuelve JSON correctamente
   - La estructura de respuesta es incorrecta
   - Error: `JSON encoding failed`

---

### 5. Error en Recuperación de Contraseña

**Sintoma:**
- El usuario no puede recuperar su contraseña
- El endpoint devuelve un error

**Posibles Causas:**
1. **Problema con la estructura de datos**
   - El campo `pregunta_secreta` no existe o tiene estructura incorrecta
   - La respuesta secreta no se compara correctamente
   - Error: `Data structure mismatch`

2. **Problema con el hash de contraseña**
   - La nueva contraseña no se hashea correctamente
   - El método `Hash::make()` falla
   - Error: `Hash creation failed`

3. **Problema con la actualización**
   - La actualización de la contraseña falla
   - El usuario no se actualiza correctamente
   - Error: `Update failed`

---

## Errores Comunes en el Backend

### 1. Falta el Trait `HasApiTokens` en el Modelo User

**Error:**
```
Call to undefined method App\Models\User::createToken()
```

**Solución:**
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    // ...
}
```

---

### 2. Falta la Configuración de Laravel Sanctum

**Error:**
```
Sanctum service provider not registered
```

**Solución:**
- Verificar que `Laravel\Sanctum\SanctumServiceProvider` esté registrado en `config/app.php`
- Ejecutar `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`

---

### 3. Problema con la Conexión a MongoDB

**Error:**
```
MongoDB connection failed
```

**Solución:**
- Verificar que `MONGODB_URI` esté configurada correctamente
- Verificar que la conexión a MongoDB Atlas sea correcta
- Verificar que el driver de MongoDB esté instalado

---

### 4. Problema con la Estructura de Datos

**Error:**
```
Data structure mismatch
```

**Solución:**
- Verificar que la estructura de datos en MongoDB coincida con el modelo
- Verificar que los campos requeridos existan
- Verificar que los tipos de datos sean correctos

---

### 5. Problema con la Validación Única

**Error:**
```
Unique validation failed
```

**Solución:**
- Verificar que la validación única esté configurada correctamente
- Verificar que el campo `email` sea único en la colección
- Verificar que la regla de validación sea correcta

---

### 6. Problema con el Hash de Contraseña

**Error:**
```
Hash check failed
```

**Solución:**
- Verificar que la contraseña esté hasheada correctamente al crear el usuario
- Verificar que el método `Hash::check()` se use correctamente
- Verificar que la contraseña no esté doblemente hasheada

---

### 7. Problema con las Excepciones

**Error:**
```
Unhandled exception
```

**Solución:**
- Agregar manejo de excepciones en los controladores
- Usar try-catch para capturar excepciones
- Devolver respuestas JSON apropiadas

---

### 8. Problema con CORS

**Error:**
```
CORS policy blocked
```

**Solución:**
- Verificar que `config/cors.php` esté configurado correctamente
- Verificar que los dominios permitidos sean correctos
- Verificar que el middleware de CORS esté habilitado

---

### 9. Problema con las Variables de Entorno

**Error:**
```
Environment variable not set
```

**Solución:**
- Verificar que todas las variables de entorno estén configuradas en Render
- Verificar que `APP_KEY` esté configurada
- Verificar que `MONGODB_URI` esté configurada

---

### 10. Problema con la Respuesta JSON

**Error:**
```
JSON encoding failed
```

**Solución:**
- Verificar que el controlador devuelva JSON correctamente
- Verificar que el header `Content-Type: application/json` esté presente
- Verificar que los datos sean serializables

---

## Cómo Diagnosticar los Errores

### 1. Revisar los Logs de Render

1. Ve a Render Dashboard
2. Selecciona tu servicio backend
3. Ve a la pestaña "Logs"
4. Busca errores relacionados con las rutas problemáticas
5. Revisa el stack trace del error

### 2. Verificar el Código del Backend

1. Revisa los controladores (`AuthController.php`, `RegisterController.php`, etc.)
2. Verifica que tengan manejo de excepciones
3. Verifica que devuelvan respuestas JSON correctas
4. Verifica que usen los métodos correctos

### 3. Verificar la Base de Datos

1. Conecta a MongoDB Atlas
2. Verifica que las colecciones existan
3. Verifica que los datos estén estructurados correctamente
4. Verifica que los usuarios existan

### 4. Verificar la Configuración

1. Verifica que Laravel Sanctum esté instalado y configurado
2. Verifica que el modelo User tenga el trait `HasApiTokens`
3. Verifica que CORS esté configurado correctamente
4. Verifica que las variables de entorno estén configuradas

---

## Soluciones Recomendadas

### 1. Agregar Manejo de Excepciones

```php
try {
    // Código del controlador
} catch (\Exception $e) {
    \Log::error('Error en controlador:', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    return response()->json([
        'message' => 'Error interno del servidor',
        'error' => config('app.debug') ? $e->getMessage() : null
    ], 500);
}
```

### 2. Verificar que el Modelo User tenga el Trait HasApiTokens

```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    // ...
}
```

### 3. Verificar que Laravel Sanctum esté Configurado

```php
// config/sanctum.php
return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,127.0.0.1')),
    // ...
];
```

### 4. Verificar que CORS esté Configurado

```php
// config/cors.php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000')),
    // ...
];
```

---

## Próximos Pasos

1. Revisa los logs del backend en Render
2. Identifica el error específico
3. Aplica la solución correspondiente
4. Prueba nuevamente la funcionalidad
5. Verifica que el error se haya solucionado

