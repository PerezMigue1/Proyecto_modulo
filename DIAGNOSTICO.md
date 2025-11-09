# 🔍 Diagnóstico de Problemas de Autenticación

## 📋 Checklist de Verificación

### 1. **Verificar Variables de Entorno**

#### En Netlify:
1. Ve a **Site settings** → **Environment variables**
2. Verifica que exista:
   - `VITE_API_URL` = `https://backend-equipo.onrender.com/api`
   - `VITE_FRONTEND_URL` = `https://tu-frontend.netlify.app` (tu URL de Netlify)

#### En Desarrollo Local:
Crea un archivo `.env` en la raíz del proyecto:
```env
VITE_API_URL=http://localhost:8000/api
VITE_FRONTEND_URL=http://localhost:3000
```

### 2. **Verificar Backend en Render**

1. Verifica que el backend esté funcionando:
   - URL: `https://backend-equipo.onrender.com`
   - Debe responder con código 200 en la ruta `/api/preguntas-secretas`

2. Verifica que las rutas estén correctas:
   - `POST /api/login`
   - `POST /api/register`
   - `GET /api/user` (requiere autenticación)
   - `GET /auth/google` (OAuth)
   - `GET /auth/facebook` (OAuth)

3. Verifica CORS:
   - El backend debe permitir requests desde tu dominio de Netlify
   - Verifica `config/cors.php` en el backend
   - `CORS_ALLOWED_ORIGINS` debe incluir tu URL de Netlify

### 3. **Verificar en el Navegador (F12 → Console)**

Cuando intentes hacer login o registro, deberías ver logs como:

```
🔗 API URL: https://backend-equipo.onrender.com/api
📤 Request: POST /login {email: "...", password: "..."}
📥 Response: 200 /login {user: {...}, token: "..."}
✅ Login exitoso
```

Si ves errores, revisa:
- **Network Error**: El backend no está respondiendo
- **401 Unauthorized**: Credenciales incorrectas o token inválido
- **404 Not Found**: La ruta no existe en el backend
- **500 Internal Server Error**: Error en el backend
- **CORS Error**: El backend no permite requests desde tu dominio

### 4. **Verificar OAuth (Google/Facebook)**

#### URLs de OAuth:
- Google: `https://backend-equipo.onrender.com/auth/google`
- Facebook: `https://backend-equipo.onrender.com/auth/facebook`

#### Verificar en Google Cloud Console:
1. Ve a **APIs & Services** → **Credentials**
2. Verifica que la **Authorized redirect URI** incluya:
   - `https://backend-equipo.onrender.com/auth/google/callback`

#### Verificar en Facebook Developers:
1. Ve a **Settings** → **Basic**
2. Verifica que **App Domains** incluya:
   - `backend-equipo.onrender.com`
3. Verifica que **Valid OAuth Redirect URIs** incluya:
   - `https://backend-equipo.onrender.com/auth/facebook/callback`

### 5. **Problemas Comunes y Soluciones**

#### Problema: "No se pudo conectar con el servidor"
**Solución:**
- Verifica que el backend esté funcionando en Render
- Verifica que `VITE_API_URL` esté correctamente configurada
- Verifica tu conexión a internet
- Verifica que no haya un firewall bloqueando las peticiones

#### Problema: "Error 401: Unauthorized"
**Solución:**
- Verifica que las credenciales sean correctas
- Verifica que el token se esté guardando correctamente en `localStorage`
- Verifica que el backend esté generando tokens correctamente

#### Problema: "Error 404: Not Found"
**Solución:**
- Verifica que las rutas en el backend estén correctas
- Verifica que el backend tenga las rutas `/api/login`, `/api/register`, etc.
- Verifica que `VITE_API_URL` termine con `/api`

#### Problema: "CORS Error"
**Solución:**
- Verifica que `CORS_ALLOWED_ORIGINS` en el backend incluya tu URL de Netlify
- Verifica que el backend tenga CORS habilitado
- Verifica que `withCredentials: true` esté en la configuración de axios

#### Problema: OAuth no redirige después de autenticar
**Solución:**
- Verifica que `FRONTEND_URL` en el backend sea correcta
- Verifica que las URLs de callback en Google/Facebook sean correctas
- Verifica que el backend esté redirigiendo a `FRONTEND_URL/auth/callback?token=...`

### 6. **Pruebas Manuales**

#### Probar API directamente:
```bash
# Probar login
curl -X POST https://backend-equipo.onrender.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"password"}'

# Probar registro
curl -X POST https://backend-equipo.onrender.com/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@test.com","password":"password","password_confirmation":"password","pregunta_secreta":"test","respuesta_secreta":"test"}'

# Probar OAuth (abrir en navegador)
https://backend-equipo.onrender.com/auth/google
```

### 7. **Logs de Debug**

Los logs de debug están habilitados en desarrollo. Para verlos:
1. Abre la consola del navegador (F12)
2. Ve a la pestaña **Console**
3. Intenta hacer login o registro
4. Revisa los logs que aparecen

Los logs incluyen:
- 🔗 URLs de la API
- 📤 Requests enviados
- 📥 Responses recibidos
- ❌ Errores encontrados
- ✅ Operaciones exitosas

### 8. **Verificar Estado de Autenticación**

En la consola del navegador, ejecuta:
```javascript
// Verificar token
console.log('Token:', localStorage.getItem('token'))

// Verificar URL de API
console.log('API URL:', import.meta.env.VITE_API_URL)

// Verificar store de autenticación
// (necesitas acceder desde Vue DevTools o desde el código)
```

## 🚨 Si Nada Funciona

1. **Verifica los logs del backend en Render**
   - Ve a **Logs** en el dashboard de Render
   - Revisa los errores que aparecen cuando intentas hacer login/registro

2. **Verifica los logs del frontend en Netlify**
   - Ve a **Deploys** → **Latest deploy** → **Functions Logs**
   - Revisa los errores durante el build

3. **Verifica la configuración de CORS en el backend**
   - Asegúrate de que `CORS_ALLOWED_ORIGINS` incluya tu URL de Netlify
   - Asegúrate de que `withCredentials: true` esté configurado correctamente

4. **Verifica las variables de entorno**
   - Asegúrate de que todas las variables estén configuradas correctamente
   - Asegúrate de que los valores sean correctos (sin espacios, sin comillas adicionales)

5. **Prueba con una herramienta como Postman**
   - Prueba las rutas del backend directamente
   - Verifica que las respuestas sean correctas

## 📞 Contacto

Si después de seguir estos pasos el problema persiste, proporciona:
1. Logs de la consola del navegador (F12 → Console)
2. Logs del backend en Render
3. Screenshots de los errores
4. Configuración de variables de entorno (sin valores sensibles)

