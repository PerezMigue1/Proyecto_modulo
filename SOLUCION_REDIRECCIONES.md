# 🔧 Solución: Problemas de Redireccionamiento

## 🔍 Problemas Identificados

### 1. **URLs de OAuth mal construidas**
- Las URLs pueden no construirse correctamente si `VITE_API_URL` tiene formato diferente
- Necesita manejar casos donde la URL termine con `/api` o `/api/`

### 2. **Autenticación no se verifica correctamente**
- `isAuthenticated` depende de `user.value`, pero `user` puede ser `null` incluso con token
- El router guard puede bloquear redireccionamientos válidos

### 3. **Callback de OAuth no maneja errores**
- No maneja correctamente errores en la URL de callback
- No verifica si el usuario se cargó correctamente

### 4. **Router guard demasiado estricto**
- Bloquea rutas incluso cuando hay token válido
- No intenta cargar el usuario si hay token pero no usuario

## ✅ Soluciones Aplicadas

### 1. **Construcción de URLs de OAuth**

```javascript
// Antes
const apiBaseUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'
const googleLoginUrl = computed(() => `${apiBaseUrl.replace('/api', '')}/auth/google`)

// Después
const getBackendBaseUrl = () => {
  const apiUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'
  return apiUrl.replace(/\/api\/?$/, '')
}
const googleLoginUrl = computed(() => `${getBackendBaseUrl()}/auth/google`)
```

### 2. **Mejora en el Store de Autenticación**

```javascript
// Ahora considera autenticado si hay token
const isAuthenticated = computed(() => {
  return !!token.value
})

// Intenta cargar usuario automáticamente si hay token
if (token.value) {
  fetchUser().catch(() => {
    clearAuth()
  })
}
```

### 3. **Router Guard Mejorado**

```javascript
router.beforeEach(async (to, from, next) => {
  const token = authStore.token

  if (to.meta.requiresAuth) {
    if (!token) {
      next('/login')
      return
    }
    
    // Intentar cargar usuario si no existe
    if (!authStore.user) {
      try {
        await authStore.fetchUser()
      } catch (error) {
        authStore.clearAuth()
        next('/login')
        return
      }
    }
    
    next()
  } else if (to.meta.requiresGuest) {
    if (token) {
      next('/dashboard')
      return
    }
    next()
  } else {
    next()
  }
})
```

### 4. **Callback de OAuth Mejorado**

```javascript
onMounted(async () => {
  const token = route.query.token
  const error = route.query.error

  if (error) {
    router.push(`/login?error=${encodeURIComponent(error)}`)
    return
  }

  if (token) {
    try {
      authStore.setAuth(null, token)
      await authStore.fetchUser()
      router.push('/dashboard')
    } catch (err) {
      authStore.clearAuth()
      router.push('/login?error=Error al autenticar')
    }
  } else {
    router.push('/login?error=No se recibió el token')
  }
})
```

## 🔍 Verificación

### 1. **URLs de OAuth**

Verifica en el navegador (F12 → Console):
```javascript
// Debe mostrar la URL correcta del backend
console.log('Google URL:', document.querySelector('.btn-google').href)
console.log('Facebook URL:', document.querySelector('.btn-facebook').href)
```

### 2. **Variables de Entorno**

Verifica que `VITE_API_URL` esté configurada:
```javascript
console.log('API URL:', import.meta.env.VITE_API_URL)
```

### 3. **Token**

Verifica que el token se guarde correctamente:
```javascript
console.log('Token:', localStorage.getItem('token'))
```

### 4. **Router**

Verifica que las rutas funcionen:
- `/login` - Debe mostrar login
- `/register` - Debe mostrar registro
- `/dashboard` - Debe redirigir a login si no hay token
- `/auth/callback` - Debe manejar el callback de OAuth

## 🐛 Troubleshooting

### Redireccionamientos no funcionan

1. **Verifica las variables de entorno en Netlify**:
   ```
   VITE_API_URL=https://backend-equipo.onrender.com/api
   VITE_FRONTEND_URL=https://tu-frontend.netlify.app
   ```

2. **Verifica que el backend esté corriendo**:
   ```
   https://backend-equipo.onrender.com/api/preguntas-secretas
   ```

3. **Verifica CORS en el backend**:
   - Debe incluir la URL de Netlify
   - Debe permitir credenciales

4. **Verifica las URLs de OAuth**:
   - Google: `https://backend-equipo.onrender.com/auth/google/callback`
   - Facebook: `https://backend-equipo.onrender.com/auth/facebook/callback`

### OAuth no redirige

1. **Verifica que el backend redirija correctamente**:
   - Debe redirigir a: `https://tu-frontend.netlify.app/auth/callback?token=xxx`

2. **Verifica `FRONTEND_URL` en el backend**:
   ```
   FRONTEND_URL=https://tu-frontend.netlify.app
   ```

3. **Verifica las URLs en Google/Facebook**:
   - Deben apuntar al backend, no al frontend

### Router no redirige

1. **Verifica `netlify.toml`**:
   ```toml
   [[redirects]]
   from = "/*"
   to = "/index.html"
   status = 200
   ```

2. **Verifica que el build incluya `index.html`**:
   - Debe estar en `dist/index.html`

3. **Verifica la consola del navegador**:
   - Busca errores de JavaScript
   - Busca errores de red

## ✅ Checklist

- [ ] URLs de OAuth se construyen correctamente
- [ ] Token se guarda en `localStorage`
- [ ] Usuario se carga después de login
- [ ] Router guard funciona correctamente
- [ ] Callback de OAuth maneja errores
- [ ] Redireccionamientos después de login/registro funcionan
- [ ] Logout redirige correctamente
- [ ] Variables de entorno están configuradas
- [ ] CORS está configurado en el backend
- [ ] URLs de OAuth están en Google/Facebook

## 📝 Notas

- Las URLs de OAuth apuntan al **backend**, no al frontend
- El backend redirige al frontend después de OAuth exitoso
- El frontend maneja el token y redirige al dashboard
- El router guard protege las rutas que requieren autenticación

