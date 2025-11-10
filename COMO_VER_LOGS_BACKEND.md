# Como Ver los Logs del Backend en Render

## Paso a Paso para Ver los Logs

### 1. Acceder a Render Dashboard

1. Ve a [https://dashboard.render.com](https://dashboard.render.com)
2. Inicia sesión con tu cuenta
3. Selecciona tu servicio backend (probablemente se llama algo como "backend-equipo" o similar)

### 2. Ver los Logs

1. Una vez en el dashboard de tu servicio, busca la pestaña **"Logs"** en el menú superior
2. Haz clic en **"Logs"**
3. Verás una lista de logs en tiempo real

### 3. Buscar Errores Específicos

#### Buscar Errores de Login
En la barra de búsqueda de los logs, busca:
- `/api/login`
- `ERROR`
- `Exception`
- `500`

#### Buscar Errores de Registro
En la barra de búsqueda de los logs, busca:
- `/api/register`
- `ERROR`
- `Exception`
- `500`

### 4. Interpretar los Logs

Los logs deberían mostrar algo como:
```
[2025-01-XX XX:XX:XX] production.ERROR: Call to undefined method App\Models\User::createToken() 
{"exception":"[object] (Error(code: 0): Call to undefined method App\Models\User::createToken() at /var/www/html/app/Http/Controllers/Api/AuthController.php:75)"}
```

O:
```
[2025-01-XX XX:XX:XX] production.ERROR: MongoDB connection failed
{"exception":"[object] (MongoDB\Driver\Exception\ConnectionException(code: 0): ...)"}
```

### 5. Copiar el Error Completo

1. Selecciona el error completo en los logs
2. Cópialo (Ctrl+C)
3. Compártelo para que puedamos diagnosticar el problema

## Errores Comunes que Verás

### Error 1: `Call to undefined method createToken()`
**Causa:** El modelo User no tiene el trait `HasApiTokens`
**Solución:** Agrega `use HasApiTokens;` en el modelo User

### Error 2: `MongoDB connection failed`
**Causa:** La conexión a MongoDB falla
**Solución:** Verifica que `MONGODB_URI` esté configurada correctamente

### Error 3: `Class 'Laravel\Sanctum\HasApiTokens' not found`
**Causa:** Laravel Sanctum no está instalado
**Solución:** Ejecuta `composer require laravel/sanctum`

### Error 4: `Route [login] not defined`
**Causa:** La ruta no está definida
**Solución:** Verifica que las rutas estén definidas en `routes/api.php`

## Si No Puedes Ver los Logs

### Opción 1: Habilitar APP_DEBUG en Render

1. Ve a tu servicio en Render
2. Ve a la pestaña **"Environment"**
3. Agrega o modifica la variable de entorno:
   - Key: `APP_DEBUG`
   - Value: `true`
4. Guarda los cambios
5. El backend se reiniciará automáticamente
6. Intenta hacer login nuevamente
7. Ahora deberías ver más detalles del error en la respuesta

### Opción 2: Agregar Logging Manual

Agrega logging en el controlador para ver qué está pasando:

```php
use Illuminate\Support\Facades\Log;

public function login(Request $request)
{
    try {
        Log::info('Iniciando login', ['email' => $request->email]);
        
        $user = User::where('email', $request->email)->first();
        Log::info('Usuario encontrado', ['user' => $user ? 'Si' : 'No']);
        
        if ($user) {
            Log::info('Verificando contraseña');
            $passwordCheck = Hash::check($request->password, $user->password);
            Log::info('Contraseña verificada', ['correcta' => $passwordCheck]);
            
            if ($passwordCheck) {
                Log::info('Intentando crear token');
                $token = $user->createToken('auth-token')->plainTextToken;
                Log::info('Token creado exitosamente');
            }
        }
    } catch (\Exception $e) {
        Log::error('Error en login', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        throw $e;
    }
}
```

### Opción 3: Probar el Endpoint Directamente

Puedes probar el endpoint directamente con curl o Postman:

```bash
curl -X POST https://backend-equipo.onrender.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"miguelperez@gmail.com","password":"tu_contraseña"}'
```

O usa Postman para hacer la petición y ver la respuesta completa.

## Pasos Siguientes

1. **Ve a Render Dashboard** y accede a los logs
2. **Busca el error específico** relacionado con `/api/login`
3. **Copia el error completo** incluyendo el stack trace
4. **Compártelo** para que podamos diagnosticar el problema exacto

## Nota Importante

El error 500 significa que hay un problema en el backend. Los logs de Render te mostrarán el error exacto que está ocurriendo. Una vez que tengas el error específico, podremos solucionarlo rápidamente.

