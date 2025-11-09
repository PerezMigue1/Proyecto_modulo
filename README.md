# Módulo de Usuario - Frontend

Frontend Vue.js 3 para el módulo de usuario. El backend está en **otro proyecto separado**.

## 🎨 Tecnología

- **Vue.js 3** - Framework frontend
- **Vue Router** - Navegación
- **Pinia** - Estado global
- **Axios** - Cliente HTTP para consumir APIs
- **Vite** - Build tool

## 🚀 Inicio Rápido

### Instalación

```bash
npm install
```

### Configuración

Crea un archivo `.env` en la raíz del proyecto:

```env
VITE_API_URL=http://localhost:8000/api
VITE_FRONTEND_URL=http://localhost:3000
```

**Nota**: Cambia `http://localhost:8000` por la URL de tu backend (que está en otro proyecto).

### Desarrollo

```bash
npm run dev
```

El frontend estará disponible en `http://localhost:3000`

## 📁 Estructura

```
equipo/
├── src/               # Código fuente
│   ├── views/         # Vistas (Login, Register, Dashboard)
│   ├── stores/        # Pinia stores (auth)
│   ├── services/      # Servicios API
│   ├── router/        # Vue Router
│   └── assets/        # CSS, imágenes
├── index.html         # HTML principal
├── package.json       # Dependencias
├── vite.config.js     # Configuración Vite
└── README.md          # Documentación
```

## 🔗 Backend

El backend está en **otro proyecto separado**. 

- **Backend URL**: Configurar en `VITE_API_URL` (ej: `http://localhost:8000/api`)
- **Comunicación**: APIs REST con tokens de autenticación
- **Base de datos**: MongoDB (compartida con backend)

## 📚 Características

- ✅ Login con email/password
- ✅ Registro de usuarios
- ✅ Login con Google OAuth
- ✅ Login con Facebook OAuth
- ✅ Recuperación de contraseña
- ✅ Dashboard protegido
- ✅ Manejo de tokens de autenticación

## 🛠️ Comandos

### Desarrollo
```bash
npm run dev
```

### Build
```bash
npm run build
```

### Preview
```bash
npm run preview
```

## 📡 APIs que Consume

El frontend consume estas APIs del backend:

### Públicas
- `POST /api/login` - Login
- `POST /api/register` - Registro
- `GET /api/preguntas-secretas` - Preguntas secretas
- `POST /api/password/verify-email` - Verificar email
- `POST /api/password/verify-answer` - Verificar respuesta
- `POST /api/password/update` - Actualizar contraseña

### Protegidas
- `GET /api/user` - Usuario actual
- `POST /api/logout` - Logout

### OAuth
- `GET /auth/google` - Redirect a Google
- `GET /auth/facebook` - Redirect a Facebook

## 🔐 Autenticación

- **Tokens**: Almacenados en `localStorage`
- **Headers**: `Authorization: Bearer {token}`
- **Validación**: Automática en cada request

## 🚀 Despliegue

### Build para Producción

```bash
npm run build
```

Los archivos se generarán en `dist/`

### Variables de Entorno en Producción

```env
VITE_API_URL=https://tu-backend.onrender.com/api
VITE_FRONTEND_URL=https://tu-frontend.onrender.com
```

## 📝 Notas Importantes

1. **Backend separado**: El backend está en otro proyecto. Asegúrate de que esté corriendo.
2. **URL del Backend**: Configura `VITE_API_URL` correctamente en `.env`
3. **CORS**: El backend debe tener CORS configurado para permitir requests del frontend
4. **OAuth**: Las URLs de OAuth se construyen automáticamente desde `VITE_API_URL`

## 📚 Documentación

- `ARQUITECTURA.md` - Arquitectura completa del proyecto

## 🎯 Resumen

- **Proyecto**: Solo frontend (Vue.js)
- **Backend**: En otro proyecto separado
- **Comunicación**: APIs REST
- **Autenticación**: Tokens (Sanctum)

## ✅ Verificación

Para verificar que todo funciona:

1. ✅ Backend está corriendo
2. ✅ Frontend está corriendo (`npm run dev`)
3. ✅ `.env` configurado con `VITE_API_URL`
4. ✅ Puedes hacer login y registro
5. ✅ OAuth funciona (Google, Facebook)
