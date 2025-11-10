# Error 500 en Login - Diagnostico

## Problema
El backend devuelve un error 500 (Internal Server Error) al intentar hacer login.

## Logs del Frontend
```
POST https://backend-equipo.onrender.com/api/login 500 (Internal Server Error)
❌ Error 500 - Error interno del servidor
```

## Posibles Causas

### 1. Problema con la Base de Datos
- La conexión a MongoDB puede estar fallando
- La colección `usuarios` puede no existir
- El usuario puede no existir en la base de datos
- Puede haber un problema con la estructura de datos

### 2. Problema con la Autenticación
- El hash de la contraseña puede no coincidir
- El modelo User puede tener un problema
- Laravel Sanctum puede no estar configurado correctamente
- Puede haber un problema con el método `createToken()`

### 3. Problema con el Controlador
- El controlador `AuthController.php` puede tener un error
- Puede haber un problema con la validación
- Puede haber un problema con la respuesta JSON

### 4. Problema con el Backend
- Puede haber un error en el código del backend
- Puede haber un problema con las dependencias
- Puede haber un problema con las variables de entorno

## Como Diagnosticar

### 1. Verificar los Logs del Backend en Render
1. Ve a Render Dashboard
2. Selecciona el servicio del backend
3. Ve a la pestaña "Logs"
4. Busca errores relacionados con `/api/login`
5. Revisa el stack trace del error

### 2. Verificar la Base de Datos
1. Conecta a MongoDB Atlas
2. Verifica que la colección `usuarios` existe
3. Verifica que el usuario existe:
   ```javascript
   db.usuarios.find({ email: "miguelperez@gmail.com" })
   ```
4. Verifica la estructura del usuario:
   - Debe tener `email`
   - Debe tener `password` (hasheado)
   - Debe tener otros campos requeridos

### 3. Verificar el Controlador de Login
Revisa `app/Http/Controllers/Api/AuthController.php`:
```php
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $token = $user->createToken('auth-token')->plainTextToken;

    return response()->json([
        'user' => $user->makeHidden(['password', 'two_factor_secret', 'two_factor_recovery_codes']),
        'token' => $token,
    ]);
}
```

### 4. Verificar el Modelo User
Revisa `app/Models/User.php`:
- Debe usar `HasApiTokens` de Laravel Sanctum
- Debe tener el campo `password` en `$fillable` o `$guarded`
- Debe tener el método `createToken()` disponible

### 5. Verificar Laravel Sanctum
1. Verifica que Sanctum está instalado: `composer show laravel/sanctum`
2. Verifica que está configurado en `config/sanctum.php`
3. Verifica que el middleware está configurado en `bootstrap/app.php`

## Soluciones Comunes

### Solución 1: Verificar que el Usuario Existe
```php
// En el controlador, agrega logging
\Log::info('Buscando usuario:', ['email' => $request->email]);
$user = User::where('email', $request->email)->first();
\Log::info('Usuario encontrado:', ['user' => $user ? $user->toArray() : 'null']);
```

### Solución 2: Verificar el Hash de la Contraseña
```php
// Verifica que la contraseña está hasheada correctamente
if ($user && Hash::check($request->password, $user->password)) {
    \Log::info('Contraseña correcta');
} else {
    \Log::error('Contraseña incorrecta o usuario no existe');
}
```

### Solución 3: Verificar la Creación del Token
```php
try {
    $token = $user->createToken('auth-token')->plainTextToken;
    \Log::info('Token creado exitosamente');
} catch (\Exception $e) {
    \Log::error('Error al crear token:', ['error' => $e->getMessage()]);
    throw $e;
}
```

### Solución 4: Manejar Errores Específicos
```php
try {
    $user = User::where('email', $request->email)->first();
    
    if (!$user) {
        return response()->json([
            'message' => 'Usuario no encontrado'
        ], 404);
    }
    
    if (!Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Contraseña incorrecta'
        ], 401);
    }
    
    $token = $user->createToken('auth-token')->plainTextToken;
    
    return response()->json([
        'user' => $user->makeHidden(['password']),
        'token' => $token,
    ]);
} catch (\Exception $e) {
    \Log::error('Error en login:', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    return response()->json([
        'message' => 'Error interno del servidor',
        'error' => config('app.debug') ? $e->getMessage() : null
    ], 500);
}
```

## Verificar en el Backend

### 1. Revisar los Logs de Render
Los logs del backend en Render deberían mostrar el error específico:
```
[2025-01-XX XX:XX:XX] production.ERROR: ...
```

### 2. Verificar las Variables de Entorno
- `MONGODB_URI` debe estar configurada correctamente
- `APP_KEY` debe estar configurada
- `APP_DEBUG` puede estar en `true` para ver más detalles (solo en desarrollo)

### 3. Probar el Endpoint Directamente
```bash
curl -X POST https://backend-equipo.onrender.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"miguelperez@gmail.com","password":"tu_contraseña"}'
```

## Siguiente Paso

1. Revisa los logs del backend en Render
2. Verifica que el usuario existe en la base de datos
3. Verifica que la contraseña está hasheada correctamente
4. Verifica que Laravel Sanctum está configurado correctamente
5. Comparte los logs del backend para diagnosticar el problema específico

## Nota

El frontend ahora muestra un mensaje más amigable cuando hay un error 500, pero el problema real está en el backend. Es necesario revisar los logs del backend para ver el error específico.

