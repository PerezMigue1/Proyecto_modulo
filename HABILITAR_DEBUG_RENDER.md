# Habilitar APP_DEBUG en Render para Ver Errores Detallados

## Por Qué Habilitar APP_DEBUG

Por defecto, Laravel en producción (`APP_DEBUG=false`) no muestra detalles de los errores por seguridad. Sin embargo, para diagnosticar problemas, es útil habilitar temporalmente `APP_DEBUG=true` para ver los errores completos.

## Como Habilitar APP_DEBUG en Render

### Paso 1: Acceder a Render Dashboard

1. Ve a [https://dashboard.render.com](https://dashboard.render.com)
2. Inicia sesión con tu cuenta
3. Selecciona tu servicio backend

### Paso 2: Agregar Variable de Entorno

1. En el dashboard de tu servicio, ve a la pestaña **"Environment"** o **"Environment Variables"**
2. Haz clic en **"Add Environment Variable"** o **"Add Variable"**
3. Agrega la siguiente variable:
   - **Key:** `APP_DEBUG`
   - **Value:** `true`
4. Haz clic en **"Save Changes"** o **"Add"**

### Paso 3: Reiniciar el Servicio

1. Render debería reiniciar automáticamente el servicio
2. Si no se reinicia automáticamente, ve a la pestaña **"Manual Deploy"** y haz clic en **"Deploy latest commit"**

### Paso 4: Probar el Login

1. Intenta hacer login nuevamente desde el frontend
2. Ahora deberías ver más detalles del error en la respuesta
3. Revisa la consola del navegador para ver el error completo

### Paso 5: Ver los Logs

1. Ve a la pestaña **"Logs"** en Render
2. Busca errores relacionados con `/api/login`
3. Ahora deberías ver el stack trace completo del error

## Deshabilitar APP_DEBUG Después de Diagnosticar

**IMPORTANTE:** Una vez que hayas diagnosticado el problema, **debes deshabilitar APP_DEBUG** por seguridad:

1. Ve a la pestaña **"Environment"** en Render
2. Cambia el valor de `APP_DEBUG` a `false`
3. Guarda los cambios
4. El servicio se reiniciará automáticamente

## Alternativa: Usar Logging en Lugar de APP_DEBUG

En lugar de habilitar `APP_DEBUG`, puedes agregar logging específico en el código:

```php
use Illuminate\Support\Facades\Log;

public function login(Request $request)
{
    try {
        Log::info('Iniciando login', ['email' => $request->email]);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            Log::warning('Usuario no encontrado', ['email' => $request->email]);
            return response()->json(['message' => 'Usuario no encontrado'], 401);
        }
        
        if (!Hash::check($request->password, $user->password)) {
            Log::warning('Contraseña incorrecta', ['email' => $request->email]);
            return response()->json(['message' => 'Contraseña incorrecta'], 401);
        }
        
        Log::info('Intentando crear token', ['email' => $user->email]);
        $token = $user->createToken('auth-token')->plainTextToken;
        Log::info('Token creado exitosamente', ['email' => $user->email]);
        
        return response()->json([
            'user' => $user->makeHidden(['password']),
            'token' => $token,
        ]);
    } catch (\Exception $e) {
        Log::error('Error en login', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'message' => 'Error interno del servidor',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
}
```

## Ventajas de Usar Logging

1. **Seguridad:** No expones información sensible al frontend
2. **Debugging:** Puedes ver los errores en los logs de Render
3. **Producción:** Puedes dejar el logging habilitado en producción
4. **Trazabilidad:** Puedes rastrear todos los errores en los logs

## Después de Habilitar APP_DEBUG

Una vez que hayas habilitado `APP_DEBUG=true`, deberías ver en la respuesta del error algo como:

```json
{
  "message": "Server Error",
  "exception": "Error: Call to undefined method App\\Models\\User::createToken()",
  "file": "/var/www/html/app/Http/Controllers/Api/AuthController.php",
  "line": 75,
  "trace": [
    ...
  ]
}
```

Esto te dirá exactamente qué está fallando y dónde.

## Nota de Seguridad

⚠️ **IMPORTANTE:** Nunca dejes `APP_DEBUG=true` en producción por mucho tiempo. Solo úsalo para diagnosticar problemas y luego deshabilítalo inmediatamente.

