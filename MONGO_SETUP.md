# Configuración MongoDB - Proyecto Equipo

## ✅ Configuración Completada

Se ha configurado exitosamente la conexión con MongoDB y se han creado páginas de autenticación modernas.

### 📦 Paquetes Instalados

- `jenssegers/mongodb` - Integración MongoDB para Laravel
- `mongodb/mongodb` - Driver MongoDB para PHP

### 🔧 Archivos Modificados/Creados

#### Configuración de Base de Datos
- **`config/database.php`**: Agregada configuración de MongoDB como conexión por defecto
- **`composer.json`**: Agregados paquetes de MongoDB

#### Modelos
- **`app/Models/User.php`**: Actualizado para usar MongoDB en lugar de Eloquent tradicional

#### Vistas de Autenticación
- **`resources/views/auth/login.blade.php`**: Página de login moderna
- **`resources/views/auth/forgot-password.blade.php`**: Página de recuperación de contraseña
- **`resources/views/auth/reset-password.blade.php`**: Página de restablecimiento de contraseña

#### Estilos CSS
- **`public/css/auth.css`**: Estilos modernos para las páginas de autenticación
- **`resources/css/auth.css`**: Archivo fuente de estilos

#### Configuración
- **`app/Providers/FortifyServiceProvider.php`**: Configurado para usar las vistas Blade personalizadas

### 🌐 Variables de Entorno

Asegúrate de que tu archivo `.env` contenga las siguientes variables:

```env
DB_CONNECTION=mongodb
MONGODB_URI=mongodb+srv://equipo:1234@equipo.o0p3nrz.mongodb.net/equipo?retryWrites=true&w=majority&appName=Equipo
MONGODB_DATABASE=equipo
PORT=5000
```

### 🚀 Cómo Usar

1. **Instalar dependencias** (si aún no lo has hecho):
   ```bash
   composer install
   npm install
   ```

2. **Ejecutar el servidor**:
   ```bash
   php artisan serve --port=5000
   ```

3. **Acceder a las páginas**:
   - Login: http://localhost:5000/login
   - Recuperar contraseña: http://localhost:5000/forgot-password
   - Registro: http://localhost:5000/register

### 📝 Características de las Páginas de Autenticación

#### Login (`/login`)
- Diseño moderno con gradiente púrpura
- Campos: Email y Contraseña
- Checkbox "Recordarme"
- Enlace a recuperación de contraseña
- Enlace a registro

#### Recuperar Contraseña (`/forgot-password`)
- Formulario para solicitar enlace de recuperación
- Envío automático de correo (configurar SMTP)
- Notificaciones de éxito/error

#### Resetear Contraseña (`/reset-password`)
- Formulario para establecer nueva contraseña
- Validación de confirmación
- Enlace de vuelta al login

### 🎨 Características del Diseño

- **Gradiente moderno**: Púrpura a violeta (#667eea - #764ba2)
- **Responsive**: Adaptado para móviles y tablets
- **Animaciones suaves**: Transiciones y efectos visuales
- **Validación visual**: Bordes que cambian de color al enfocar
- **Iconografía**: Decoración visual en pantallas grandes

### ⚙️ Configuración de Correo (Para Recuperación de Contraseña)

Para que la recuperación de contraseña funcione, necesitas configurar el envío de correos en tu `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@equipo.com"
MAIL_FROM_NAME="Equipo"
```

### 📂 Estructura de Archivos

```
equipo/
├── config/
│   └── database.php (✅ Configurado para MongoDB)
├── app/
│   ├── Models/
│   │   └── User.php (✅ Usa MongoDB)
│   └── Providers/
│       └── FortifyServiceProvider.php (✅ Vistas personalizadas)
├── resources/
│   ├── views/
│   │   └── auth/
│   │       ├── login.blade.php
│   │       ├── forgot-password.blade.php
│   │       └── reset-password.blade.php
│   └── css/
│       └── auth.css
└── public/
    └── css/
        └── auth.css
```

### 🔐 Seguridad

- Las contraseñas se hashean automáticamente con bcrypt
- Protección CSRF en todos los formularios
- Rate limiting en las rutas de autenticación
- Validación de entrada en todos los campos

### 🐛 Solución de Problemas

**Error de conexión a MongoDB**:
- Verifica que MONGODB_URI esté correcto en `.env`
- Asegúrate de que las credenciales sean correctas
- Verifica que tu IP esté permitida en MongoDB Atlas

**CSS no se carga**:
- Asegúrate de que el archivo existe en `public/css/auth.css`
- Limpia la caché: `php artisan cache:clear`

**Vistas no se muestran**:
- Verifica que FortifyServiceProvider esté correctamente configurado
- Ejecuta: `php artisan config:clear`

### 📞 Rutas Disponibles

- `GET /login` - Muestra formulario de login
- `POST /login` - Procesa login
- `GET /logout` - Cierra sesión
- `GET /register` - Muestra formulario de registro
- `GET /forgot-password` - Solicitar recuperación
- `POST /forgot-password` - Enviar correo de recuperación
- `GET /reset-password/{token}` - Mostrar formulario de reset
- `POST /reset-password` - Procesar reset de contraseña

### ✅ Estado Actual

- ✅ MongoDB configurado
- ✅ Vistas de login creadas
- ✅ Vistas de recuperación de contraseña creadas
- ✅ Estilos CSS modernos aplicados
- ✅ User model actualizado para MongoDB
- ✅ Fortify configurado para usar vistas personalizadas

### 🎯 Próximos Pasos

1. Configurar envío de correos (SMTP)
2. Probar el flujo completo de autenticación
3. Personalizar los mensajes de error según necesidades
4. Agregar más campos al formulario de registro si es necesario

