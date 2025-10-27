# ✅ Registro con Pregunta Secreta Configurado

## 📋 Cambios Realizados

### 1. Modelo User (`app/Models/User.php`)
✅ Agregados campos al fillable:
- `pregunta_secreta`
- `respuesta_secreta`

### 2. Vista de Registro (`resources/views/auth/register.blade.php`)
✅ Agregados campos en el formulario:
- Select para pregunta secreta (con 6 opciones predefinidas)
- Input para respuesta secreta

### 3. Estilos CSS (`public/css/auth.css`)
✅ Estilos agregados para select:
- Mismo diseño que los inputs
- Indicador de flecha personalizado
- Efectos de focus con color púrpura

## 🗄️ Almacenamiento en MongoDB

### Colección: `usuario`

```json
{
  "_id": ObjectId("..."),
  "name": "Tu Nombre",
  "email": "tu@correo.com",
  "password": "$2y$10$...", // Hasheada
  "pregunta_secreta": "¿Cuál es el nombre de tu primera mascota?",
  "respuesta_secreta": "Rex", // Guardada en texto plano
  "email_verified_at": null,
  "created_at": ISODate("..."),
  "updated_at": ISODate("...")
}
```

## 📝 Preguntas Secretas Disponibles

1. ¿Cuál es el nombre de tu primera mascota?
2. ¿En qué ciudad naciste?
3. ¿Cuál es el nombre de tu mejor amigo de la infancia?
4. ¿Cuál era el nombre de tu escuela primaria?
5. ¿Cuál es tu comida favorita?
6. ¿Cuál es el nombre de tu primer profesor?

## 🎨 Diseño del Formulario

El formulario de registro ahora incluye:
- Campo Nombre
- Campo Email
- Campo Contraseña
- Campo Confirmar Contraseña
- Campo Pregunta Secreta (dropdown con 6 opciones)
- Campo Respuesta Secreta
- Botón Registrarse

## 🧪 Probar el Registro

1. Visita: http://localhost:8000/register
2. Completa todos los campos incluyendo:
   - Selecciona una pregunta secreta
   - Escribe tu respuesta
3. Envía el formulario
4. Verifica en MongoDB Atlas:
   - Base de datos: equipo
   - Colección: usuario
   - Verás los datos incluyendo pregunta y respuesta secreta

## 📍 Ubicación de los Datos

- ✅ Usuarios: Base `equipo` / Colección `usuario`
- ✅ Pregunta secreta: Guardada en cada usuario
- ✅ Respuesta secreta: Guardada en cada usuario
- ✅ Tokens de recuperación: Base `equipo` / Colección `recuperar-password`

## ✅ Estado Actual

✅ Modelo configurado para usar colección "usuario"
✅ Campos pregunta_secreta y respuesta_secreta agregados
✅ Vista de registro actualizada con los campos
✅ Estilos CSS aplicados al select
✅ Todo listo para guardar en MongoDB

═══ ═══ ═══ ═══ ═══ ═══ ═══

🎉 ¡Registro con Pregunta Secreta Listo!

Visita: http://localhost:8000/register

═══ ═══ ═══ ═══ ═══ ═══ ═══

