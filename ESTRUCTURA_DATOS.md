# 📊 Estructura de Datos - MongoDB

## Colecciones

### 1. Colección `users` (usuarios)

Estructura de un usuario:
```json
{
  "_id": {
    "$oid": "6902bbd82add90c0020e0444"
  },
  "name": "francisco",
  "email": "valdesfrancis768@gmail.com",
  "password": "$2y$12$y11idYy5k/1hYndTNIuT1u4Te49fztK7CFUT69Masv8jQCEjsIQea",
  "pregunta_secreta": "{\"pregunta\":\"¿Cuál es el nombre de tu primera mascota?\",\"respuesta\":\"Doki\"}",
  "updated_at": {
    "$date": "2025-10-30T01:15:02.479Z"
  },
  "created_at": {
    "$date": "2025-10-30T01:14:00.479Z"
  },
  "remember_token": "ZnlfUx0ji67XgBkgZ0wSJXNSYfX2F4ftM5GfZHynN8BTw5AXUt4wPutE7Tdi"
}
```

**Campos importantes:**
- `pregunta_secreta`: String JSON con formato `{"pregunta":"...","respuesta":"..."}`
- `password`: Hash bcrypt de la contraseña
- `email`: Debe ser único
- `google_id`: (Opcional) ID de Google OAuth
- `facebook_id`: (Opcional) ID de Facebook OAuth

### 2. Colección `recuperar-password`

Estructura de una pregunta secreta disponible:
```json
{
  "_id": {
    "$oid": "68fefb9839a19114216a00cf"
  },
  "pregunta": "¿Cuál fue tu primera escuela?"
}
```

**Nota:** Esta colección solo contiene las preguntas disponibles. Las respuestas están guardadas en la colección `users`.

## Flujos de Datos

### 1. Registro de Usuario

**Frontend envía:**
```json
{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "pregunta_secreta": "¿Cuál es el nombre de tu primera mascota?",
  "respuesta_secreta": "Doki"
}
```

**Backend debe guardar:**
- `name`: "Juan Pérez"
- `email`: "juan@example.com"
- `password`: Hash bcrypt de "password123"
- `pregunta_secreta`: `"{\"pregunta\":\"¿Cuál es el nombre de tu primera mascota?\",\"respuesta\":\"Doki\"}"`
- `created_at`: Fecha actual
- `updated_at`: Fecha actual

### 2. Login de Usuario

**Frontend envía:**
```json
{
  "email": "juan@example.com",
  "password": "password123"
}
```

**Backend debe:**
1. Buscar usuario por `email` en la colección `users`
2. Verificar que el hash de `password` coincida
3. Generar un token de autenticación (Sanctum)
4. Devolver:
```json
{
  "user": {
    "_id": "...",
    "name": "Juan Pérez",
    "email": "juan@example.com",
    // NO incluir password ni pregunta_secreta.respuesta
  },
  "token": "1|..."
}
```

### 3. Login con Google

**Flujo:**
1. Usuario hace clic en "Continuar con Google"
2. Se redirige a Google para autenticación
3. Google redirige a: `https://backend-equipo.onrender.com/auth/google/callback`
4. Backend recibe datos de Google
5. Backend busca o crea usuario en la colección `users`:
   - Si el usuario existe: Actualiza `google_id` si no existe
   - Si no existe: Crea nuevo usuario con:
     - `name`: Nombre de Google
     - `email`: Email de Google
     - `password`: Hash de contraseña aleatoria (ya que es OAuth)
     - `google_id`: ID de Google
     - `email_verified_at`: Fecha actual (Google ya verificó el email)
     - `pregunta_secreta`: (Opcional) Puede ser null o vacío
6. Backend genera token y redirige a: `https://tu-frontend.netlify.app/auth/callback?token=...`

### 4. Login con Facebook

**Flujo:**
Similar al de Google, pero usando `facebook_id` en lugar de `google_id`.

### 5. Recuperación de Contraseña

**Paso 1: Verificar Email**
- Frontend envía: `{ "email": "juan@example.com" }`
- Backend busca usuario en la colección `users`
- Backend lee `pregunta_secreta` (string JSON) y lo parsea
- Backend devuelve: `{ "pregunta_secreta": "¿Cuál es el nombre de tu primera mascota?" }`

**Paso 2: Verificar Respuesta**
- Frontend envía: `{ "email": "juan@example.com", "respuesta_secreta": "Doki" }`
- Backend busca usuario y parsea `pregunta_secreta`
- Backend compara `respuesta_secreta` del JSON con la respuesta enviada
- Si coincide, devuelve: `{ "verified": true }`

**Paso 3: Actualizar Contraseña**
- Frontend envía:
```json
{
  "email": "juan@example.com",
  "new_password": "nuevaPassword123",
  "new_password_confirmation": "nuevaPassword123",
  "respuesta_secreta": "Doki"
}
```
- Backend verifica nuevamente la respuesta secreta
- Backend actualiza el hash de `password` en la colección `users`

## Notas Importantes

### Parseo de `pregunta_secreta`

El campo `pregunta_secreta` está guardado como string JSON, por lo que el backend debe:

**Al guardar:**
```php
$preguntaSecreta = json_encode([
    'pregunta' => $request->pregunta_secreta,
    'respuesta' => $request->respuesta_secreta
]);
```

**Al leer:**
```php
$preguntaSecreta = json_decode($user->pregunta_secreta, true);
$pregunta = $preguntaSecreta['pregunta'];
$respuesta = $preguntaSecreta['respuesta'];
```

### Seguridad

- **Nunca devolver** `password` ni `respuesta_secreta` en las respuestas de la API
- **Siempre hashear** las contraseñas con bcrypt antes de guardarlas
- **Validar** que las respuestas sean case-insensitive (mayúsculas/minúsculas no importan)
- **Limpiar** espacios en blanco al inicio y final de las respuestas

### OAuth (Google/Facebook)

- Los usuarios de OAuth pueden no tener `pregunta_secreta` configurada
- Si un usuario de OAuth intenta recuperar contraseña, el backend debe devolver un error apropiado
- Los usuarios de OAuth pueden tener `password` vacío o aleatorio (ya que no lo usan)

## Verificación

Para verificar que todo funciona correctamente:

1. **Registro:**
   - Registra un nuevo usuario
   - Verifica en MongoDB que `pregunta_secreta` esté guardado como string JSON
   - Verifica que `password` esté hasheado

2. **Login:**
   - Intenta hacer login con el usuario registrado
   - Verifica que se genere un token
   - Verifica que no se devuelva `password` ni `respuesta_secreta`

3. **OAuth:**
   - Intenta hacer login con Google/Facebook
   - Verifica que se cree/actualice el usuario en MongoDB
   - Verifica que `google_id` o `facebook_id` esté guardado

4. **Recuperación de Contraseña:**
   - Intenta recuperar contraseña
   - Verifica que se muestre la pregunta correcta
   - Verifica que se pueda actualizar la contraseña con la respuesta correcta

