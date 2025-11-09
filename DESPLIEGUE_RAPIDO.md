# 🚀 Despliegue Rápido

## Backend (Laravel API) - Render.com

### 1. Subir Backend a GitHub
```bash
cd tu-proyecto-backend
git init
git add .
git commit -m "Backend listo para desplegar"
git remote add origin tu-repositorio-backend
git push -u origin main
```

### 2. Crear Servicio en Render
1. Ve a [Render.com](https://render.com)
2. **New +** → **Web Service**
3. Conecta tu repositorio
4. Configura:
   - **Name**: `modulo-usuario-backend`
   - **Environment**: `Docker`
   - **Build Command**: (dejar vacío, usa Dockerfile)
   - **Start Command**: (dejar vacío, usa Dockerfile)

### 3. Variables de Entorno
```env
APP_KEY=base64:... (genera con: php artisan key:generate)
MONGODB_URI=tu_uri_mongodb
FRONTEND_URL=https://tu-frontend.onrender.com
CORS_ALLOWED_ORIGINS=https://tu-frontend.onrender.com
GOOGLE_CLIENT_ID=tu_id
GOOGLE_CLIENT_SECRET=tu_secret
GOOGLE_REDIRECT_URI=https://tu-backend.onrender.com/auth/google/callback
FACEBOOK_CLIENT_ID=tu_id
FACEBOOK_CLIENT_SECRET=tu_secret
FACEBOOK_REDIRECT_URI=https://tu-backend.onrender.com/auth/facebook/callback
```

### 4. Desplegar
- Click **Create Web Service**
- Espera a que termine
- URL: `https://tu-backend.onrender.com`

---

## Frontend (Vue.js) - Vercel (Más Rápido)

### 1. Instalar Vercel CLI
```bash
npm install -g vercel
```

### 2. Desplegar
```bash
cd equipo
vercel
```

### 3. Configurar Variables
En Vercel Dashboard:
```
VITE_API_URL=https://tu-backend.onrender.com/api
VITE_FRONTEND_URL=https://tu-frontend.vercel.app
```

### 4. Redesplegar
```bash
vercel --prod
```

---

## Frontend - Render.com (Alternativa)

### 1. Crear `render.yaml`
```yaml
services:
  - type: web
    name: modulo-usuario-frontend
    env: node
    buildCommand: npm install && npm run build
    startCommand: npx serve -s dist -l $PORT
    envVars:
      - key: VITE_API_URL
        value: https://tu-backend.onrender.com/api
      - key: VITE_FRONTEND_URL
        value: https://tu-frontend.onrender.com
```

### 2. Crear Servicio en Render
1. **New +** → **Static Site**
2. Conecta repositorio
3. **Build Command**: `npm install && npm run build`
4. **Publish Directory**: `dist`

---

## ⚙️ Actualizar OAuth

### Google
1. Google Cloud Console
2. **APIs & Services** → **Credentials**
3. Agrega redirect URI: `https://tu-backend.onrender.com/auth/google/callback`

### Facebook
1. Facebook Developers
2. **Settings** → **Basic**
3. Agrega dominio: `tu-backend.onrender.com`
4. **Facebook Login** → **Settings**
5. Agrega redirect URI: `https://tu-backend.onrender.com/auth/facebook/callback`

---

## ✅ Verificar

1. **Backend**: `https://tu-backend.onrender.com/api/preguntas-secretas`
2. **Frontend**: `https://tu-frontend.onrender.com`
3. **Login**: Prueba login y OAuth

---

¡Listo! 🎉

