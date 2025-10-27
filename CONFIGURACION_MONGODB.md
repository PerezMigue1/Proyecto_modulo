# ✅ Configuración MongoDB - Colección Usuarios

## 📊 Estructura de Base de Datos

### Base de Datos: `equipo`
### Colección: `usuarios`

Cuando te registres, los datos se guardarán en MongoDB en la siguiente estructura:

```json
{
  "_id": ObjectId("..."),
  "name": "Tu Nombre",
  "email": "tu@correo.com",
  "password": "$2y$10$...", // Hasheada con bcrypt
  "email_verified_at": ISODate("..."),
  "two_factor_secret": null,
  "two_factor_recovery_codes": null,
  "two_factor_confirmed_at": null,
  "remember_token": null,
  "created_at": ISODate("..."),
  "updated_at": ISODate("...")
}
```

## ⚙️ Configuración Aplicada

### Modelo User (`app/Models/User.php`)
- ✅ Extiende de `MongoDB\Laravel\Auth\User`
- ✅ Colección especificada: `usuarios`
- ✅ Conexión a base de datos: `equipo`
- ✅ Contraseñas se hashean automáticamente
- ✅ Campos ocultos: password, tokens, etc.

### Archivo .env
```env
DB_CONNECTION=mongodb
MONGODB_URI=mongodb+srv://equipo:1234@equipo.o0p3nrz.mongodb.net/equipo?retryWrites=true&w=majority&appName=Equipo
MONGODB_DATABASE=equipo
```

## 🧪 Cómo Probar el Registro

### 1. Visita la página de registro
```
http://localhost:8000/register
```

### 2. Completa el formulario
- Nombre
- Correo electrónico
- Contraseña
- Confirmar contraseña

### 3. Envía el formulario
- Los datos se guardarán en MongoDB
- Base de datos: `equipo`
- Colección: `usuarios`

### 4. Verifica en MongoDB Atlas
1. Inicia sesión en tu cuenta de MongoDB Atlas
2. Ve a "Browse Collections"
3. Selecciona el cluster "Equipo"
4. En la base de datos `equipo`
5. Verás la colección `usuarios` con tus datos

## 📝 Estructura de la Colección

### Campos guardados:
- `name` - Nombre del usuario
- `email` - Correo electrónico
- `password` - Contraseña hasheada (bcrypt)
- `email_verified_at` - Fecha de verificación (si aplica)
- `two_factor_secret` - Código 2FA (si aplica)
- `two_factor_recovery_codes` - Códigos de recuperación
- `two_factor_confirmed_at` - Fecha de confirmación 2FA
- `remember_token` - Token de sesión
- `created_at` - Fecha de creación
- `updated_at` - Fecha de actualización

### Seguridad:
✅ Contraseñas hasheadas con bcrypt
✅ Tokens protegidos
✅ Fechas de verificación
✅ Atributos ocultos en respuestas JSON

## 🔍 Consultar Datos en MongoDB

### Usando MongoDB Shell:
```javascript
use equipo
db.usuarios.find()
db.usuarios.findOne({ email: "tu@correo.com" })
```

### Usando MongoDB Compass:
1. Conecta a tu cluster
2. Selecciona base de datos `equipo`
3. Abre la colección `usuarios`
4. Verás todos los usuarios registrados

## ✅ Estado Actual

- ✅ Base de datos: `equipo`
- ✅ Colección: `usuarios`
- ✅ Modelo configurado correctamente
- ✅ Conexión MongoDB activa
- ✅ Registro funcionando

¡Listo para guardar usuarios en MongoDB! 🎉

