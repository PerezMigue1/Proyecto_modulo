# 🚀 Despliegue Paso a Paso: Netlify + Render

Guía paso a paso para desplegar el frontend en Netlify y el backend en Render.

---

## Paso 1: Desplegar Backend en Render

### 1.1. Subir Backend a GitHub

```bash
cd tu-proyecto-backend
git add .
git commit -m "Backend listo para Render"
git push origin main
```

### 1.2. Crear Servicio en Render

1. Ve a [Render.com](https://render.com)
2. Click en **"New +"** → **"Web Service"**
3. Conecta tu repositorio de GitHub
4. Configura:
   - **Name**: `modulo-usuario-backend`
   - **Environment**: `Docker`
   - **Region**: `Oregon (US West)` (o la más cercana)
   - **Branch**: `main`

### 1.3. Variables de Entorno (Temporal)

Agrega estas variables (actualizarás `FRONTEND_URL` después):

```env
APP_NAME="Módulo Usuario API"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:... (genera con: php artisan key:generate)
APP_URL=https://backend-equipo.onrender.com

DB_CONNECTION=mongodb
MONGODB_URI=tu_uri_mongodb
MONGODB_DATABASE=equipo

GOOGLE_CLIENT_ID=tu_id
GOOGLE_CLIENT_SECRET=tu_secret
GOOGLE_REDIRECT_URI=https://backend-equipo.onrender.com/auth/google/callback

FACEBOOK_CLIENT_ID=tu_id
FACEBOOK_CLIENT_SECRET=tu_secret
FACEBOOK_REDIRECT_URI=https://backend-equipo.onrender.com/auth/facebook/callback

FRONTEND_URL=http://localhost:3000
CORS_ALLOWED_ORIGINS=http://localhost:3000
```

### 1.4. Desplegar

- Click en **"Create Web Service"**
- Espera a que termine (5-10 minutos)
- **Copia la URL**: `https://backend-equipo.onrender.com`

### 1.5. Verificar Backend

Accede a: `https://backend-equipo.onrender.com/api/preguntas-secretas`

Debes ver JSON con las preguntas secretas.

---

## Paso 2: Desplegar Frontend en Netlify

### 2.1. Subir Frontend a GitHub

```bash
cd equipo
git add .
git commit -m "Frontend listo para Netlify"
git push origin main
```

### 2.2. Crear Sitio en Netlify

1. Ve a [Netlify](https://netlify.com)
2. Click en **"Add new site"** → **"Import an existing project"**
3. Selecciona **"GitHub"** y autoriza Netlify
4. Selecciona tu repositorio `Proyecto_modulo`
5. Configura:
   - **Branch to deploy**: `main`
   - **Build command**: `npm install && npm run build`
   - **Publish directory**: `dist`

### 2.3. Variables de Entorno

Agrega estas variables:

```env
VITE_API_URL=https://backend-equipo.onrender.com/api
VITE_FRONTEND_URL=https://tu-frontend.netlify.app
```

**Nota**: La URL `tu-frontend.netlify.app` la obtendrás después del primer despliegue.

### 2.4. Desplegar

- Click en **"Deploy site"**
- Espera a que termine el build (2-5 minutos)
- **Copia la URL**: `https://tu-frontend.netlify.app` (o el nombre que Netlify te asigne)

### 2.5. Cambiar Nombre del Sitio (Opcional)

1. Ve a **Site settings** → **Change site name**
2. Cambia a un nombre más amigable (ej: `modulo-usuario`)
3. La nueva URL será: `https://modulo-usuario.netlify.app`

---

## Paso 3: Actualizar Backend con URL del Frontend

### 3.1. Actualizar Variables de Entorno en Render

1. Ve a Render Dashboard → Tu servicio backend
2. Ve a **Environment**
3. Actualiza estas variables:

```env
FRONTEND_URL=https://tu-frontend.netlify.app
CORS_ALLOWED_ORIGINS=https://tu-frontend.netlify.app,http://localhost:3000
```

4. Click en **"Save Changes"**
5. Render reiniciará automáticamente

### 3.2. Verificar CORS

Accede a: `https://modulo-usuario-backend.onrender.com/api/preguntas-secretas`

Debe seguir funcionando.

---

## Paso 4: Configurar OAuth

### 4.1. Google OAuth

1. Ve a [Google Cloud Console](https://console.cloud.google.com)
2. Selecciona tu proyecto
3. Ve a **APIs & Services** → **Credentials**
4. Edita tu OAuth 2.0 Client
5. Agrega a **Authorized redirect URIs**:
   ```
   https://backend-equipo.onrender.com/auth/google/callback
   ```
6. Click en **Save**

### 4.2. Facebook OAuth

1. Ve a [Facebook Developers](https://developers.facebook.com)
2. Selecciona tu app
3. Ve a **Settings** → **Basic**
4. Agrega a **App Domains**:
   ```
   backend-equipo.onrender.com
   tu-frontend.netlify.app
   ```
5. Ve a **Facebook Login** → **Settings**
6. Agrega a **Valid OAuth Redirect URIs**:
   ```
   https://backend-equipo.onrender.com/auth/facebook/callback
   ```
7. Click en **Save Changes**

---

## Paso 5: Verificar Todo

### 5.1. Verificar Backend

1. ✅ Accede a: `https://backend-equipo.onrender.com/api/preguntas-secretas`
2. ✅ Debes ver JSON con las preguntas secretas

### 5.2. Verificar Frontend

1. ✅ Accede a: `https://tu-frontend.netlify.app`
2. ✅ Debes ver la página de login
3. ✅ Prueba hacer login con email/password
4. ✅ Verifica la consola del navegador (F12) para errores

### 5.3. Verificar OAuth

1. ✅ Prueba login con Google
2. ✅ Prueba login con Facebook
3. ✅ Verifica que las redirecciones funcionen

---

## 🐛 Troubleshooting

### Backend no responde

- Revisa los logs en Render Dashboard
- Verifica que las variables de entorno estén correctas
- Verifica que MongoDB esté accesible

### Frontend no se conecta al backend

- Verifica `VITE_API_URL` en Netlify
- Verifica CORS en el backend
- Revisa la consola del navegador (F12)

### CORS Error

Actualiza en Render:
```env
CORS_ALLOWED_ORIGINS=https://tu-frontend.netlify.app,http://localhost:3000
```

### OAuth no funciona

- Verifica las URLs de redirect en Google/Facebook
- Verifica que las URLs en el backend sean correctas

---

## ✅ Checklist Final

- [ ] Backend desplegado en Render
- [ ] Frontend desplegado en Netlify
- [ ] Variables de entorno configuradas
- [ ] CORS configurado
- [ ] OAuth (Google) configurado
- [ ] OAuth (Facebook) configurado
- [ ] Todo funciona correctamente

---

¡Listo! Tu aplicación está desplegada. 🎉

**URLs:**
- Frontend: `https://tu-frontend.netlify.app`
- Backend: `https://backend-equipo.onrender.com`

