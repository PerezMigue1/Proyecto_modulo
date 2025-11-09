# 🔧 Solución de Problemas: No Registra, No Loguea, No Muestra Preguntas

## 🔍 Diagnóstico

Si no funciona el registro, login, o las preguntas secretas, verifica lo siguiente:

## ✅ Checklist de Verificación

### 1. **Backend Funcionando**
```bash
# Probar si el backend responde
curl https://backend-equipo.onrender.com/api/preguntas-secretas
```

**Resultado esperado:**
```json
{
  "preguntas": [...],
  "total": 10
}
```

### 2. **Variables de Entorno en Netlify**
- Ve a **Netlify Dashboard** → **Site settings** → **Environment variables**
- Verifica que exista:
  - `VITE_API_URL` = `https://backend-equipo.onrender.com/api`

### 3. **CORS en el Backend**
- Verifica que `CORS_ALLOWED_ORIGINS` en el backend incluya tu URL de Netlify
- Ejemplo: `https://modulo-usuario.netlify.app`

### 4. **Rutas del Backend**
Verifica que estas rutas existan en el backend:
- `POST /api/login`
- `POST /api/register`
- `GET /api/preguntas-secretas`
- `GET /api/user` (requiere autenticación)
- `POST /api/logout` (requiere autenticación)

## 🐛 Problemas Comunes

### Problema 1: No Muestra Preguntas Secretas

**Síntomas:**
- El dropdown de preguntas secretas está vacío
- No aparece ninguna pregunta en el registro

**Causas posibles:**
1. El backend no responde en `/api/preguntas-secretas`
2. La respuesta no tiene la estructura esperada
3. Error de CORS

**Solución:**
1. Abre la consola del navegador (F12)
2. Busca los logs:
   ```
   📋 Obteniendo preguntas secretas...
   ✅ Preguntas secretas recibidas: {...}
   ```
3. Si ves un error, verifica:
   - Que el backend esté funcionando
   - Que la ruta `/api/preguntas-secretas` exista
   - Que CORS esté configurado correctamente

### Problema 2: No Registra

**Síntomas:**
- Al intentar registrarse, aparece un error
- Error 422 (validación) o error de red

**Causas posibles:**
1. Email ya existe
2. Validación fallida en el backend
3. Error de conexión con el backend
4. CORS bloqueando la petición

**Solución:**
1. Abre la consola del navegador (F12)
2. Busca los logs:
   ```
   📝 Intentando registro con: {...}
   ❌ Register error: {...}
   ```
3. Revisa el error específico:
   - Si es 422: Revisa los errores de validación
   - Si es network error: Verifica que el backend esté funcionando
   - Si es CORS: Verifica la configuración de CORS en el backend

### Problema 3: No Loguea

**Síntomas:**
- Al intentar hacer login, aparece un error
- No redirige al dashboard

**Causas posibles:**
1. Credenciales incorrectas
2. Usuario no existe
3. Error de conexión con el backend
4. Error 401 (no autorizado)

**Solución:**
1. Abre la consola del navegador (F12)
2. Busca los logs:
   ```
   🔐 Intentando login con: {...}
   ❌ Login error: {...}
   ```
3. Revisa el error específico:
   - Si es 422: Credenciales incorrectas o usuario no existe
   - Si es network error: Verifica que el backend esté funcionando
   - Si es 401: Token inválido

## 🔍 Debugging en el Navegador

### Paso 1: Abrir Consola
1. Abre la aplicación en Netlify
2. Presiona F12 para abrir las herramientas de desarrollador
3. Ve a la pestaña **Console**

### Paso 2: Verificar URL de API
Busca estos logs al cargar la página:
```
🔗 API URL: https://backend-equipo.onrender.com/api
🔗 Environment: production
🔗 VITE_API_URL: https://backend-equipo.onrender.com/api
```

**Si la URL es incorrecta:**
- Verifica que `VITE_API_URL` esté configurada en Netlify
- O que el fallback esté funcionando correctamente

### Paso 3: Probar Peticiones
1. Intenta hacer login o registro
2. Revisa los logs en la consola:
   ```
   📤 Request: POST /login {...}
   📥 Response: 200 /login {...}
   ```
   O
   ```
   ❌ API Error: {...}
   ```

### Paso 4: Verificar Errores
Si hay errores, revisa:
- **Status code:** 200 (éxito), 422 (validación), 404 (no encontrado), 500 (error del servidor)
- **Mensaje de error:** Te dirá qué está fallando
- **URL:** Verifica que la URL sea correcta

## 🛠️ Soluciones Rápidas

### Solución 1: Verificar Backend
```bash
# Probar backend directamente
curl https://backend-equipo.onrender.com/api/preguntas-secretas

# Probar login
curl -X POST https://backend-equipo.onrender.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"password123"}'
```

### Solución 2: Verificar CORS
En el backend, verifica que `config/cors.php` tenga:
```php
'allowed_origins' => [
    'https://modulo-usuario.netlify.app',
    'https://tu-frontend.netlify.app',
    // ... otros dominios
],
```

### Solución 3: Verificar Variables de Entorno
En Netlify:
1. Ve a **Site settings** → **Environment variables**
2. Verifica que `VITE_API_URL` esté configurada
3. Verifica que el valor sea correcto: `https://backend-equipo.onrender.com/api`

### Solución 4: Limpiar Cache
1. Limpia el cache del navegador (Ctrl+Shift+Delete)
2. O abre en modo incógnito
3. Verifica que los cambios estén desplegados

## 📋 Verificación Paso a Paso

### 1. Verificar Backend
```bash
# Probar si el backend responde
curl https://backend-equipo.onrender.com/api/preguntas-secretas
```

**Si no responde:**
- Verifica que el backend esté desplegado en Render
- Verifica los logs del backend en Render
- Verifica que las rutas estén configuradas correctamente

### 2. Verificar Frontend
1. Abre la aplicación en Netlify
2. Abre la consola (F12)
3. Verifica que la URL de la API sea correcta
4. Intenta hacer una petición (login, registro, etc.)
5. Revisa los logs de la consola

### 3. Verificar CORS
1. Intenta hacer una petición desde el frontend
2. Si ves un error de CORS en la consola:
   - Verifica que `CORS_ALLOWED_ORIGINS` en el backend incluya tu URL de Netlify
   - Verifica que `withCredentials: true` esté en la configuración de axios

### 4. Verificar Rutas
Verifica que estas rutas existan en el backend:
- `GET /api/preguntas-secretas` → Debe devolver preguntas
- `POST /api/register` → Debe registrar usuario
- `POST /api/login` → Debe hacer login
- `GET /api/user` → Debe devolver usuario autenticado

## 🚨 Si Nada Funciona

1. **Verifica los logs del backend en Render**
   - Ve a **Logs** en el dashboard de Render
   - Revisa los errores que aparecen

2. **Verifica los logs del frontend en Netlify**
   - Ve a **Deploys** → **Latest deploy** → **Functions Logs**
   - Revisa los errores durante el build

3. **Verifica la consola del navegador**
   - Abre F12 → **Console**
   - Revisa todos los errores
   - Comparte los logs con el equipo

4. **Verifica la configuración**
   - Verifica que todas las variables de entorno estén configuradas
   - Verifica que CORS esté configurado correctamente
   - Verifica que las rutas existan en el backend

## 📞 Información para Debugging

Si necesitas ayuda, proporciona:
1. **Logs de la consola del navegador** (F12 → Console)
2. **Logs del backend en Render**
3. **Screenshots de los errores**
4. **Configuración de variables de entorno** (sin valores sensibles)
5. **URL del backend y frontend**

