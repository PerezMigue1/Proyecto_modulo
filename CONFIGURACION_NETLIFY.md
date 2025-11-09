# ⚙️ Configuración de Variables de Entorno en Netlify

## 🔧 Variables Requeridas

### 1. **VITE_API_URL** (Requerida)
- **Valor**: `https://backend-equipo.onrender.com/api`
- **Descripción**: URL del backend API en Render
- **Cómo configurar**:
  1. Ve a tu sitio en Netlify Dashboard
  2. Ve a **Site settings** → **Environment variables**
  3. Haz clic en **Add variable**
  4. Nombre: `VITE_API_URL`
  5. Valor: `https://backend-equipo.onrender.com/api`
  6. Haz clic en **Save**

### 2. **VITE_FRONTEND_URL** (Opcional, recomendada)
- **Valor**: `https://tu-frontend.netlify.app` (reemplaza con tu URL de Netlify)
- **Descripción**: URL del frontend para OAuth callbacks
- **Cómo configurar**:
  1. Ve a **Site settings** → **Environment variables**
  2. Haz clic en **Add variable**
  3. Nombre: `VITE_FRONTEND_URL`
  4. Valor: Tu URL de Netlify (ej: `https://modulo-usuario-frontend.netlify.app`)
  5. Haz clic en **Save**

## 📝 Notas Importantes

### Fallback Automático
- Si `VITE_API_URL` no está configurada en Netlify, el código usará automáticamente `https://backend-equipo.onrender.com/api` en producción
- Sin embargo, **es recomendable configurarla explícitamente** para evitar problemas

### Desarrollo Local
- Para desarrollo local, crea un archivo `.env.local` en la raíz del proyecto:
  ```env
  VITE_API_URL=http://localhost:8000/api
  VITE_FRONTEND_URL=http://localhost:3000
  ```
- Este archivo no se sube a Git (está en `.gitignore`)

### Después de Configurar
1. Después de agregar las variables de entorno, **redespliega** tu sitio en Netlify
2. Ve a **Deploys** → **Trigger deploy** → **Clear cache and deploy site**
3. Espera a que el deploy termine
4. Verifica que la aplicación funcione correctamente

## 🔍 Verificar Configuración

### En el Navegador (F12 → Console)
Después de desplegar, abre la consola del navegador y deberías ver:
```
🔗 API URL: https://backend-equipo.onrender.com/api
🔗 Environment: production
🔗 VITE_API_URL: https://backend-equipo.onrender.com/api
```

### Probar la Conexión
1. Intenta hacer login o registro
2. Verifica que las peticiones se envíen a `https://backend-equipo.onrender.com/api`
3. Revisa la consola del navegador para ver los logs de las peticiones

## 🚨 Problemas Comunes

### Problema: "No se pudo conectar con el servidor"
**Solución:**
- Verifica que `VITE_API_URL` esté configurada correctamente
- Verifica que el backend esté funcionando en Render
- Verifica que la URL no tenga espacios o caracteres especiales

### Problema: "CORS Error"
**Solución:**
- Verifica que el backend tenga configurado CORS para permitir tu dominio de Netlify
- Verifica que `CORS_ALLOWED_ORIGINS` en el backend incluya tu URL de Netlify

### Problema: OAuth no funciona
**Solución:**
- Verifica que `VITE_FRONTEND_URL` esté configurada correctamente
- Verifica que el backend tenga configurado `FRONTEND_URL` con tu URL de Netlify
- Verifica que las URLs de callback en Google/Facebook sean correctas

## 📞 Soporte

Si después de configurar las variables de entorno el problema persiste:
1. Verifica los logs del navegador (F12 → Console)
2. Verifica los logs del backend en Render
3. Verifica que las variables de entorno estén correctamente configuradas en Netlify

