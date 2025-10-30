# Guía Completa: Configurar Correo Gratis en Render

## 🎯 Opción Recomendada: Mailgun (GRATIS - 5,000 correos/mes)

### Paso 1: Crear cuenta en Mailgun

1. Ve a: https://www.mailgun.com/
2. Click en "Sign Up" (Registrarse)
3. Crea una cuenta gratuita
4. Verifica tu email

### Paso 2: Obtener credenciales SMTP

1. Una vez dentro de Mailgun, ve a **"Sending" → "Domain Settings"**
2. Si aún no tienes un dominio verificado, puedes usar el dominio de prueba que Mailgun te da
3. Ve a **"Domain Settings" → "SMTP credentials"**
4. Copia estos datos:
   - **SMTP Hostname**: `smtp.mailgun.org`
   - **Port**: `587` o `465`
   - **Username**: (aparece en la página, algo como `postmaster@tu-dominio.mailgun.org`)
   - **Password**: (tu contraseña SMTP que verás allí)

### Paso 3: Configurar en Render

1. Ve a tu servicio en Render: https://dashboard.render.com/
2. Selecciona tu servicio "ModuloUsuario"
3. Click en **"Environment"** en el menú lateral
4. Agrega estas variables una por una:

```
MAIL_MAILER = smtp
MAIL_HOST = smtp.mailgun.org
MAIL_PORT = 587
MAIL_USERNAME = postmaster@tu-dominio.mailgun.org  (el que te dio Mailgun)
MAIL_PASSWORD = tu-contraseña-smtp-de-mailgun  (la que copiaste)
MAIL_ENCRYPTION = tls
MAIL_FROM_ADDRESS = noreply@tu-dominio.mailgun.org  (o el email que quieras)
MAIL_FROM_NAME = Laravel App
```

5. Para cada variable que tenga información sensible (USERNAME, PASSWORD), asegúrate de que **"Sync" esté desactivado** (esto la mantiene privada)
6. Click en **"Save Changes"**
7. Render reiniciará tu servicio automáticamente

### Paso 4: Probar

1. Registra un nuevo usuario en tu aplicación
2. Revisa tu correo (y la carpeta de spam)
3. Deberías recibir el correo de verificación

---

## 📧 Opción Alternativa: SendGrid (GRATIS - 100 correos/día)

Si prefieres SendGrid:

### Paso 1: Crear cuenta

1. Ve a: https://sendgrid.com/
2. Click en "Start for free"
3. Crea tu cuenta
4. Completa el proceso de verificación

### Paso 2: Crear API Key

1. Ve a **"Settings" → "API Keys"**
2. Click en "Create API Key"
3. Dale un nombre y selecciona "Full Access" o "Mail Send"
4. Copia la API key (solo se muestra una vez, guárdala bien)

### Paso 3: Configurar en Render

En Render, agrega estas variables:

```
MAIL_MAILER = smtp
MAIL_HOST = smtp.sendgrid.net
MAIL_PORT = 587
MAIL_USERNAME = apikey
MAIL_PASSWORD = tu-api-key-de-sendgrid  (la que copiaste)
MAIL_ENCRYPTION = tls
MAIL_FROM_ADDRESS = tu-email-verificado@ejemplo.com
MAIL_FROM_NAME = Laravel App
```

---

## ⚙️ Configuración Paso a Paso en Render (Visual)

1. **Ingresa a Render Dashboard**: https://dashboard.render.com/
2. **Selecciona tu servicio**: Busca "ModuloUsuario" en la lista
3. **Ve a Environment**: Click en "Environment" en el menú izquierdo
4. **Agrega cada variable**:
   - Click en "Add Environment Variable"
   - Nombre: `MAIL_HOST`
   - Valor: `smtp.mailgun.org` (o el del servicio que elijas)
   - **Desactiva "Sync"** (esto mantiene la variable privada)
   - Click "Save"
5. **Repite para todas las variables**:
   - `MAIL_USERNAME`
   - `MAIL_PASSWORD`
   - `MAIL_FROM_ADDRESS`
   - etc.
6. **Guarda y espera**: Render reiniciará automáticamente

---

## 📊 Comparación de Servicios Gratuitos

| Servicio | Límite Gratis | Dificultad | Recomendado |
|----------|---------------|------------|-------------|
| **Mailgun** | 5,000/mes | Fácil | ⭐⭐⭐⭐⭐ |
| **SendGrid** | 100/día | Fácil | ⭐⭐⭐⭐ |
| **Resend** | 3,000/mes | Fácil | ⭐⭐⭐⭐ |
| **Gmail** | 500/día | Difícil | ⭐⭐ |

---

## ✅ Checklist de Configuración

- [ ] Creé cuenta en Mailgun (o SendGrid)
- [ ] Obtuve las credenciales SMTP
- [ ] Agregué todas las variables en Render
- [ ] Desмонтировал "Sync" para variables sensibles
- [ ] Guardé los cambios en Render
- [ ] Render reinició el servicio
- [ ] Probé registrando un usuario
- [ ] Recibí el correo de verificación

---

## 🆘 Problemas Comunes

### No recibo correos
1. Revisa la carpeta de **spam**
2. Verifica que todas las variables estén correctas en Render
3. Revisa los **logs de Render** para ver errores
4. Asegúrate de que el email esté verificado en el servicio SMTP

### Error de autenticación
1. Verifica que `MAIL_USERNAME` y `MAIL_PASSWORD` sean correctas
2. Asegúrate de usar las credenciales SMTP, no las de la cuenta principal
3. Verifica que el puerto sea correcto (587 para TLS, 465 para SSL)

### Render no inicia
1. Verifica que no haya errores de sintaxis en las variables
2. Asegúrate de que todas las variables requeridas estén presentes
3. Revisa los logs de build en Render

---

## 🎉 Después de Configurar

Una vez configurado, cualquier usuario puede:
1. ✅ Registrarse en tu aplicación
2. ✅ Recibir correo de verificación
3. ✅ Verificar su correo
4. ✅ Iniciar sesión

**¡Todo funcionará automáticamente!**

