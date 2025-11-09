# 🔌 APIs del Frontend - Resumen Completo

## 📋 Rutas de API Utilizadas

### 1. **Autenticación**

#### Login
- **Endpoint:** `POST /api/login`
- **Archivo:** `src/stores/auth.js` → `login()`
- **Componente:** `src/views/Login.vue`
- **Datos enviados:**
  ```json
  {
    "email": "usuario@example.com",
    "password": "password123"
  }
  ```
- **Respuesta esperada:**
  ```json
  {
    "user": { ... },
    "token": "1|...",
    "message": "Login exitoso"
  }
  ```

#### Registro
- **Endpoint:** `POST /api/register`
- **Archivo:** `src/stores/auth.js` → `register()`
- **Componente:** `src/views/Register.vue`
- **Datos enviados:**
  ```json
  {
    "name": "Juan Pérez",
    "email": "usuario@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "pregunta_secreta": "¿Cuál es tu mascota?",
    "respuesta_secreta": "Perro"
  }
  ```
- **Respuesta esperada:**
  ```json
  {
    "user": { ... },
    "token": "1|...",
    "message": "Registro exitoso"
  }
  ```

#### Obtener Usuario
- **Endpoint:** `GET /api/user`
- **Archivo:** `src/stores/auth.js` → `fetchUser()`
- **Componentes:** `src/views/Dashboard.vue`, `src/router/index.js`
- **Headers:** `Authorization: Bearer {token}`
- **Respuesta esperada:**
  ```json
  {
    "_id": "...",
    "name": "Juan Pérez",
    "email": "usuario@example.com",
    ...
  }
  ```

#### Logout
- **Endpoint:** `POST /api/logout`
- **Archivo:** `src/stores/auth.js` → `logout()`
- **Componente:** `src/views/Dashboard.vue`
- **Headers:** `Authorization: Bearer {token}`
- **Respuesta esperada:**
  ```json
  {
    "message": "Logged out successfully"
  }
  ```

### 2. **Preguntas Secretas**

#### Obtener Preguntas Secretas
- **Endpoint:** `GET /api/preguntas-secretas`
- **Archivo:** `src/services/secretQuestions.js` → `getSecretQuestions()`
- **Componente:** `src/views/Register.vue`
- **Respuesta esperada:**
  ```json
  {
    "preguntas": [
      {
        "_id": "...",
        "pregunta": "¿Cuál es tu mascota?"
      },
      ...
    ],
    "total": 10
  }
  ```

### 3. **Recuperación de Contraseña**

#### Verificar Email
- **Endpoint:** `POST /api/password/verify-email`
- **Archivo:** `src/services/passwordRecovery.js` → `verifyEmail()`
- **Componente:** `src/views/ForgotPassword.vue`
- **Datos enviados:**
  ```json
  {
    "email": "usuario@example.com"
  }
  ```
- **Respuesta esperada:**
  ```json
  {
    "email": "usuario@example.com",
    "pregunta_secreta": "¿Cuál es tu mascota?"
  }
  ```

#### Verificar Respuesta
- **Endpoint:** `POST /api/password/verify-answer`
- **Archivo:** `src/services/passwordRecovery.js` → `verifyAnswer()`
- **Componente:** `src/views/ForgotPassword.vue`
- **Datos enviados:**
  ```json
  {
    "email": "usuario@example.com",
    "respuesta_secreta": "Perro"
  }
  ```
- **Respuesta esperada:**
  ```json
  {
    "message": "Respuesta correcta. Puede proceder a cambiar la contraseña.",
    "verified": true
  }
  ```

#### Actualizar Contraseña
- **Endpoint:** `POST /api/password/update`
- **Archivo:** `src/services/passwordRecovery.js` → `updatePassword()`
- **Componente:** `src/views/ForgotPassword.vue`
- **Datos enviados:**
  ```json
  {
    "email": "usuario@example.com",
    "new_password": "nuevaPassword123",
    "new_password_confirmation": "nuevaPassword123",
    "respuesta_secreta": "Perro"
  }
  ```
- **Respuesta esperada:**
  ```json
  {
    "message": "Contraseña actualizada exitosamente."
  }
  ```

### 4. **OAuth (Google/Facebook)**

#### Google OAuth
- **Endpoint:** `GET /auth/google` (redirección)
- **Archivo:** `src/views/Login.vue`
- **URL completa:** `https://backend-equipo.onrender.com/auth/google`
- **Callback:** `GET /auth/google/callback`
- **Redirección final:** `{FRONTEND_URL}/auth/callback?token={token}&provider=google`

#### Facebook OAuth
- **Endpoint:** `GET /auth/facebook` (redirección)
- **Archivo:** `src/views/Login.vue`
- **URL completa:** `https://backend-equipo.onrender.com/auth/facebook`
- **Callback:** `GET /auth/facebook/callback`
- **Redirección final:** `{FRONTEND_URL}/auth/callback?token={token}&provider=facebook`

#### Callback de OAuth
- **Componente:** `src/views/AuthCallback.vue`
- **Ruta:** `/auth/callback`
- **Query params:** `?token={token}&provider={google|facebook}`
- **Proceso:**
  1. Obtener token de la URL
  2. Guardar token en localStorage
  3. Obtener usuario con `fetchUser()`
  4. Redirigir a `/dashboard`

## 🔧 Configuración

### Variables de Entorno

#### En Netlify:
```env
VITE_API_URL=https://backend-equipo.onrender.com/api
VITE_FRONTEND_URL=https://tu-frontend.netlify.app
```

#### En Desarrollo Local:
Crear archivo `.env.local`:
```env
VITE_API_URL=http://localhost:8000/api
VITE_FRONTEND_URL=http://localhost:3000
```

### URL Base de la API

**Archivo:** `src/services/api.js`

```javascript
const API_URL = import.meta.env.VITE_API_URL || 
  (import.meta.env.PROD 
    ? 'https://backend-equipo.onrender.com/api' 
    : 'http://localhost:8000/api')
```

### Headers

Todas las peticiones incluyen:
```javascript
{
  'Content-Type': 'application/json',
  'Accept': 'application/json',
  'Authorization': 'Bearer {token}' // Solo si hay token
}
```

## 🔐 Autenticación

### Token Storage
- **Localización:** `localStorage.getItem('token')`
- **Nombre:** `token`
- **Uso:** Se incluye automáticamente en todas las peticiones autenticadas

### Interceptor de Request
**Archivo:** `src/services/api.js`

```javascript
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})
```

### Interceptor de Response
**Archivo:** `src/services/api.js`

- **401 Unauthorized:** Limpia token y redirige a `/login`
- **422 Validation Error:** Muestra errores de validación
- **Otros errores:** Muestra mensaje de error

## 📱 Componentes y sus APIs

### Login (`src/views/Login.vue`)
- ✅ `POST /api/login` - Login con email/password
- ✅ `GET /auth/google` - OAuth con Google
- ✅ `GET /auth/facebook` - OAuth con Facebook

### Register (`src/views/Register.vue`)
- ✅ `GET /api/preguntas-secretas` - Obtener preguntas secretas
- ✅ `POST /api/register` - Registrar nuevo usuario

### ForgotPassword (`src/views/ForgotPassword.vue`)
- ✅ `POST /api/password/verify-email` - Verificar email
- ✅ `POST /api/password/verify-answer` - Verificar respuesta secreta
- ✅ `POST /api/password/update` - Actualizar contraseña

### Dashboard (`src/views/Dashboard.vue`)
- ✅ `GET /api/user` - Obtener información del usuario
- ✅ `POST /api/logout` - Cerrar sesión

### AuthCallback (`src/views/AuthCallback.vue`)
- ✅ `GET /api/user` - Obtener usuario después de OAuth

## ✅ Checklist de Verificación

### Frontend
- [x] Configuración de API URL correcta
- [x] Interceptores de request/response configurados
- [x] Manejo de tokens en localStorage
- [x] Manejo de errores implementado
- [x] URLs de OAuth correctas
- [x] Rutas del router configuradas

### Backend (verificar en tu proyecto)
- [ ] Rutas de API configuradas correctamente
- [ ] CORS configurado para Netlify
- [ ] Validaciones implementadas
- [ ] Mensajes de error en español
- [ ] OAuth (Google/Facebook) configurado
- [ ] Variables de entorno configuradas

## 🚨 Errores Comunes

### Error 422 (Validación)
- **Causa:** Datos inválidos o faltantes
- **Solución:** Verificar que todos los campos requeridos estén presentes
- **Mensaje:** Se muestra en la UI con los errores específicos

### Error 401 (No Autorizado)
- **Causa:** Token inválido o expirado
- **Solución:** El interceptor limpia el token y redirige a login
- **Mensaje:** Se redirige automáticamente

### Error de Red (Network Error)
- **Causa:** Backend no disponible o CORS mal configurado
- **Solución:** Verificar que el backend esté funcionando y CORS esté configurado
- **Mensaje:** "No se pudo conectar con el servidor"

### Error de CORS
- **Causa:** Backend no permite requests desde el frontend
- **Solución:** Verificar `CORS_ALLOWED_ORIGINS` en el backend
- **Mensaje:** Error en la consola del navegador

## 📝 Notas

1. **Todas las APIs están configuradas correctamente en el frontend**
2. **El backend debe tener los cambios aplicados según `CAMBIOS_BACKEND.md`**
3. **Las variables de entorno deben estar configuradas en Netlify**
4. **CORS debe estar configurado en el backend para permitir requests desde Netlify**
5. **Los tokens se guardan en localStorage y se incluyen automáticamente en las peticiones**

## 🔍 Debugging

### Ver logs en la consola:
```javascript
// Ver URL de la API
console.log('API URL:', import.meta.env.VITE_API_URL)

// Ver token
console.log('Token:', localStorage.getItem('token'))

// Ver requests (en desarrollo)
// Los logs aparecen automáticamente en la consola
```

### Probar APIs directamente:
```bash
# Login
curl -X POST https://backend-equipo.onrender.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"password123"}'

# Preguntas secretas
curl https://backend-equipo.onrender.com/api/preguntas-secretas

# OAuth (abrir en navegador)
https://backend-equipo.onrender.com/auth/google
```

