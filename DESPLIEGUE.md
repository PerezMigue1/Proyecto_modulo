# 🚀 Guía de Despliegue - Frontend y Backend

## 📋 Índice

1. [Despliegue del Backend (Laravel API)](#backend)
2. [Despliegue del Frontend (Vue.js)](#frontend)
3. [Configuración Post-Despliegue](#configuracion)

---

## 🔧 Backend - Laravel API

### Opción 1: Render.com (Recomendado)

#### 1. Preparar el Backend

El backend debe estar en un proyecto/repositorio separado con estos archivos:

- `Dockerfile`
- `render.yaml`
- `start.sh`
- `.env.example`

#### 2. Crear Servicio en Render

1. Ve a [Render.com](https://render.com) y crea una cuenta
2. Click en **"New +"** → **"Web Service"**
3. Conecta tu repositorio de GitHub/GitLab donde está el backend
4. Configura:
   - **Name**: `modulo-usuario-backend` (o el nombre que prefieras)
   - **Environment**: `Docker`
   - **Region**: Elige la más cercana
   - **Branch**: `main` (o tu rama principal)

#### 3. Configurar Variables de Entorno

En Render, ve a **Environment** y agrega:

```env
APP_NAME="Módulo Usuario API"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:... (genera uno con: php artisan key:generate)
APP_URL=https://backend-equipo.onrender.com

DB_CONNECTION=mongodb
MONGODB_URI=tu_uri_de_mongodb_atlas
MONGODB_DATABASE=equipo

GOOGLE_CLIENT_ID=tu_client_id
GOOGLE_CLIENT_SECRET=tu_client_secret
GOOGLE_REDIRECT_URI=https://tu-backend.onrender.com/auth/google/callback

FACEBOOK_CLIENT_ID=tu_client_id
FACEBOOK_CLIENT_SECRET=tu_client_secret
FACEBOOK_REDIRECT_URI=https://tu-backend.onrender.com/auth/facebook/callback

FRONTEND_URL=https://tu-frontend.onrender.com
CORS_ALLOWED_ORIGINS=https://tu-frontend.onrender.com,http://localhost:3000
```

#### 4. Desplegar

- Click en **"Create Web Service"**
- Render construirá y desplegará automáticamente
- Espera a que el despliegue termine
- Tu backend estará en: `https://backend-equipo.onrender.com`

---

## 🎨 Frontend - Vue.js

### Opción 1: Render.com

#### 1. Preparar el Frontend

1. Asegúrate de que `package.json` tenga el script de build:
   ```json
   {
     "scripts": {
       "build": "vite build"
     }
   }
   ```

2. Crea un archivo `render.yaml` en la raíz:

```yaml
services:
  - type: web
    name: modulo-usuario-frontend
    env: node
    buildCommand: npm install && npm run build
    startCommand: npx serve -s dist -l 10000
    envVars:
      - key: NODE_VERSION
        value: 18.x
```

#### 2. Crear Servicio en Render

1. Ve a [Render.com](https://render.com)
2. Click en **"New +"** → **"Static Site"** (o Web Service)
3. Conecta tu repositorio de GitHub/GitLab
4. Configura:
   - **Name**: `modulo-usuario-frontend`
   - **Environment**: `Node`
   - **Build Command**: `npm install && npm run build`
   - **Publish Directory**: `dist`

#### 3. Configurar Variables de Entorno

```env
VITE_API_URL=https://backend-equipo.onrender.com/api
VITE_FRONTEND_URL=https://tu-frontend.onrender.com
```

#### 4. Desplegar

- Click en **"Create Static Site"**
- Render construirá y desplegará automáticamente
- Tu frontend estará en: `https://tu-frontend.onrender.com`

### Opción 2: Vercel (Recomendado para Frontend)

#### 1. Instalar Vercel CLI

```bash
npm install -g vercel
```

#### 2. Desplegar

```bash
vercel
```

Sigue las instrucciones:
- ¿Set up and deploy? **Yes**
- ¿Which scope? Selecciona tu cuenta
- ¿Link to existing project? **No**
- ¿What's your project's name? `modulo-usuario-frontend`
- ¿In which directory is your code located? **./**

#### 3. Configurar Variables de Entorno

En el dashboard de Vercel:
1. Ve a tu proyecto
2. Settings → Environment Variables
3. Agrega:
   ```
   VITE_API_URL=https://backend-equipo.onrender.com/api
   VITE_FRONTEND_URL=https://tu-frontend.vercel.app
   ```

#### 4. Redesplegar

```bash
vercel --prod
```

### Opción 3: Netlify

#### 1. Preparar

Crea `netlify.toml` en la raíz:

```toml
[build]
  command = "npm run build"
  publish = "dist"

[[redirects]]
  from = "/*"
  to = "/index.html"
  status = 200
```

#### 2. Desplegar

1. Ve a [Netlify](https://netlify.com)
2. Arrastra la carpeta `dist` después de hacer `npm run build`
3. O conecta tu repositorio de GitHub

#### 3. Configurar Variables de Entorno

En Netlify Dashboard:
- Site settings → Environment variables
- Agrega:
  ```
  VITE_API_URL=https://backend-equipo.onrender.com/api
  VITE_FRONTEND_URL=https://tu-frontend.netlify.app
  ```

---

## ⚙️ Configuración Post-Despliegue

### 1. Actualizar URLs en Backend

En el backend (Render), actualiza las variables de entorno:

```env
FRONTEND_URL=https://tu-frontend.onrender.com
CORS_ALLOWED_ORIGINS=https://tu-frontend.onrender.com
GOOGLE_REDIRECT_URI=https://tu-backend.onrender.com/auth/google/callback
FACEBOOK_REDIRECT_URI=https://tu-backend.onrender.com/auth/facebook/callback
```

### 2. Actualizar URLs en Frontend

En el frontend, las variables de entorno ya están configuradas:
```env
VITE_API_URL=https://backend-equipo.onrender.com/api
VITE_FRONTEND_URL=https://tu-frontend.onrender.com
```

### 3. Actualizar OAuth (Google)

1. Ve a [Google Cloud Console](https://console.cloud.google.com)
2. Selecciona tu proyecto
3. Ve a **APIs & Services** → **Credentials**
4. Edita tu OAuth 2.0 Client
5. Agrega a **Authorized redirect URIs**:
   ```
   https://tu-backend.onrender.com/auth/google/callback
   ```

### 4. Actualizar OAuth (Facebook)

1. Ve a [Facebook Developers](https://developers.facebook.com)
2. Selecciona tu app
3. Ve a **Settings** → **Basic**
4. Agrega a **App Domains**:
   ```
   tu-backend.onrender.com
   tu-frontend.onrender.com
   ```
5. Ve a **Facebook Login** → **Settings**
6. Agrega a **Valid OAuth Redirect URIs**:
   ```
   https://tu-backend.onrender.com/auth/facebook/callback
   ```

### 5. Actualizar MongoDB Atlas

1. Ve a [MongoDB Atlas](https://cloud.mongodb.com)
2. Ve a **Network Access**
3. Agrega la IP de Render (o permite todas: `0.0.0.0/0`)
4. Verifica que la conexión funcione

---

## 🔍 Verificación

### Backend

1. ✅ Accede a: `https://backend-equipo.onrender.com/api/preguntas-secretas`
2. ✅ Deberías ver JSON con las preguntas secretas
3. ✅ Verifica los logs en Render para errores

### Frontend

1. ✅ Accede a: `https://tu-frontend.onrender.com`
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

- Verifica que las variables de entorno estén correctas
- Revisa los logs en Render
- Verifica que MongoDB esté accesible
- Verifica que `APP_KEY` esté configurado

### Frontend no se conecta al backend

- Verifica `VITE_API_URL` en las variables de entorno
- Verifica CORS en el backend
- Verifica que el backend esté corriendo
- Revisa la consola del navegador (F12)

### OAuth no funciona

- Verifica las URLs de redirect en Google/Facebook
- Verifica que las URLs en el backend sean correctas
- Verifica que el frontend esté en la lista de dominios permitidos

### CORS Error

En el backend, verifica `config/cors.php`:
```php
'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'https://tu-frontend.onrender.com')),
```

---

## 📝 Resumen

### Backend
- **Plataforma**: Render.com
- **URL**: `https://backend-equipo.onrender.com`
- **Tipo**: Web Service (Docker)

### Frontend
- **Plataforma**: Render.com, Vercel, o Netlify
- **URL**: `https://tu-frontend.onrender.com`
- **Tipo**: Static Site

### Variables Importantes

**Backend:**
- `FRONTEND_URL`
- `CORS_ALLOWED_ORIGINS`
- `MONGODB_URI`
- `GOOGLE_REDIRECT_URI`
- `FACEBOOK_REDIRECT_URI`

**Frontend:**
- `VITE_API_URL`
- `VITE_FRONTEND_URL`

---

## ✅ Checklist de Despliegue

### Backend
- [ ] Backend desplegado en Render
- [ ] Variables de entorno configuradas
- [ ] MongoDB Atlas configurado
- [ ] OAuth (Google) configurado
- [ ] OAuth (Facebook) configurado
- [ ] CORS configurado
- [ ] Backend responde correctamente

### Frontend
- [ ] Frontend desplegado (Render/Vercel/Netlify)
- [ ] Variables de entorno configuradas
- [ ] Build exitoso
- [ ] Frontend se conecta al backend
- [ ] Login funciona
- [ ] OAuth funciona

### Post-Despliegue
- [ ] URLs de OAuth actualizadas
- [ ] Dominios agregados en Facebook
- [ ] MongoDB accesible desde Render
- [ ] Todo funciona correctamente

---

¡Listo! Tu aplicación estará desplegada y funcionando. 🎉

