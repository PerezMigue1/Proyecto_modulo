# 🚀 Inicio Rápido - Frontend

## ✅ Estado

El frontend está **corriendo correctamente**.

## 📍 Acceso

El frontend está disponible en:
- **URL Local**: http://localhost:3000
- **Puerto**: 3000

## 🔧 Comandos

### Iniciar el servidor
```bash
npm run dev
```

### Detener el servidor
Presiona `Ctrl + C` en la terminal

### Build para producción
```bash
npm run build
```

## ⚠️ Importante

1. **Backend debe estar corriendo**: El backend debe estar en otro proyecto y corriendo en `http://localhost:8000` (o la URL que configuraste)

2. **Variables de entorno**: Crea un archivo `.env` en la raíz:
   ```env
   VITE_API_URL=http://localhost:8000/api
   VITE_FRONTEND_URL=http://localhost:3000
   ```

3. **CORS**: El backend debe tener CORS configurado para permitir requests del frontend

## 🎯 Pruebas

1. Abre tu navegador en http://localhost:3000
2. Deberías ver la página de login
3. Puedes probar:
   - Login con email/password
   - Registro de usuarios
   - Login con Google OAuth
   - Login con Facebook OAuth
   - Recuperación de contraseña

## 📝 Notas

- El servidor se recarga automáticamente cuando haces cambios
- Los errores se muestran en la consola del navegador
- Verifica la consola del navegador (F12) para ver errores

