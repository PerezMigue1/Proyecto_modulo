# 📦 Guía de Instalación y Configuración del Proyecto

Esta guía contiene todos los pasos necesarios para instalar y ejecutar el proyecto desde cero.

## 📋 Requisitos Previos

### Software Necesario

1. **Node.js** (versión 20.19.0 o superior)
   - Descargar desde: https://nodejs.org/
   - Verificar instalación: `node --version` (debe ser >= 20.19.0)

2. **npm** (versión 10.0.0 o superior)
   - Viene incluido con Node.js
   - Verificar instalación: `npm --version` (debe ser >= 10.0.0)

3. **Git** (opcional, para clonar el repositorio)
   - Descargar desde: https://git-scm.com/
   - Verificar instalación: `git --version`

### Cuentas y Servicios Externos (Opcional)

- **Backend API**: Debe estar corriendo y accesible
  - URL de desarrollo: `http://localhost:8000/api`
  - URL de producción: `https://backend-equipo.onrender.com/api`

## 🚀 Instalación Paso a Paso

### 1. Clonar o Descargar el Proyecto

```bash
# Si tienes acceso al repositorio Git
git clone https://github.com/PerezMigue1/Proyecto_modulo.git

# O descarga el proyecto como ZIP y extráelo
```

### 2. Navegar al Directorio del Proyecto

```bash
cd equipo
# O el nombre de la carpeta donde está el proyecto
```

### 3. Instalar Dependencias

```bash
npm install
```

Este comando instalará todas las dependencias listadas en `package.json`:
- **Vue.js 3** (^3.5.13)
- **Vue Router** (^4.4.5)
- **Pinia** (^2.2.6)
- **Axios** (^1.7.9)
- **Vite** (^7.0.4) - Build tool
- **@vitejs/plugin-vue** (^6.0.0) - Plugin de Vite para Vue

### 4. Configurar Variables de Entorno

Crea un archivo `.env` en la raíz del proyecto:

```bash
# En Windows (PowerShell)
New-Item .env

# En Windows (CMD)
type nul > .env

# En Linux/Mac
touch .env
```

Edita el archivo `.env` y agrega:

```env
# URL del backend API
VITE_API_URL=http://localhost:8000/api

# URL del frontend (para desarrollo)
VITE_FRONTEND_URL=http://localhost:3000
```

**Para producción**, usa:
```env
VITE_API_URL=https://backend-equipo.onrender.com/api
VITE_FRONTEND_URL=https://modulo-usuario.netlify.app
```

### 5. Verificar la Instalación

```bash
# Verificar que Node.js está instalado
node --version

# Verificar que npm está instalado
npm --version

# Verificar que las dependencias se instalaron
npm list --depth=0
```

## 🎯 Comandos Disponibles

### Desarrollo

```bash
# Iniciar servidor de desarrollo
npm run dev
```

El servidor se iniciará en `http://localhost:3000`

**Características:**
- Hot Module Replacement (HMR) - Los cambios se reflejan automáticamente
- Recarga automática del navegador
- Errores visibles en la consola del navegador

### Build para Producción

```bash
# Compilar el proyecto para producción
npm run build
```

Esto generará los archivos optimizados en la carpeta `dist/`

**Características:**
- Minificación de código
- Optimización de assets
- Tree-shaking (eliminación de código no usado)

### Preview de Producción

```bash
# Previsualizar el build de producción localmente
npm run preview
```

Útil para probar cómo se verá el proyecto en producción antes de desplegarlo.

## 📦 Dependencias del Proyecto

### Dependencias de Producción

| Paquete | Versión | Descripción |
|---------|---------|-------------|
| `vue` | ^3.5.13 | Framework JavaScript para interfaces de usuario |
| `vue-router` | ^4.4.5 | Router oficial para Vue.js |
| `pinia` | ^2.2.6 | Store de estado para Vue.js |
| `axios` | ^1.7.9 | Cliente HTTP para hacer peticiones a APIs |

### Dependencias de Desarrollo

| Paquete | Versión | Descripción |
|---------|---------|-------------|
| `vite` | ^7.0.4 | Build tool y servidor de desarrollo |
| `@vitejs/plugin-vue` | ^6.0.0 | Plugin oficial de Vite para Vue.js |

## 🔧 Configuración Adicional

### Puerto del Servidor

El servidor de desarrollo está configurado para usar el puerto **3000** por defecto.

Si necesitas cambiar el puerto, edita `vite.config.js`:

```javascript
server: {
  port: 3000  // Cambia este número
}
```

O inicia el servidor con un puerto específico:

```bash
npm run dev -- --port 3001
```

### Alias de Importación

El proyecto usa el alias `@` para importar desde la carpeta `src`:

```javascript
// En lugar de:
import { useAuthStore } from '../../../stores/auth'

// Puedes usar:
import { useAuthStore } from '@/stores/auth'
```

Esto está configurado en `vite.config.js`.

## 🐛 Solución de Problemas

### Error: "Cannot find module"

```bash
# Eliminar node_modules y reinstalar
rm -rf node_modules package-lock.json
npm install
```

En Windows:
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

Asegúrate de que:
1. El archivo `.env` existe en la raíz del proyecto
2. El archivo `.env` contiene `VITE_API_URL=...`
3. Reinicias el servidor de desarrollo después de crear/modificar `.env`

### Error: "Network Error" o "CORS Error"

Verifica que:
1. El backend esté corriendo
2. La URL en `VITE_API_URL` sea correcta
3. El backend tenga CORS configurado para permitir tu origen

## 📁 Estructura del Proyecto

```
equipo/
├── src/                    # Código fuente
│   ├── views/              # Vistas (páginas)
│   │   ├── Login.vue
│   │   ├── Register.vue
│   │   ├── Dashboard.vue
│   │   ├── AuthCallback.vue
│   │   └── ForgotPassword.vue
│   ├── stores/            # Pinia stores
│   │   └── auth.js        # Store de autenticación
│   ├── services/          # Servicios API
│   │   ├── api.js         # Cliente Axios configurado
│   │   ├── passwordRecovery.js
│   │   └── secretQuestions.js
│   ├── router/            # Vue Router
│   │   └── index.js       # Configuración de rutas
│   ├── assets/            # Assets estáticos
│   │   └── auth.css       # Estilos CSS
│   ├── App.vue            # Componente raíz
│   ├── main.js            # Punto de entrada
│   └── style.css          # Estilos globales
├── index.html             # HTML principal
├── package.json           # Dependencias y scripts
├── vite.config.js         # Configuración de Vite
├── .env                   # Variables de entorno (crear)
└── README.md              # Documentación principal
```

## ✅ Checklist de Verificación

Antes de ejecutar el proyecto, verifica:

- [ ] Node.js >= 20.19.0 instalado
- [ ] npm >= 10.0.0 instalado
- [ ] Dependencias instaladas (`npm install`)
- [ ] Archivo `.env` creado y configurado
- [ ] Backend está corriendo y accesible
- [ ] Puerto 3000 disponible (o configurado otro puerto)

## 🚀 Inicio Rápido (Resumen)

```bash
# 1. Clonar/descargar el proyecto
git clone https://github.com/PerezMigue1/Proyecto_modulo.git
cd equipo

# 2. Instalar dependencias
npm install

# 3. Crear archivo .env
echo "VITE_API_URL=http://localhost:8000/api" > .env
echo "VITE_FRONTEND_URL=http://localhost:3000" >> .env

# 4. Iniciar servidor de desarrollo
npm run dev
```

## 📚 Recursos Adicionales

- [Documentación de Vue.js](https://vuejs.org/)
- [Documentación de Vue Router](https://router.vuejs.org/)
- [Documentación de Pinia](https://pinia.vuejs.org/)
- [Documentación de Vite](https://vitejs.dev/)
- [Documentación de Axios](https://axios-http.com/)

## 💡 Notas Importantes

1. **Backend Separado**: Este proyecto es solo el frontend. El backend debe estar corriendo en otro proyecto.

2. **Variables de Entorno**: Las variables que empiezan con `VITE_` son expuestas al cliente. No pongas información sensible aquí.

3. **CORS**: El backend debe tener CORS configurado para permitir requests desde `http://localhost:3000` (desarrollo) o tu dominio de producción.

4. **OAuth**: Para que funcione el login con Google/Facebook, el backend debe estar configurado con las credenciales correctas de OAuth.

## 🆘 Soporte

Si tienes problemas:

1. Revisa la sección "Solución de Problemas" arriba
2. Verifica los logs en la consola del navegador (F12)
3. Verifica los logs del servidor de desarrollo
4. Asegúrate de que el backend esté funcionando correctamente

