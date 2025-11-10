# Actualización del Frontend para JWT

## Buenas Noticias 🎉

**El frontend NO necesita cambios significativos** porque JWT funciona de la misma manera que Sanctum en el lado del cliente:

- ✅ El token se envía en el header `Authorization: Bearer {token}`
- ✅ El token se almacena en `localStorage`
- ✅ La estructura de la respuesta es la misma

## Cambios Mínimos (Opcionales)

### 1. Manejar Token de Refresco (Opcional)

Si quieres implementar el refresco automático de tokens, puedes agregar esto:

```javascript
// src/services/api.js
import api from './api'

// Interceptor para refrescar token cuando expira
api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config

    // Si el error es 401 y no hemos intentado refrescar el token
    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true

      try {
        // Intentar refrescar el token
        const response = await api.post('/refresh')
        const { token } = response.data

        // Guardar el nuevo token
        localStorage.setItem('token', token)
        api.defaults.headers.common['Authorization'] = `Bearer ${token}`

        // Reintentar la petición original
        originalRequest.headers['Authorization'] = `Bearer ${token}`
        return api(originalRequest)
      } catch (refreshError) {
        // Si el refresh falla, redirigir al login
        localStorage.removeItem('token')
        window.location.href = '/login'
        return Promise.reject(refreshError)
      }
    }

    return Promise.reject(error)
  }
)
```

### 2. Verificar que el Token se Esté Enviando Correctamente

El código actual ya debería funcionar, pero verifica que:

```javascript
// src/services/api.js
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  }
)
```

### 3. Manejar Respuestas de JWT

JWT puede devolver un campo `token_type` en la respuesta. El código actual ya lo maneja correctamente, pero puedes verificar:

```javascript
// src/stores/auth.js
async function login(email, password) {
  const response = await api.post('/login', { email, password })
  
  // JWT puede devolver: { user, token, token_type: 'bearer' }
  // El código actual solo usa 'user' y 'token', lo cual es correcto
  if (response.data.user && response.data.token) {
    setAuth(response.data.user, response.data.token)
    return response.data
  }
}
```

## Estructura de Respuesta JWT

### Login
```json
{
  "user": {
    "id": "...",
    "name": "...",
    "email": "..."
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer"
}
```

### Registro
```json
{
  "message": "Usuario creado exitosamente",
  "user": {
    "id": "...",
    "name": "...",
    "email": "..."
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer"
}
```

## Verificación

Después de que el backend esté actualizado a JWT:

1. ✅ El login debería funcionar sin cambios
2. ✅ El registro debería funcionar sin cambios
3. ✅ OAuth (Google/Facebook) debería funcionar sin cambios
4. ✅ Las rutas protegidas deberían funcionar sin cambios
5. ✅ El token se almacena y se envía correctamente

## Si Hay Problemas

### Token no se envía

Verifica que el token se esté guardando correctamente:
```javascript
console.log('Token:', localStorage.getItem('token'))
```

### Token inválido o expirado

Verifica que el token esté en el formato correcto:
```javascript
const token = localStorage.getItem('token')
console.log('Token format:', token?.substring(0, 20) + '...')
```

### Error 401 en rutas protegidas

Verifica que el header de autorización se esté enviando:
```javascript
console.log('Authorization header:', api.defaults.headers.common['Authorization'])
```

## Conclusión

**No necesitas hacer cambios en el frontend** porque JWT funciona de la misma manera que Sanctum desde la perspectiva del cliente. El token se envía en el header `Authorization: Bearer {token}` y se almacena en `localStorage`, exactamente como lo hace Sanctum.

Los únicos cambios opcionales serían:
1. Agregar manejo de refresco de tokens (opcional)
2. Agregar manejo de tokens expirados (opcional)

Pero el código actual debería funcionar perfectamente con JWT.

