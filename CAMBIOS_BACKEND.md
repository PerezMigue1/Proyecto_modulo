# 🔧 Cambios para Aplicar en el Backend

## Resumen
Estos son los cambios que debes aplicar en tu proyecto de backend para que las APIs funcionen correctamente con el frontend.

## 📋 Archivos Modificados

### 1. `app/Http/Controllers/Api/RegisterController.php`

**Cambios:**
- Validación mejorada con mensajes en español
- Especificar tabla de MongoDB en `Rule::unique('usuario', 'email')`
- Ocultar `pregunta_secreta` en la respuesta (no devolver la respuesta secreta)
- Manejo de excepciones mejorado
- Logging de errores

**Código clave:**
```php
Rule::unique('usuario', 'email'), // Especificar tabla de MongoDB

// Ocultar pregunta_secreta en respuesta
$userData = $user->makeHidden([
    'password', 
    'two_factor_secret', 
    'two_factor_recovery_codes',
    'remember_token',
    'pregunta_secreta' // No devolver la respuesta secreta
])->toArray();
```

### 2. `app/Http/Controllers/Api/AuthController.php`

**Cambios:**
- Mensajes de error en español
- Separar validación: usuario no existe vs contraseña incorrecta
- Ocultar `pregunta_secreta` en respuestas
- Manejo de excepciones en `login()` y `user()`
- Logging de errores

**Código clave:**
```php
// Mensajes en español
'email.required' => 'El correo electrónico es obligatorio.',
'email.email' => 'El correo electrónico debe ser válido.',
'password.required' => 'La contraseña es obligatoria.',

// Ocultar pregunta_secreta
$userData = $user->makeHidden([
    'password', 
    'two_factor_secret', 
    'two_factor_recovery_codes',
    'remember_token',
    'pregunta_secreta' // No devolver la respuesta secreta
])->toArray();
```

### 3. `config/cors.php`

**Cambios:**
- Agregar soporte para Netlify
- Limpiar valores vacíos en `allowed_origins`

**Código:**
```php
'allowed_origins' => array_filter(array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000,http://localhost:5173,http://127.0.0.1:3000,http://127.0.0.1:5173,https://modulo-usuario.netlify.app,https://*.netlify.app')))),
```

**Importante:** Agregar en tu `.env` del backend:
```env
CORS_ALLOWED_ORIGINS=https://modulo-usuario.netlify.app,https://tu-frontend.netlify.app,http://localhost:3000
```

### 4. `app/Actions/Fortify/CreateNewUser.php`

**Cambios:**
- Especificar tabla de MongoDB en validación

**Código:**
```php
Rule::unique('usuario', 'email'), // Especificar tabla de MongoDB
```

### 5. `app/Http/Controllers/Api/PasswordRecoveryController.php`

**Cambios:**
- Verificar que `pregunta_secreta` existe antes de leer
- Mensajes de error más claros

**Código clave:**
```php
// Verificar si el usuario tiene pregunta secreta configurada
$preguntaSecretaAttr = $user->getAttribute('pregunta_secreta');

if (!$preguntaSecretaAttr) {
    return response()->json([
        'errors' => ['email' => ['Este usuario no tiene una pregunta secreta configurada.']],
        'message' => 'Usuario sin pregunta secreta.',
    ], 404);
}
```

### 6. `app/Http/Controllers/Api/GoogleAuthController.php`

**Cambios:**
- Validar que Google devuelva email
- Mejor manejo de errores con logging
- Mensajes de error más informativos

**Código clave:**
```php
if (!$googleUser->getEmail()) {
    throw new \Exception('No se pudo obtener el email de Google');
}

// Logging
\Log::error('Error en Google OAuth: ' . $e->getMessage());
```

### 7. `app/Http/Controllers/Api/FacebookAuthController.php`

**Cambios:**
- Validar que Facebook devuelva email
- Mejor manejo de errores con logging
- Mensajes de error más informativos

**Código clave:**
```php
if (!$facebookUser->getEmail()) {
    throw new \Exception('No se pudo obtener el email de Facebook. Asegúrate de que la aplicación de Facebook tenga permisos para acceder al email.');
}

// Logging
\Log::error('Error en Facebook OAuth: ' . $e->getMessage());
```

## 🔑 Puntos Clave

### 1. Validación de Email Único
```php
// ❌ Incorrecto (no funciona con MongoDB)
Rule::unique(User::class)

// ✅ Correcto
Rule::unique('usuario', 'email')
```

### 2. Ocultar Campos Sensibles
Siempre ocultar `pregunta_secreta` en las respuestas de la API para no exponer la respuesta secreta.

### 3. CORS
Asegurarse de que `CORS_ALLOWED_ORIGINS` incluya la URL de Netlify en el `.env` del backend.

### 4. Mensajes de Error
Todos los mensajes de error están en español para mejor experiencia de usuario.

### 5. Logging
Todos los errores se registran en los logs para facilitar el debugging.

## ✅ Checklist de Aplicación

- [ ] Actualizar `RegisterController.php`
- [ ] Actualizar `AuthController.php`
- [ ] Actualizar `config/cors.php`
- [ ] Actualizar `CreateNewUser.php`
- [ ] Actualizar `PasswordRecoveryController.php`
- [ ] Actualizar `GoogleAuthController.php`
- [ ] Actualizar `FacebookAuthController.php`
- [ ] Agregar `CORS_ALLOWED_ORIGINS` en `.env` del backend
- [ ] Verificar que `FRONTEND_URL` esté configurada en `.env` del backend
- [ ] Probar registro, login, OAuth y recuperación de contraseña

## 🚀 Después de Aplicar los Cambios

1. **Probar registro:**
   ```bash
   POST /api/register
   {
     "name": "Test",
     "email": "test@test.com",
     "password": "password123",
     "password_confirmation": "password123",
     "pregunta_secreta": "¿Cuál es tu mascota?",
     "respuesta_secreta": "Perro"
   }
   ```

2. **Probar login:**
   ```bash
   POST /api/login
   {
     "email": "test@test.com",
     "password": "password123"
   }
   ```

3. **Probar OAuth:**
   - Abrir `/auth/google` en el navegador
   - Verificar que redirija correctamente al frontend

4. **Probar recuperación de contraseña:**
   ```bash
   POST /api/password/verify-email
   {
     "email": "test@test.com"
   }
   ```

## 📝 Notas

- Estos cambios son compatibles con MongoDB
- Los errores ahora son más informativos y en español
- Se ocultan campos sensibles en las respuestas
- CORS está configurado para Netlify
- Los logs ayudan a diagnosticar problemas

## 🔍 Verificación

Después de aplicar los cambios, verifica:

1. ✅ Registro funciona correctamente
2. ✅ Login funciona correctamente
3. ✅ OAuth (Google/Facebook) funciona correctamente
4. ✅ Recuperación de contraseña funciona correctamente
5. ✅ No se expone `pregunta_secreta` en las respuestas
6. ✅ Mensajes de error están en español
7. ✅ CORS permite requests desde Netlify

