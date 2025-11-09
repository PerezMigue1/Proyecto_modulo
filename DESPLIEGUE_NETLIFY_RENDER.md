# 🚀 Despliegue: Frontend (Netlify) + Backend (Render)

Guía específica para desplegar el frontend en Netlify y el backend en Render.

---

## 📡 Backend - Render.com

### 1. Preparar el Backend

Asegúrate de que tu backend (en el otro proyecto) tenga:
- `Dockerfile`
- `render.yaml`
- `start.sh`
- `.env.example`

### 2. Subir Backend a GitHub

```bash
cd tu-proyecto-backend
git init
git add .
git commit -m "Backend listo para desplegar"
git remote add origin tu-repositorio-backend
git push -u origin main
```

### 3. Crear Servicio en Render

1. Ve a [Render.com](https://render.com) y crea una cuenta
2. Click en **"New +"** → **"Web Service"**
3. Conecta tu repositorio de GitHub donde está el backend
4. Configura:
   - **Name**: `modulo-usuario-backend` (o el nombre que prefieras)
   - **Environment**: `Docker`
   - **Region**: Elige la más cercana (ej: `Oregon (US West)`)
   - **Branch**: `main` (o tu rama principal)
   - **Root Directory**: (dejar vacío)
   - **Build Command**: (dejar vacío, Render usa el Dockerfile)
   - **Start Command**: (dejar vacío, Render usa el Dockerfile)

### 4. Configurar Variables de Entorno en Render

En Render, ve a **Environment** y agrega estas variables:

```env
APP_NAME="Módulo Usuario API"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:... (genera uno con: php artisan key:generate)
APP_URL=https://modulo-usuario-backend.onrender.com

DB_CONNECTION=mongodb
MONGODB_URI=tu_uri_de_mongodb_atlas
MONGODB_DATABASE=equipo

GOOGLE_CLIENT_ID=tu_client_id
GOOGLE_CLIENT_SECRET=tu_client_secret
GOOGLE_REDIRECT_URI=https://modulo-usuario-backend.onrender.com/auth/google/callback

FACEBOOK_CLIENT_ID=tu_client_id
FACEBOOK_CLIENT_SECRET=tu_client_secret
FACEBOOK_REDIRECT_URI=https://modulo-usuario-backend.onrender.com/auth/facebook/callback

FRONTEND_URL=https://tu-frontend.netlify.app
CORS_ALLOWED_ORIGINS=https://tu-frontend.netlify.app,http://localhost:3000
```

**Importante**: 
- Reemplaza `modulo-usuario-backend` con el nombre que le diste a tu servicio
- Reemplaza `tu-frontend.netlify.app` con la URL de tu frontend en Netlify (la obtendrás después)

### 5. Desplegar Backend

- Click en **"Create Web Service"**
- Render construirá y desplegará automáticamente
- Espera a que el despliegue termine (puede tardar 5-10 minutos)
- Tu backend estará en: `https://modulo-usuario-backend.onrender.com`
- **Copia esta URL**, la necesitarás para el frontend

### 6. Verificar Backend

1. Accede a: `https://modulo-usuario-backend.onrender.com/api/preguntas-secretas`
2. Deberías ver JSON con las preguntas secretas
3. Si hay errores, revisa los logs en Render

---

## 🎨 Frontend - Netlify

### 1. Preparar el Frontend

El frontend ya está listo con:
- `netlify.toml` (configuración de Netlify)
- `package.json` (con script de build)
- `vite.config.js` (configuración de Vite)

### 2. Subir Frontend a GitHub

```bash
cd equipo
git add .
git commit -m "Frontend listo para Netlify"
git push origin main
```

### 3. Crear Sitio en Netlify

#### Opción A: Desde GitHub (Recomendado)

1. Ve a [Netlify](https://netlify.com) y crea una cuenta
2. Click en **"Add new site"** → **"Import an existing project"**
3. Selecciona **"GitHub"** y autoriza Netlify
4. Selecciona tu repositorio `Proyecto_modulo`
5. Configura:
   - **Branch to deploy**: `main`
   - **Base directory**: (dejar vacío)
   - **Build command**: `npm install && npm run build`
   - **Publish directory**: `dist`

#### Opción B: Desde Netlify CLI

```bash
# Instalar Netlify CLI
npm install -g netlify-cli

# Login en Netlify
netlify login

# Inicializar sitio
netlify init

# Desplegar
netlify deploy --prod
```

### 4. Configurar Variables de Entorno en Netlify

1. En Netlify Dashboard, ve a tu sitio
2. Ve a **Site settings** → **Environment variables**
3. Agrega estas variables:

```env
VITE_API_URL=https://modulo-usuario-backend.onrender.com/api
VITE_FRONTEND_URL=https://tu-frontend.netlify.app
```

**Importante**: 
- Reemplaza `modulo-usuario-backend` con el nombre de tu backend en Render
- La URL `tu-frontend.netlify.app` la obtendrás después del primer despliegue

### 5. Desplegar Frontend

#### Si usaste Opción A (GitHub):
- Netlify desplegará automáticamente cuando hagas push a `main`
- O puedes hacer click en **"Trigger deploy"** → **"Deploy site"**

#### Si usaste Opción B (CLI):
```bash
netlify deploy --prod
```

### 6. Obtener URL del Frontend

1. En Netlify Dashboard, ve a tu sitio
2. Verás la URL: `https://tu-frontend.netlify.app` (o un nombre aleatorio)
3. Puedes cambiarla en **Site settings** → **Change site name**
4. **Copia esta URL**, la necesitarás para actualizar el backend

### 7. Actualizar Variables de Entorno del Backend

Ahora que tienes la URL del frontend, actualiza el backend en Render:

1. Ve a Render Dashboard → Tu servicio backend
2. Ve a **Environment**
3. Actualiza estas variables:

```env
FRONTEND_URL=https://tu-frontend.netlify.app
CORS_ALLOWED_ORIGINS=https://tu-frontend.netlify.app,http://localhost:3000
```

4. Click en **"Save Changes"**
5. Render reiniciará automáticamente el servicio

---

## ⚙️ Configurar OAuth

### Google OAuth

1. Ve a [Google Cloud Console](https://console.cloud.google.com)
2. Selecciona tu proyecto
3. Ve a **APIs & Services** → **Credentials**
4. Edita tu OAuth 2.0 Client
5. Agrega a **Authorized redirect URIs**:
   ```
   https://modulo-usuario-backend.onrender.com/auth/google/callback
   ```
6. Click en **Save**

### Facebook OAuth

1. Ve a [Facebook Developers](https://developers.facebook.com)
2. Selecciona tu app
3. Ve a **Settings** → **Basic**
4. Agrega a **App Domains**:
   ```
   modulo-usuario-backend.onrender.com
   tu-frontend.netlify.app
   ```
5. Ve a **Facebook Login** → **Settings**
6. Agrega a **Valid OAuth Redirect URIs**:
   ```
   https://modulo-usuario-backend.onrender.com/auth/facebook/callback
   ```
7. Click en **Save Changes**

---

## 🔍 Verificación

### Backend

1. ✅ Accede a: `https://modulo-usuario-backend.onrender.com/api/preguntas-secretas`
2. ✅ Deberías ver JSON con las preguntas secretas
3. ✅ Verifica los logs en Render para errores

### Frontend

1. ✅ Accede a: `https://tu-frontend.netlify.app`
2. ✅ Deberías ver la página de login
3. ✅ Prueba hacer login
4. ✅ Verifica la consola del navegador (F12) para errores

### OAuth

1. ✅ Prueba login con Google
2. ✅ Prueba login con Facebook
3. ✅ Verifica que las redirecciones funcionen

---

## 🐛 Troubleshooting

### Backend no responde

- Verifica que las variables de entorno estén correctas en Render
- Revisa los logs en Render Dashboard
- Verifica que MongoDB esté accesible desde Render
- Verifica que `APP_KEY` esté configurado

### Frontend no se conecta al backend

- Verifica `VITE_API_URL` en Netlify (debe ser la URL del backend)
- Verifica CORS en el backend (debe incluir la URL de Netlify)
- Verifica que el backend esté corriendo
- Revisa la consola del navegador (F12) para errores CORS

### CORS Error

En el backend, verifica `config/cors.php`:
```php
'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'https://tu-frontend.netlify.app')),
```

Actualiza la variable de entorno en Render:
```env
CORS_ALLOWED_ORIGINS=https://tu-frontend.netlify.app,http://localhost:3000
```

### OAuth no funciona

- Verifica las URLs de redirect en Google/Facebook
- Verifica que las URLs en el backend sean correctas
- Verifica que el frontend esté en la lista de dominios permitidos

### Build falla en Netlify

- Verifica que `package.json` tenga el script `build`
- Verifica que `netlify.toml` esté configurado correctamente
- Revisa los logs de build en Netlify Dashboard

---

## 📝 Resumen

### Backend (Render)
- **URL**: `https://modulo-usuario-backend.onrender.com`
- **Tipo**: Web Service (Docker)
- **Variables importantes**: 
  - `FRONTEND_URL`
  - `CORS_ALLOWED_ORIGINS`
  - `MONGODB_URI`
  - `GOOGLE_REDIRECT_URI`
  - `FACEBOOK_REDIRECT_URI`

### Frontend (Netlify)
- **URL**: `https://tu-frontend.netlify.app`
- **Tipo**: Static Site
- **Variables importantes**:
  - `VITE_API_URL`
  - `VITE_FRONTEND_URL`

### Flujo de Despliegue

1. ✅ Desplegar backend en Render
2. ✅ Obtener URL del backend
3. ✅ Desplegar frontend en Netlify
4. ✅ Obtener URL del frontend
5. ✅ Actualizar variables de entorno del backend con URL del frontend
6. ✅ Configurar OAuth con las URLs de producción
7. ✅ Verificar que todo funcione

---

## ✅ Checklist de Despliegue

### Backend (Render)
- [ ] Backend subido a GitHub
- [ ] Servicio creado en Render
- [ ] Variables de entorno configuradas
- [ ] MongoDB Atlas configurado
- [ ] Backend desplegado y funcionando
- [ ] URL del backend obtenida

### Frontend (Netlify)
- [ ] Frontend subido a GitHub
- [ ] Sitio creado en Netlify
- [ ] Variables de entorno configuradas (con URL del backend)
- [ ] Build exitoso
- [ ] Frontend desplegado y funcionando
- [ ] URL del frontend obtenida

### Post-Despliegue
- [ ] Backend actualizado con URL del frontend
- [ ] CORS configurado correctamente
- [ ] OAuth (Google) configurado
- [ ] OAuth (Facebook) configurado
- [ ] Todo funciona correctamente

---

¡Listo! Tu aplicación estará desplegada en Netlify (frontend) y Render (backend). 🎉

