# Guía Completa para Configurar SendGrid

## 🎯 Resumen

Esta guía te lleva paso a paso para configurar SendGrid en tu aplicación Laravel para enviar correos de verificación a los usuarios en producción.

## 📋 Paso 1: Crear Cuenta en SendGrid

### 1.1 Registro
1. Ve a [https://sendgrid.com/](https://sendgrid.com/)
2. Haz clic en **"Start for Free"** o **"Sign Up"**
3. Completa el formulario:
   - Email: tu correo electrónico
   - Password: contraseña segura
   - First Name: tu nombre
   - Last Name: tu apellido
   - Company: nombre de tu empresa (opcional)
4. Acepta los términos y condiciones
5. Haz clic en **"Create Account"**

### 1.2 Verificación de Correo
1. Revisa tu correo electrónico
2. Haz clic en el enlace de verificación que te enviaron
3. Completa la información adicional si es necesario

### 1.3 Verificación de Identidad
1. SendGrid puede pedirte verificar tu identidad
2. Completa el proceso de verificación (puede incluir número de teléfono)
3. Una vez verificado, estarás en el dashboard de SendGrid

## 🔑 Paso 2: Crear API Key

### 2.1 Acceder a API Keys
1. En el dashboard de SendGrid, ve a **Settings** (Configuración)
2. Haz clic en **API Keys** en el menú lateral
3. Verás la página de API Keys

### 2.2 Crear Nuevo API Key
1. Haz clic en **"Create API Key"** (Crear API Key)
2. Completa el formulario:
   - **API Key Name**: Dale un nombre descriptivo (ej: "Laravel App - Producción")
   - **API Key Permissions**: Selecciona **"Full Access"** o **"Restricted Access"**
     - Si seleccionas "Restricted Access", asegúrate de dar permisos de **"Mail Send"**
3. Haz clic en **"Create & View"**
4. **⚠️ IMPORTANTE**: Copia el API Key inmediatamente
   - Este es el **único momento** en que podrás ver el API Key completo
   - Guárdalo en un lugar seguro (por ejemplo, un gestor de contraseñas)
   - Si lo pierdes, tendrás que crear uno nuevo

### 2.3 Guardar API Key
- Guarda el API Key en un lugar seguro
- Lo necesitarás para configurar las variables de entorno

## 📧 Paso 3: Verificar Remitente (Sender)

### 3.1 Acceder a Sender Authentication
1. En el dashboard de SendGrid, ve a **Settings** (Configuración) en el menú lateral
2. Haz clic en **Sender Authentication**
3. Verás dos opciones:
   - **Single Sender Verification**: Para un solo correo (✅ **RECOMENDADO PARA EMPEZAR**)
   - **Domain Authentication**: Para un dominio completo (✅ **RECOMENDADO PARA PRODUCCIÓN**)

---

### 3.2 Verificar Single Sender (Más Fácil - Para Empezar) ⭐

**¿Cuándo usar esto?**
- ✅ Si quieres empezar rápido
- ✅ Si no tienes acceso al DNS de tu dominio
- ✅ Si solo necesitas enviar desde un correo específico
- ✅ Si es para pruebas o desarrollo

**Pasos detallados:**

1. **Haz clic en "Verify a Single Sender"**
   - Verás un botón azul "Verify a Single Sender"

2. **Completa el formulario:**
   - **From Email Address**: 
     - Ejemplo: `noreply@tudominio.com`
     - O: `tu-email@gmail.com` (si no tienes dominio propio)
     - ⚠️ Este será el correo que aparecerá como remitente
   
   - **From Name**: 
     - Ejemplo: "Tu App" o "Modulo Usuario"
     - Este nombre aparecerá junto al correo
   
   - **Reply To**: 
     - Puede ser el mismo correo: `noreply@tudominio.com`
     - O un correo diferente: `soporte@tudominio.com`
   
   - **Company Address**: 
     - Tu dirección completa (requerido por SendGrid)
     - Ejemplo: "123 Calle Principal"
   
   - **City**: Tu ciudad
   - **State**: Tu estado o provincia
   - **Country**: Tu país (selecciona del dropdown)
   - **Zip Code**: Tu código postal

3. **Haz clic en "Create"**
   - SendGrid validará la información
   - Si todo está bien, verás un mensaje de éxito

4. **Verifica tu correo electrónico**
   - SendGrid enviará un correo a la dirección que especificaste
   - El asunto será algo como: "Please verify your sender email"
   - ⚠️ **IMPORTANTE**: Si usas un correo que no controlas, no podrás verificar

5. **Haz clic en el enlace de verificación**
   - Abre el correo que te envió SendGrid
   - Haz clic en el botón o enlace de verificación
   - Serás redirigido a SendGrid con un mensaje de éxito

6. **Verifica el estado**
   - Vuelve al dashboard de SendGrid
   - Ve a Settings → Sender Authentication
   - Deberías ver tu correo con estado "Verified" (Verificado)
   - ✅ Una vez verificado, puedes usar este correo como remitente

**⚠️ Notas importantes:**
- El correo debe existir y debes tener acceso a él
- Si usas Gmail, Hotmail, etc., funcionará pero es mejor usar un dominio propio
- Para producción, es mejor verificar un dominio completo

---

### 3.3 Verificar Dominio (Recomendado para Producción) 🌐

**¿Cuándo usar esto?**
- ✅ Si tienes un dominio propio (ej: tudominio.com)
- ✅ Si quieres enviar desde cualquier correo de tu dominio
- ✅ Si es para producción
- ✅ Si quieres mejor reputación y deliverability

**Requisitos:**
- ⚠️ Necesitas acceso al DNS de tu dominio
- ⚠️ Necesitas conocer tu proveedor de DNS (Cloudflare, GoDaddy, Namecheap, etc.)

**Pasos detallados:**

1. **Haz clic en "Authenticate Your Domain"**
   - Verás un botón "Authenticate Your Domain"

2. **Selecciona tu proveedor de DNS**
   - SendGrid te mostrará una lista de proveedores comunes:
     - Cloudflare
     - GoDaddy
     - Namecheap
     - Google Domains
     - AWS Route 53
     - Y muchos otros
   - Si tu proveedor no está en la lista, selecciona "Other"

3. **Ingresa tu dominio**
   - Ejemplo: `tudominio.com` (sin www)
   - SendGrid validará el formato del dominio

4. **SendGrid generará registros DNS**
   - SendGrid te mostrará varios registros DNS que debes agregar:
     - **CNAME Records** (2-3 registros): Para verificación
     - **TXT Record** (1 registro): Para SPF
     - **CNAME Records** (2 registros): Para DKIM
   - Cada registro tendrá:
     - **Type**: CNAME o TXT
     - **Host/Name**: El nombre del registro (ej: `s1._domainkey`)
     - **Value**: El valor del registro (ej: `s1.domainkey.sendgrid.net`)

5. **Agrega los registros en tu proveedor de DNS**
   - Ve a tu proveedor de DNS (Cloudflare, GoDaddy, etc.)
   - Accede a la configuración de DNS de tu dominio
   - Agrega cada registro que SendGrid te proporcionó
   - ⚠️ **IMPORTANTE**: Copia exactamente los valores que SendGrid te da
   - ⚠️ **IMPORTANTE**: No incluyas el dominio completo en el "Host", solo la parte antes del dominio

   **Ejemplo de registro CNAME:**
   ```
   Type: CNAME
   Host: s1._domainkey
   Value: s1.domainkey.sendgrid.net
   TTL: 3600 (o Auto)
   ```

6. **Espera la propagación DNS**
   - Los cambios DNS pueden tardar desde minutos hasta 48 horas
   - Normalmente toma entre 5 minutos y 2 horas
   - Puedes verificar la propagación en: https://www.whatsmydns.net/

7. **Verifica en SendGrid**
   - Vuelve a SendGrid
   - Haz clic en "Verify" o "Check DNS Records"
   - SendGrid verificará que los registros DNS estén configurados correctamente
   - Si todos los registros están correctos, verás un mensaje de éxito

8. **Verifica el estado**
   - Vuelve al dashboard de SendGrid
   - Ve a Settings → Sender Authentication
   - Deberías ver tu dominio con estado "Verified" (Verificado)
   - ✅ Una vez verificado, puedes enviar desde cualquier correo de tu dominio

**⚠️ Notas importantes:**
- Si algún registro DNS está incorrecto, SendGrid te indicará cuál
- Verifica que los registros no tengan el dominio completo en el "Host"
- Si tienes problemas, contacta al soporte de tu proveedor de DNS
- Para producción, es muy recomendable verificar el dominio completo

---

### 3.4 ¿Cuál Opción Elegir?

**Para empezar rápido (Recomendado):**
- ✅ Usa **Single Sender Verification**
- ✅ Es más rápido y fácil
- ✅ No requiere configuración de DNS
- ✅ Puedes cambiar a Domain Authentication después

**Para producción:**
- ✅ Usa **Domain Authentication**
- ✅ Mejor reputación
- ✅ Puedes enviar desde cualquier correo de tu dominio
- ✅ Más profesional

**Recomendación:**
1. **Primero**: Verifica un Single Sender para empezar a probar
2. **Después**: Verifica el dominio completo para producción
3. **Finalmente**: Usa el dominio verificado en producción

## ⚙️ Paso 4: Configurar Variables de Entorno

### 4.1 Configurar en Render (Producción)

1. Ve a tu servicio en [Render Dashboard](https://dashboard.render.com/)
2. Selecciona tu servicio backend
3. Ve a la pestaña **"Environment"**
4. Haz clic en **"Add Environment Variable"**
5. Agrega las siguientes variables:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=tu-api-key-de-sendgrid-aqui
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tudominio.com
MAIL_FROM_NAME="Tu App"
```

**⚠️ IMPORTANTE:**
- Reemplaza `tu-api-key-de-sendgrid-aqui` con el API Key que copiaste en el Paso 2
- Reemplaza `noreply@tudominio.com` con el correo que verificaste en el Paso 3
- Reemplaza `"Tu App"` con el nombre de tu aplicación

### 4.2 Configurar en `.env` Local (Desarrollo)

Si quieres probar localmente, agrega las mismas variables en tu archivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=tu-api-key-de-sendgrid-aqui
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tudominio.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4.3 Reiniciar Servicio en Render

1. Después de agregar las variables de entorno en Render
2. El servicio se reiniciará automáticamente
3. Espera a que el servicio esté en estado "Live"

## ✅ Paso 5: Probar Envío de Correo

### 5.1 Probar con Tinker (Local)

1. En tu proyecto Laravel, ejecuta:

```bash
php artisan tinker
```

2. Dentro de tinker, prueba el envío:

```php
use Illuminate\Support\Facades\Mail;
use App\Models\User;

// Obtener un usuario
$user = User::first();

// Enviar correo de verificación
$user->sendEmailVerificationNotification();

// O probar con Mail directamente
Mail::raw('Test email from SendGrid', function ($message) {
    $message->to('tu-email@ejemplo.com')
            ->subject('Test Email');
});
```

3. Verifica que el correo llegue a tu bandeja de entrada

### 5.2 Probar en Producción

1. Ve a tu aplicación en producción
2. Registra un nuevo usuario
3. Verifica que el correo de verificación llegue
4. Revisa el dashboard de SendGrid para ver las estadísticas

## 📊 Paso 6: Monitorear Envíos en SendGrid

### 6.1 Dashboard de SendGrid

1. Ve al dashboard de SendGrid
2. En la página principal verás:
   - **Correos enviados hoy**
   - **Correos enviados este mes**
   - **Tasa de entrega**
   - **Rebotes**
   - **Spam reports**

### 6.2 Estadísticas Detalladas

1. Ve a **Activity** (Actividad) en el menú lateral
2. Verás una lista de todos los correos enviados
3. Puedes filtrar por:
   - Fecha
   - Estado (delivered, bounced, etc.)
   - Destinatario
   - Asunto

### 6.3 Estadísticas de Entrega

1. Ve a **Stats** (Estadísticas) en el menú lateral
2. Verás gráficos de:
   - Correos enviados
   - Correos entregados
   - Correos abiertos
   - Clics en enlaces
   - Rebotes
   - Spam reports

## 🔍 Paso 7: Verificar Configuración en Laravel

### 7.1 Verificar Configuración de Mail

En tu archivo `config/mail.php`, verifica que la configuración sea correcta:

```php
'mailers' => [
    'smtp' => [
        'transport' => 'smtp',
        'host' => env('MAIL_HOST', 'smtp.sendgrid.net'),
        'port' => env('MAIL_PORT', 587),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'timeout' => null,
        'local_domain' => env('MAIL_EHLO_DOMAIN'),
    ],
],
```

### 7.2 Verificar Variables de Entorno

Ejecuta este comando para verificar que las variables estén configuradas:

```bash
php artisan config:show mail
```

O en producción, revisa los logs de Laravel para ver si hay errores de configuración.

## ⚠️ Problemas Comunes y Soluciones

### Problema 1: Correos no se envían

**Solución:**
1. Verifica que el API Key sea correcto
2. Verifica que `MAIL_USERNAME=apikey` (literalmente la palabra "apikey")
3. Verifica que `MAIL_PASSWORD` sea tu API Key de SendGrid
4. Verifica que el remitente esté verificado
5. Revisa los logs de Laravel: `storage/logs/laravel.log`
6. Revisa el dashboard de SendGrid para ver errores

### Problema 2: Error de autenticación

**Solución:**
1. Verifica que el API Key tenga permisos de "Mail Send"
2. Verifica que el API Key no haya expirado o sido revocado
3. Crea un nuevo API Key si es necesario
4. Verifica que las credenciales SMTP sean correctas

### Problema 3: Correos marcan como spam

**Solución:**
1. Verifica tu dominio (Domain Authentication)
2. Configura SPF, DKIM, y DMARC correctamente
3. No uses palabras spam en el asunto
4. Incluye enlace para darse de baja
5. Monitorea la reputación de tu dominio en SendGrid

### Problema 4: Límite de envío alcanzado

**Solución:**
1. Plan gratuito: 100 correos/día
2. Si necesitas más, actualiza a un plan de pago
3. Ve a Settings → Billing para actualizar tu plan
4. $19.95/mes para 50,000 correos

### Problema 5: Correos no llegan

**Solución:**
1. Verifica que el destinatario sea válido
2. Revisa la carpeta de spam del destinatario
3. Verifica que el remitente esté verificado
4. Revisa el dashboard de SendGrid para ver el estado del correo
5. Verifica que no estés en una lista negra

## 📈 Límites y Planes

### Plan Gratuito (Free)
- ✅ 100 correos/día
- ✅ 3,000 correos/mes
- ✅ Sin límite de tiempo
- ✅ Dashboard completo
- ✅ Estadísticas básicas
- ✅ API completa

### Plan Essentials ($19.95/mes)
- ✅ 50,000 correos/mes
- ✅ Soporte por email
- ✅ Estadísticas avanzadas
- ✅ Supresión de rebotes
- ✅ IP dedicada (opcional)

### Plan Pro ($89.95/mes)
- ✅ 100,000 correos/mes
- ✅ Soporte prioritario
- ✅ Estadísticas avanzadas
- ✅ IP dedicada
- ✅ Más funciones avanzadas

## 🎯 Mejores Prácticas

### 1. Usar Colas (Queues)
No envíes correos de forma síncrona. Usa colas:

```php
// En lugar de:
$user->sendEmailVerificationNotification();

// Usa:
dispatch(function () use ($user) {
    $user->sendEmailVerificationNotification();
});
```

### 2. Verificar Dominio
Para producción, verifica tu dominio completo en lugar de un solo correo. Esto mejora el deliverability.

### 3. Monitorear Estadísticas
Revisa regularmente el dashboard de SendGrid para:
- Verificar tasa de entrega
- Detectar problemas de spam
- Monitorear rebotes
- Ver estadísticas de apertura

### 4. Manejar Errores
Implementa manejo de errores para correos fallidos:

```php
try {
    $user->sendEmailVerificationNotification();
} catch (\Exception $e) {
    \Log::error('Error sending email: ' . $e->getMessage());
    // Manejar el error apropiadamente
}
```

### 5. Rate Limiting
No envíes demasiados correos muy rápido. Usa rate limiting para evitar problemas:

```php
// Enviar máximo 100 correos por minuto
RateLimiter::for('emails', function ($job) {
    return Limit::perMinute(100);
});
```

## 🔒 Seguridad

### 1. Proteger API Key
- ✅ Nunca commitees el API Key al repositorio
- ✅ Usa variables de entorno
- ✅ Rota el API Key regularmente
- ✅ Usa permisos restringidos si es posible

### 2. Verificar Remitente
- ✅ Siempre verifica el remitente antes de enviar
- ✅ Usa un dominio verificado para producción
- ✅ Configura SPF, DKIM, y DMARC

### 3. Monitorear Actividad
- ✅ Revisa regularmente la actividad de envío
- ✅ Detecta actividad sospechosa
- ✅ Revoca API Keys no utilizados

## 📝 Resumen de Pasos

1. ✅ Crear cuenta en SendGrid
2. ✅ Crear API Key
3. ✅ Verificar remitente (Single Sender o Domain)
4. ✅ Configurar variables de entorno en Render
5. ✅ Probar envío de correo
6. ✅ Monitorear envíos en SendGrid
7. ✅ Verificar configuración en Laravel

## 🚀 Siguiente Paso

Una vez configurado SendGrid:

1. Prueba el registro de usuarios
2. Verifica que los correos lleguen
3. Prueba la verificación de correo
4. Monitorea las estadísticas en SendGrid
5. Configura alertas si es necesario

## 📚 Recursos Adicionales

- [Documentación de SendGrid](https://docs.sendgrid.com/)
- [Guía de integración con Laravel](https://docs.sendgrid.com/for-developers/sending-email/laravel)
- [Dashboard de SendGrid](https://app.sendgrid.com/)
- [Soporte de SendGrid](https://support.sendgrid.com/)

## ✅ Checklist Final

- [ ] Cuenta en SendGrid creada
- [ ] API Key creado y guardado
- [ ] Remitente verificado
- [ ] Variables de entorno configuradas en Render
- [ ] Servicio reiniciado en Render
- [ ] Correo de prueba enviado
- [ ] Correo recibido correctamente
- [ ] Verificación de correo funcionando
- [ ] Dashboard de SendGrid monitoreando envíos

