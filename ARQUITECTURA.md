# Arquitectura del Proyecto

## 🏗️ Arquitectura: Backend API + Frontend Vue.js

### 📡 Backend - Laravel API (Solo APIs)

**Ubicación**: `backend/`

**Tecnología**: Laravel (PHP)

**Función**: Proporcionar APIs REST para el frontend

#### Características:
- ✅ **Solo APIs** - No tiene vistas, solo endpoints JSON
- ✅ **Laravel Sanctum** - Autenticación con tokens
- ✅ **CORS configurado** - Permite requests del frontend
- ✅ **MongoDB** - Base de datos
- ✅ **OAuth** - Google y Facebook (solo redirecciones)

#### Endpoints API:
```
POST /api/login              - Login
POST /api/register           - Registro
GET  /api/preguntas-secretas - Preguntas secretas
POST /api/password/verify-email   - Verificar email
POST /api/password/verify-answer  - Verificar respuesta
POST /api/password/update    - Actualizar contraseña
GET  /api/user               - Usuario actual (protegido)
POST /api/logout             - Logout (protegido)
```

#### OAuth (redirecciones):
```
GET /auth/google             - Redirect a Google
GET /auth/google/callback    - Callback de Google
GET /auth/facebook           - Redirect a Facebook
GET /auth/facebook/callback  - Callback de Facebook
```

#### Respuestas:
- Todas las respuestas son **JSON**
- No hay vistas Blade
- No hay HTML renderizado en el servidor

---

### 🎨 Frontend - Vue.js

**Ubicación**: `frontend/`

**Tecnología**: Vue.js 3

**Función**: Interfaz de usuario que consume las APIs del backend

#### Características:
- ✅ **Vue 3** - Framework frontend
- ✅ **Vue Router** - Navegación
- ✅ **Pinia** - Estado global
- ✅ **Axios** - Cliente HTTP para consumir APIs
- ✅ **Vite** - Build tool

#### Estructura:
```
frontend/
├── src/
│   ├── views/          # Vistas (Login, Register, Dashboard)
│   ├── stores/         # Pinia stores (auth)
│   ├── services/       # Servicios API (axios)
│   ├── router/         # Vue Router
│   └── assets/         # CSS, imágenes
└── package.json
```

#### Funcionalidades:
- Login con email/password
- Registro de usuarios
- Login con Google OAuth
- Login con Facebook OAuth
- Recuperación de contraseña
- Dashboard protegido
- Manejo de tokens de autenticación

#### Consumo de APIs:
- Todas las peticiones van a `http://localhost:8000/api`
- Tokens almacenados en `localStorage`
- Headers: `Authorization: Bearer {token}`

---

## 🔄 Flujo de Comunicación

### 1. Login Normal
```
Frontend (Vue) → POST /api/login → Backend (Laravel)
Backend → JSON {user, token} → Frontend
Frontend → Guarda token en localStorage
```

### 2. OAuth (Google/Facebook)
```
Frontend → Click "Login with Google"
Frontend → Redirect a /auth/google (Backend)
Backend → Redirect a Google OAuth
Google → Redirect a /auth/google/callback (Backend)
Backend → Crea usuario, genera token
Backend → Redirect a Frontend: /auth/callback?token=xxx
Frontend → Guarda token, redirige a Dashboard
```

### 3. Request Autenticado
```
Frontend → GET /api/user
Header: Authorization: Bearer {token}
Backend → Valida token (Sanctum)
Backend → JSON {user} → Frontend
```

---

## 📦 Separación de Responsabilidades

### Backend (Laravel)
- ✅ Autenticación y autorización
- ✅ Validación de datos
- ✅ Lógica de negocio
- ✅ Acceso a base de datos
- ✅ Generación de tokens
- ✅ OAuth (Google, Facebook)

### Frontend (Vue.js)
- ✅ Interfaz de usuario
- ✅ Navegación y routing
- ✅ Estado local
- ✅ Consumo de APIs
- ✅ Manejo de tokens
- ✅ Validación de formularios (cliente)

---

## 🔐 Autenticación

### Tokens (Sanctum)
- Tokens generados por Laravel Sanctum
- Almacenados en `localStorage` del navegador
- Enviados en header: `Authorization: Bearer {token}`
- Validados en cada request protegido

### OAuth
- Google OAuth
- Facebook OAuth
- Redirecciones manejadas por el backend
- Tokens generados después de autenticación exitosa

---

## 🗄️ Base de Datos

**MongoDB** - Compartida entre backend y frontend

- **Base de datos**: `equipo`
- **Colección usuarios**: `usuario`
- **Colección preguntas secretas**: `recuperar-password`

---

## 🚀 Despliegue

### Backend
- **Render.com** (Docker)
- **URL**: `https://tu-backend.onrender.com`
- **APIs**: `https://tu-backend.onrender.com/api`

### Frontend
- **Render.com, Vercel, o Netlify**
- **URL**: `https://tu-frontend.onrender.com`
- **API URL**: Configurar en `.env` del frontend

---

## ✅ Resumen

- **Backend**: Laravel API (solo JSON, sin vistas)
- **Frontend**: Vue.js 3 (consume APIs)
- **Comunicación**: HTTP/REST
- **Autenticación**: Tokens (Sanctum)
- **Base de datos**: MongoDB (compartida)
- **Separación**: Backend y Frontend completamente separados

---

## 📚 Documentación

- `backend/README.md` - Documentación del backend
- `frontend/README.md` - Documentación del frontend
- `backend/INSTALACION.md` - Instrucciones de instalación
- `INSTRUCCIONES_FINALES.md` - Instrucciones completas

---

## 🎯 Conclusión

✅ **Backend**: Laravel API pura (solo APIs, sin vistas)
✅ **Frontend**: Vue.js 3 (consume APIs del backend)
✅ **Separación completa**: Backend y Frontend son proyectos independientes
✅ **Comunicación**: APIs REST con tokens de autenticación

