# 🔐 Módulo de Usuario - Frontend

Frontend moderno desarrollado con Vue.js 3 para el sistema de autenticación y gestión de usuarios. Este proyecto forma parte de un sistema completo de módulo de usuario con múltiples métodos de autenticación y cifrado.

## 📋 Tabla de Contenidos

- [Descripción](#-descripción)
- [Características](#-características)
- [Tecnologías](#-tecnologías)
- [Requisitos](#-requisitos)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Uso](#-uso)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [APIs](#-apis-que-consume)
- [Autenticación](#-autenticación)
- [Despliegue](#-despliegue)
- [Comandos](#-comandos)
- [Solución de Problemas](#-solución-de-problemas)
- [Documentación](#-documentación)
- [Contribución](#-contribución)

## 📖 Descripción

Este es el frontend del **Módulo de Usuario**, una aplicación web desarrollada como parte de una práctica de clase que implementa un sistema completo de autenticación con múltiples métodos de login, recuperación de contraseña y cifrado de datos.

El proyecto está diseñado como una **Single Page Application (SPA)** que se comunica con un backend Laravel a través de APIs REST. El backend está en un proyecto separado.

### Propósito del Proyecto

Definición de la práctica de clase – Módulo de usuario con métodos de autenticación y cifrado.

**Integrantes del Equipo:**
- Ontiveros Sanjuan Diana Monserrat - 20230019
- Flores cervantes Elizabeth - 20230015
- Martínez Ramírez Karla Yoselin – 20221078
- Hernández Valdes Francisco - 20230079
- Pérez de la Cruz Miguel Ángel - 20230091
- Ontiveros García Axali Jerusalén - 20230039

## ✨ Características

- ✅ **Login con email/password** - Autenticación tradicional
- ✅ **Registro de usuarios** - Con validación de datos
- ✅ **Login con Google OAuth** - Autenticación social
- ✅ **Login con Facebook OAuth** - Autenticación social
- ✅ **Recuperación de contraseña** - Con preguntas secretas
- ✅ **Dashboard protegido** - Rutas con autenticación
- ✅ **Manejo de tokens JWT** - Autenticación stateless
- ✅ **Interfaz moderna y responsive** - Diseño atractivo
- ✅ **Manejo de errores** - Mensajes claros al usuario
- ✅ **Validación de formularios** - En tiempo real

## 🛠️ Tecnologías

### Frontend

- **[Vue.js 3](https://vuejs.org/)** (^3.5.13) - Framework JavaScript progresivo
- **[Vue Router](https://router.vuejs.org/)** (^4.4.5) - Router oficial para Vue.js
- **[Pinia](https://pinia.vuejs.org/)** (^2.2.6) - Store de estado para Vue.js
- **[Axios](https://axios-http.com/)** (^1.7.9) - Cliente HTTP para APIs
- **[Vite](https://vitejs.dev/)** (^7.0.4) - Build tool y servidor de desarrollo

### Backend (Proyecto Separado)

- **Laravel** - Framework PHP
- **JWT (tymon/jwt-auth)** - Autenticación con tokens
- **Laravel Socialite** - OAuth con Google y Facebook
- **MongoDB** - Base de datos NoSQL

## 📦 Requisitos

### Software Necesario

- **Node.js** >= 20.19.0
  - Descargar desde: https://nodejs.org/
  - Verificar: `node --version`

- **npm** >= 10.0.0
  - Viene incluido con Node.js
  - Verificar: `npm --version`

- **Git** (opcional)
  - Para clonar el repositorio
  - Descargar desde: https://git-scm.com/

### Servicios Externos

- **Backend API** - Debe estar corriendo y accesible
  - Desarrollo: `http://localhost:8000/api`
  - Producción: `https://backend-equipo.onrender.com/api`

## 🚀 Instalación

### Opción 1: Instalación Rápida

```bash
# 1. Clonar el repositorio
git clone https://github.com/PerezMigue1/Proyecto_modulo.git
cd equipo

# 2. Instalar dependencias
npm install

# 3. Crear archivo .env
# Windows (PowerShell):
New-Item .env
# Linux/Mac:
touch .env

# 4. Configurar .env (ver sección Configuración)

# 5. Iniciar servidor de desarrollo
npm run dev
```

### Opción 2: Instalación Detallada

Para una guía completa paso a paso, consulta **[INSTALACION.md](./INSTALACION.md)**

## ⚙️ Configuración

### Variables de Entorno

Crea un archivo `.env` en la raíz del proyecto con las siguientes variables:

#### Desarrollo

```env
# URL del backend API
VITE_API_URL=http://localhost:8000/api

# URL del frontend
VITE_FRONTEND_URL=http://localhost:3000
```

#### Producción

```env
# URL del backend API
VITE_API_URL=https://backend-equipo.onrender.com/api

# URL del frontend
VITE_FRONTEND_URL=https://modulo-usuario.netlify.app
```

### Configuración del Puerto

El servidor de desarrollo usa el puerto **3000** por defecto. Para cambiar el puerto:

1. Edita `vite.config.js`:
```javascript
server: {
  port: 3000  // Cambia este número
}
```

2. O inicia con un puerto específico:
```bash
npm run dev -- --port 3001
```

## 💻 Uso

### Desarrollo

```bash
# Iniciar servidor de desarrollo
npm run dev
```

El servidor se iniciará en `http://localhost:3000` con:
- Hot Module Replacement (HMR)
- Recarga automática del navegador
- Errores visibles en la consola

### Producción

```bash
# Compilar para producción
npm run build

# Previsualizar el build
npm run preview
```

Los archivos optimizados se generarán en la carpeta `dist/`

## 📁 Estructura del Proyecto

```
equipo/
├── src/                      # Código fuente
│   ├── views/                # Vistas (páginas)
│   │   ├── Login.vue         # Página de login
│   │   ├── Register.vue      # Página de registro
│   │   ├── Dashboard.vue     # Dashboard principal
│   │   ├── AuthCallback.vue  # Callback de OAuth
│   │   └── ForgotPassword.vue # Recuperación de contraseña
│   ├── stores/               # Pinia stores
│   │   └── auth.js           # Store de autenticación
│   ├── services/             # Servicios API
│   │   ├── api.js            # Cliente Axios configurado
│   │   ├── passwordRecovery.js
│   │   └── secretQuestions.js
│   ├── router/               # Vue Router
│   │   └── index.js          # Configuración de rutas
│   ├── assets/               # Assets estáticos
│   │   └── auth.css          # Estilos CSS
│   ├── App.vue               # Componente raíz
│   ├── main.js               # Punto de entrada
│   └── style.css             # Estilos globales
├── index.html                # HTML principal
├── package.json              # Dependencias y scripts
├── vite.config.js            # Configuración de Vite
├── .env                      # Variables de entorno (crear)
├── netlify.toml              # Configuración de Netlify
└── README.md                 # Este archivo
```

## 📡 APIs que Consume

El frontend consume las siguientes APIs del backend:

### Endpoints Públicos

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `POST` | `/api/login` | Autenticación con email/password |
| `POST` | `/api/register` | Registro de nuevo usuario |
| `GET` | `/api/preguntas-secretas` | Lista de preguntas secretas |
| `POST` | `/api/password/verify-email` | Verificar email para recuperación |
| `POST` | `/api/password/verify-answer` | Verificar respuesta secreta |
| `POST` | `/api/password/update` | Actualizar contraseña |

### Endpoints Protegidos

| Método | Endpoint | Descripción | Autenticación |
|--------|----------|-------------|---------------|
| `GET` | `/api/user` | Obtener usuario actual | ✅ Requerida |
| `POST` | `/api/logout` | Cerrar sesión | ✅ Requerida |

### Endpoints OAuth

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/auth/google` | Redirige a Google OAuth |
| `GET` | `/auth/facebook` | Redirige a Facebook OAuth |

## 🔐 Autenticación

### Flujo de Autenticación

1. **Login tradicional**: Usuario ingresa email/password → Backend valida → Retorna token JWT
2. **OAuth**: Usuario hace clic en botón → Redirige a proveedor → Callback con token → Guarda token
3. **Token almacenado**: Se guarda en `localStorage` y se envía en cada request

### Almacenamiento

- **Tokens**: Almacenados en `localStorage` con la clave `token`
- **Headers**: Se envía automáticamente como `Authorization: Bearer {token}`
- **Validación**: Automática en cada request mediante interceptores de Axios

### Rutas Protegidas

Las rutas protegidas requieren autenticación:
- `/dashboard` - Dashboard principal
- Cualquier ruta con `meta: { requiresAuth: true }`

Si no hay token, el usuario es redirigido a `/login`

## 🚀 Despliegue

### Netlify (Recomendado)

Este proyecto está configurado para desplegarse en Netlify.

#### Pasos:

1. **Sube el proyecto a GitHub**
   ```bash
   git add .
   git commit -m "Initial commit"
   git push origin main
   ```

2. **Conecta con Netlify**:
   - Ve a [Netlify](https://netlify.com)
   - Click en "Add new site" → "Import an existing project"
   - Selecciona tu repositorio de GitHub

3. **Configura el build**:
   - **Build command**: `npm install && npm run build`
   - **Publish directory**: `dist`

4. **Variables de entorno**:
   ```
   VITE_API_URL=https://backend-equipo.onrender.com/api
   VITE_FRONTEND_URL=https://tu-frontend.netlify.app
   ```

5. **Despliega**: Click en "Deploy site"

Para más detalles, ver `DESPLIEGUE_NETLIFY_RENDER.md`

### Otros Proveedores

El proyecto puede desplegarse en cualquier servicio que soporte aplicaciones estáticas:
- **Vercel**
- **GitHub Pages**
- **Firebase Hosting**
- **AWS S3 + CloudFront**

## 🛠️ Comandos

### Desarrollo

```bash
# Iniciar servidor de desarrollo
npm run dev

# Iniciar en puerto específico
npm run dev -- --port 3001
```

### Producción

```bash
# Compilar para producción
npm run build

# Previsualizar el build
npm run preview
```

### Utilidades

```bash
# Verificar versión de Node.js
node --version

# Verificar versión de npm
npm --version

# Ver dependencias instaladas
npm list --depth=0

# Actualizar dependencias
npm update
```

## 🐛 Solución de Problemas

### Error: "Cannot find module"

```bash
# Eliminar node_modules y reinstalar
rm -rf node_modules package-lock.json
npm install
```

**Windows:**
```bash
rmdir /s node_modules
del package-lock.json
npm install
```

### Error: "Port 3000 is already in use"

```bash
# Usar otro puerto
npm run dev -- --port 3001
```

O detener el proceso que está usando el puerto 3000.

### Error: "VITE_API_URL is not defined"

**Solución:**
1. Verifica que el archivo `.env` existe en la raíz del proyecto
2. Verifica que contiene `VITE_API_URL=...`
3. Reinicia el servidor de desarrollo

### Error: "Network Error" o "CORS Error"

**Solución:**
1. Verifica que el backend esté corriendo
2. Verifica que la URL en `VITE_API_URL` sea correcta
3. Verifica que el backend tenga CORS configurado

### Error: "401 Unauthorized"

**Solución:**
1. Verifica que el token esté guardado en `localStorage`
2. Verifica que el backend esté validando correctamente el token
3. Intenta hacer login nuevamente

## 📚 Documentación

- **[INSTALACION.md](./INSTALACION.md)** - ⭐ Guía completa de instalación y configuración
- `DESPLIEGUE_NETLIFY_RENDER.md` - Guía de despliegue (Netlify + Render)
- `DESPLIEGUE.md` - Guía completa de despliegue
- `DESPLIEGUE_RAPIDO.md` - Guía rápida de despliegue
- `ARQUITECTURA.md` - Arquitectura completa del proyecto
- `COMO_INICIAR.md` - Cómo iniciar el proyecto
- `INICIO_RAPIDO.md` - Inicio rápido

## 📝 Notas Importantes

1. **Backend separado**: El backend está en otro proyecto. Asegúrate de que esté corriendo antes de usar el frontend.

2. **Variables de entorno**: Las variables que empiezan con `VITE_` son expuestas al cliente. No pongas información sensible aquí.

3. **CORS**: El backend debe tener CORS configurado para permitir requests desde:
   - Desarrollo: `http://localhost:3000`
   - Producción: Tu dominio de Netlify

4. **OAuth**: Para que funcione el login con Google/Facebook:
   - El backend debe estar configurado con las credenciales correctas
   - Las URLs de callback deben estar configuradas en los proveedores OAuth

5. **Tokens JWT**: Los tokens tienen un tiempo de expiración. Si expiran, el usuario debe hacer login nuevamente.

## 🎯 Resumen del Proyecto

| Aspecto | Detalle |
|---------|---------|
| **Tipo** | Frontend SPA (Single Page Application) |
| **Framework** | Vue.js 3 |
| **Build Tool** | Vite |
| **Estado** | Pinia |
| **Router** | Vue Router |
| **HTTP Client** | Axios |
| **Backend** | Laravel (proyecto separado) |
| **Autenticación** | JWT (tymon/jwt-auth) |
| **Base de datos** | MongoDB (en backend) |
| **Despliegue Frontend** | Netlify |
| **Despliegue Backend** | Render |

## ✅ Checklist de Verificación

Antes de ejecutar el proyecto, verifica:

- [ ] Node.js >= 20.19.0 instalado
- [ ] npm >= 10.0.0 instalado
- [ ] Dependencias instaladas (`npm install`)
- [ ] Archivo `.env` creado y configurado
- [ ] Backend está corriendo y accesible
- [ ] Puerto 3000 disponible (o configurado otro puerto)
- [ ] CORS configurado en el backend

Para verificar que todo funciona:

1. ✅ Backend está corriendo
2. ✅ Frontend está corriendo (`npm run dev`)
3. ✅ `.env` configurado con `VITE_API_URL`
4. ✅ Puedes hacer login y registro
5. ✅ OAuth funciona (Google, Facebook)
6. ✅ Dashboard se muestra correctamente

## 🤝 Contribución

Este es un proyecto académico desarrollado como práctica de clase. Si deseas contribuir:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto es parte de una práctica académica. Todos los derechos reservados.

## 👥 Autores

- **Ontiveros Sanjuan Diana Monserrat** - 20230019
- **Flores cervantes Elizabeth** - 20230015
- **Martínez Ramírez Karla Yoselin** – 20221078
- **Hernández Valdes Francisco** - 20230079
- **Pérez de la Cruz Miguel Ángel** - 20230091
- **Ontiveros García Axali Jerusalén** - 20230039

## 🔗 Enlaces Útiles

- [Vue.js Documentation](https://vuejs.org/)
- [Vue Router Documentation](https://router.vuejs.org/)
- [Pinia Documentation](https://pinia.vuejs.org/)
- [Vite Documentation](https://vitejs.dev/)
- [Axios Documentation](https://axios-http.com/)
- [Laravel Documentation](https://laravel.com/docs)

---

**Desarrollado con ❤️ por el equipo de Módulo de Usuario**
