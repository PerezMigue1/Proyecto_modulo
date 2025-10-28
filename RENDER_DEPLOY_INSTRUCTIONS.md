# 📋 Instrucciones para Desplegar en Render

## ✅ Estado Actual
- ✅ Nombre del servicio: **ModuloUsuario**
- ✅ Rama: **main**
- ✅ Archivo render.yaml configurado

## 🚀 Paso 1: Variables de Entorno en Render

Ve al Dashboard de Render: https://dashboard.render.com

### En tu servicio "ModuloUsuario", agrega estas variables:

```
APP_NAME=Laravel
APP_ENV=production
APP_DEBUG=false
LOG_CHANNEL=stderr
LOG_LEVEL=error

# MongoDB (desde MongoDB Atlas)
MONGODB_URI=your-mongodb-connection-string-from-atlas

# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id-here
GOOGLE_CLIENT_SECRET=your-google-client-secret-here
GOOGLE_REDIRECT_URI=https://modulousuario.onrender.com/auth/google/callback

# Cache y Sesión
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

## 🔑 Paso 2: Actualizar Google OAuth

**DESPUÉS** de que Render te dé la URL (ejemplo: `https://modulousuario.onrender.com`):

1. Ve a: https://console.cloud.google.com/apis/credentials
2. Edita tu Client ID
3. En "Authorized redirect URIs", agrega:
   ```
   https://modulousuario.onrender.com/auth/google/callback
   ```
4. **Mantén también** el de localhost para desarrollo:
   ```
   http://localhost:8000/auth/google/callback
   ```
5. Guarda

## 📝 Paso 3: Actualizar GOOGLE_REDIRECT_URI en Render

Una vez que sepas la URL exacta de tu app en Render:

1. Ve al Dashboard de Render
2. Environment → Edita `GOOGLE_REDIRECT_URI`
3. Cambia a: `https://tu-url-exacta.onrender.com/auth/google/callback`

## 🐛 Problemas Comunes y Soluciones

### Error: "could not find driver"
**Solución**: Render usa PHP 8.2 por defecto, está bien.

### Error: "npm ci" falla
**Solución**: Render necesita Node.js. El buildCommand ya incluye `npm ci`.

### Error: MongoDB no conecta
**Solución**: 
1. Ve a MongoDB Atlas → Network Access
2. Agrega IP: `0.0.0.0/0` (Allow from anywhere)
3. Esto permite que Render se conecte

### Error: Assets no se cargan
**Solución**: El comando `npm run build` ya está en el buildCommand, Render lo compilará automáticamente.

### Error: "APP_KEY not set"
**Solución**: Ya está en el buildCommand: `php artisan key:generate --force`

## ⚡ Comandos en el Log de Render

Si ves errores, verifica que el build esté ejecutando:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan key:generate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔗 URL de tu App

Tu app estará disponible en:
```
https://modulousuario.onrender.com
```

## 📊 Verificar Despliegue

1. ✅ Login funciona
2. ✅ Registro funciona
3. ✅ Recuperar contraseña funciona
4. ✅ Login con Google funciona
5. ✅ Dashboard cargaba

## ⚠️ Importante

- **Free tier**: Tarda 50 segundos en "despertar" si no hay tráfico
- **MongoDB Atlas**: Free tier M0 tiene 512MB, suficiente para empezar
- **Logs**: Verás el build en tiempo real en Render dashboard

## 🎯 Próximos Pasos

1. Sube los cambios al repositorio:
   ```bash
   git add render.yaml
   git commit -m "Update render.yaml for ModuloUsuario"
   git push
   ```

2. Render detectará los cambios automáticamente y volverá a desplegar

3. Espera 5-10 minutos para el build completo

4. Prueba tu app en: `https://modulousuario.onrender.com`
