# Configuración de Correo en Render

## Variables de Entorno Necesarias en Render

Para que el sistema de verificación de correo funcione en Render, necesitas configurar las siguientes variables de entorno en el panel de Render:

### Variables Requeridas:

1. **MAIL_MAILER**: `smtp` (ya configurado en render.yaml)
2. **MAIL_HOST**: Tu servidor SMTP (ej: `smtp.gmail.com`, `smtp.mailgun.org`, etc.)
3. **MAIL_PORT**: `587` (ya configurado) o `465` para SSL
4. **MAIL_USERNAME**: Tu email o usuario SMTP
5. **MAIL_PASSWORD**: Tu contraseña o token SMTP
6. **MAIL_ENCRYPTION**: `tls` (ya configurado) o `ssl`
7. **MAIL_FROM_ADDRESS**: El email desde el cual se enviarán los correos
8. **MAIL_FROM_NAME**: `Laravel App` (ya configurado) o el nombre que prefieras

## Opciones de Servicios SMTP

### Gmail (Gratis)
```
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseña_de_aplicación
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email@gmail.com
```

**Nota**: Para Gmail necesitas crear una "Contraseña de aplicación" en tu cuenta de Google.

### Mailgun (Recomendado para producción)
```
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@tu-dominio.mailgun.org
MAIL_PASSWORD=tu-api-key-de-mailgun
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tu-dominio.com
```

### SendGrid
```
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=tu-api-key-de-sendgrid
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tu-dominio.com
```

### Postmark
```
MAIL_MAILER=postmark
POSTMARK_TOKEN=tu-token-de-postmark
MAIL_FROM_ADDRESS=noreply@tu-dominio.com
```

## Cómo Configurar en Render

1. Ve a tu servicio en Render Dashboard
2. Navega a la sección "Environment"
3. Agrega cada una de las variables de entorno mencionadas
4. Asegúrate de que `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD` y `MAIL_FROM_ADDRESS` estén marcadas como "Sync: false" para mantenerlas privadas
5. Guarda los cambios
6. Render desplegará automáticamente con la nueva configuración

## Verificación

Después de configurar:
1. Intenta registrarte en la aplicación
2. Verifica que recibas el correo de verificación
3. Si no recibes el correo, revisa los logs en Render para ver errores

## Protecciones Implementadas

- ✅ Rate limiting en registro (máximo 3 por hora por IP)
- ✅ Rate limiting en reenvío de correos (máximo 1 cada 60 segundos)
- ✅ Validación de email único
- ✅ Verificación de correo obligatoria antes de login

## Notas Importantes

- Los correos se envían automáticamente cuando un usuario se registra
- El usuario NO puede iniciar sesión hasta verificar su correo
- Si el correo no se configura correctamente, los usuarios no podrán completar el registro

