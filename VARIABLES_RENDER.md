# 🔧 Variables de Entorno para Render

Esta guía lista todas las variables de entorno que debes configurar en Render para que el backend funcione correctamente con OTP, OAuth y todas las funcionalidades.

## 📋 Variables Requeridas

### 🔐 Variables de Aplicación Laravel

```env
APP_NAME="Módulo de Usuario"
APP_ENV=production
APP_KEY=base64:TU_CLAVE_APP_AQUI
APP_DEBUG=false
APP_URL=https://backend-equipo.onrender.com
APP_TIMEZONE=America/Mexico_City
```

**Cómo obtener `APP_KEY`:**
```bash
# En tu proyecto Laravel local, ejecuta:
php artisan key:generate

# Copia la clave generada (formato: base64:...)
```

### 🌐 Variables de Frontend y CORS

```env
FRONTEND_URL=https://modulo-usuario.netlify.app
CORS_ALLOWED_ORIGINS=https://modulo-usuario.netlify.app,https://modulo-usuario.netlify.app/
```

**Nota:** Si tu frontend está en otro dominio, actualiza `FRONTEND_URL` y `CORS_ALLOWED_ORIGINS` con la URL correcta.

### 🗄️ Variables de Base de Datos (MongoDB)

```env
DB_CONNECTION=mongodb
DB_HOST=tu-cluster.mongodb.net
DB_PORT=27017
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario_mongodb
DB_PASSWORD=tu_contraseña_mongodb
```

**Formato de conexión MongoDB Atlas:**
```
mongodb+srv://DB_USERNAME:DB_PASSWORD@DB_HOST/DB_DATABASE?retryWrites=true&w=majority
```

### 🔑 Variables de JWT (Autenticación)

```env
JWT_SECRET=tu_secret_key_muy_largo_y_seguro_aqui
JWT_TTL=60
JWT_REFRESH_TTL=20160
```

**Cómo generar `JWT_SECRET`:**
```bash
# En tu proyecto Laravel local, ejecuta:
php artisan jwt:secret

# O genera una clave aleatoria:
php artisan tinker
>>> Str::random(64)
```

### 📧 Variables de SendGrid (OTP por Email)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.tu_api_key_de_sendgrid_aqui
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tudominio.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Cómo obtener `MAIL_PASSWORD` (SendGrid API Key):**
1. Ve a [SendGrid](https://sendgrid.com/)
2. Inicia sesión o crea una cuenta
3. Ve a **Settings** → **API Keys**
4. Click en **Create API Key**
5. Dale un nombre (ej: "Render Production")
6. Selecciona **Full Access** o **Restricted Access** (con permisos de Mail Send)
7. Copia la API Key (empieza con `SG.`)
8. **⚠️ IMPORTANTE:** Solo se muestra una vez, guárdala bien

**Nota sobre `MAIL_FROM_ADDRESS`:**
- Debe ser un email verificado en SendGrid
- Puede ser `noreply@tudominio.com` o cualquier email que hayas verificado

### 🔵 Variables de Google OAuth

```env
GOOGLE_CLIENT_ID=tu_google_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-tu_google_client_secret
GOOGLE_REDIRECT_URI=https://backend-equipo.onrender.com/auth/google/callback
```

**Cómo obtener credenciales de Google:**
1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Crea un proyecto o selecciona uno existente
3. Ve a **APIs & Services** → **Credentials**
4. Click en **Create Credentials** → **OAuth client ID**
5. Tipo: **Web application**
6. **Authorized redirect URIs:** `https://backend-equipo.onrender.com/auth/google/callback`
7. Copia el **Client ID** y **Client Secret**

### 🔵 Variables de Facebook OAuth

```env
FACEBOOK_CLIENT_ID=tu_facebook_app_id
FACEBOOK_CLIENT_SECRET=tu_facebook_app_secret
FACEBOOK_REDIRECT_URI=https://backend-equipo.onrender.com/auth/facebook/callback
```

**Cómo obtener credenciales de Facebook:**
1. Ve a [Facebook Developers](https://developers.facebook.com/)
2. Crea una nueva App o selecciona una existente
3. Ve a **Settings** → **Basic**
4. Copia el **App ID** y **App Secret**
5. Ve a **Facebook Login** → **Settings**
6. Agrega **Valid OAuth Redirect URIs:** `https://backend-equipo.onrender.com/auth/facebook/callback`

### ⚙️ Variables Opcionales (Recomendadas)

```env
LOG_CHANNEL=stack
LOG_LEVEL=error
LOG_DEPRECATIONS_CHANNEL=null
```

## 📝 Resumen de Variables en Render

### Paso 1: Ir a Render Dashboard
1. Ve a [Render Dashboard](https://dashboard.render.com/)
2. Selecciona tu servicio (Web Service)
3. Ve a **Environment** en el menú lateral

### Paso 2: Agregar Variables
Para cada variable, haz click en **Add Environment Variable** y agrega:

| Variable | Valor | Ejemplo |
|----------|-------|---------|
| `APP_NAME` | Nombre de tu app | `Módulo de Usuario` |
| `APP_ENV` | Entorno | `production` |
| `APP_KEY` | Clave de Laravel | `base64:...` |
| `APP_DEBUG` | Modo debug | `false` |
| `APP_URL` | URL del backend | `https://backend-equipo.onrender.com` |
| `FRONTEND_URL` | URL del frontend | `https://modulo-usuario.netlify.app` |
| `CORS_ALLOWED_ORIGINS` | Orígenes permitidos | `https://modulo-usuario.netlify.app` |
| `DB_CONNECTION` | Tipo de BD | `mongodb` |
| `DB_HOST` | Host de MongoDB | `cluster.mongodb.net` |
| `DB_DATABASE` | Nombre de BD | `modulo_usuario` |
| `DB_USERNAME` | Usuario MongoDB | `usuario` |
| `DB_PASSWORD` | Contraseña MongoDB | `contraseña_segura` |
| `JWT_SECRET` | Secret JWT | `clave_secreta_larga` |
| `JWT_TTL` | Tiempo de vida | `60` |
| `MAIL_MAILER` | Driver de email | `smtp` |
| `MAIL_HOST` | Host SMTP | `smtp.sendgrid.net` |
| `MAIL_PORT` | Puerto SMTP | `587` |
| `MAIL_USERNAME` | Usuario SMTP | `apikey` |
| `MAIL_PASSWORD` | API Key SendGrid | `SG.xxx...` |
| `MAIL_FROM_ADDRESS` | Email remitente | `noreply@tudominio.com` |
| `MAIL_FROM_NAME` | Nombre remitente | `Módulo de Usuario` |
| `GOOGLE_CLIENT_ID` | Google Client ID | `xxx.apps.googleusercontent.com` |
| `GOOGLE_CLIENT_SECRET` | Google Secret | `GOCSPX-xxx` |
| `GOOGLE_REDIRECT_URI` | Google Callback | `https://backend-equipo.onrender.com/auth/google/callback` |
| `FACEBOOK_CLIENT_ID` | Facebook App ID | `123456789` |
| `FACEBOOK_CLIENT_SECRET` | Facebook Secret | `abc123...` |
| `FACEBOOK_REDIRECT_URI` | Facebook Callback | `https://backend-equipo.onrender.com/auth/facebook/callback` |

## ✅ Checklist de Verificación

Después de configurar todas las variables, verifica:

- [ ] `APP_KEY` está configurada (formato: `base64:...`)
- [ ] `FRONTEND_URL` apunta a tu frontend en Netlify
- [ ] `CORS_ALLOWED_ORIGINS` incluye la URL de tu frontend
- [ ] Variables de MongoDB están correctas (conecta a tu cluster)
- [ ] `JWT_SECRET` está configurada (clave larga y segura)
- [ ] `MAIL_PASSWORD` contiene tu API Key de SendGrid (formato: `SG.xxx...`)
- [ ] `MAIL_FROM_ADDRESS` es un email verificado en SendGrid
- [ ] `GOOGLE_REDIRECT_URI` coincide con la configurada en Google Cloud Console
- [ ] `FACEBOOK_REDIRECT_URI` coincide con la configurada en Facebook Developers
- [ ] Todas las URLs usan `https://` (no `http://`)

## 🧪 Probar la Configuración

Después de configurar las variables, haz un nuevo deploy y prueba:

1. **Registro con OTP:**
   - Registra un nuevo usuario
   - Debe recibir un email con código OTP
   - Verifica el código

2. **Login:**
   - Intenta hacer login con email/password
   - Debe funcionar si la cuenta está activada

3. **OAuth:**
   - Prueba login con Google
   - Prueba login con Facebook
   - Deben redirigir correctamente

4. **Recuperación de contraseña:**
   - Prueba recuperación con OTP
   - Debe enviar email con código

## ⚠️ Notas Importantes

1. **Seguridad:**
   - Nunca compartas tus variables de entorno
   - No las subas a Git (están en `.gitignore`)
   - Usa valores seguros y únicos

2. **SendGrid:**
   - Verifica tu dominio o email en SendGrid antes de usar
   - El plan gratuito tiene límites (100 emails/día)
   - Monitorea tu uso en el dashboard de SendGrid

3. **OAuth:**
   - Las URLs de callback deben coincidir exactamente
   - Verifica que los redirect URIs estén configurados en los proveedores
   - Los secrets no deben tener espacios ni saltos de línea

4. **MongoDB:**
   - Asegúrate de que tu IP esté en la whitelist de MongoDB Atlas
   - O configura `0.0.0.0/0` para permitir todas las IPs (menos seguro)

5. **Render:**
   - Después de agregar variables, haz un nuevo deploy
   - Las variables se aplican en el siguiente deploy
   - Puedes verificar las variables en los logs del deploy

## 🔍 Solución de Problemas

### Error: "No application encryption key has been specified"
- **Solución:** Configura `APP_KEY` con `php artisan key:generate`

### Error: "Could not send email"
- **Solución:** Verifica `MAIL_PASSWORD` (API Key de SendGrid) y `MAIL_FROM_ADDRESS`

### Error: "Invalid OAuth redirect URI"
- **Solución:** Verifica que `GOOGLE_REDIRECT_URI` y `FACEBOOK_REDIRECT_URI` coincidan exactamente con los configurados en los proveedores

### Error: "MongoDB connection failed"
- **Solución:** Verifica `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` y la whitelist de IPs en MongoDB Atlas

### Error: "JWT secret not set"
- **Solución:** Configura `JWT_SECRET` con una clave segura

## 📚 Recursos Adicionales

- [Documentación de Render](https://render.com/docs)
- [Documentación de SendGrid](https://docs.sendgrid.com/)
- [Documentación de Google OAuth](https://developers.google.com/identity/protocols/oauth2)
- [Documentación de Facebook OAuth](https://developers.facebook.com/docs/facebook-login)
- [Documentación de Laravel](https://laravel.com/docs)

---

**Última actualización:** 2024-01-XX

