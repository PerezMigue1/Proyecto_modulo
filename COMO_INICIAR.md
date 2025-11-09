# 🚀 Cómo Iniciar el Frontend

## 📋 Pasos para Iniciar

### 1. Abrir Terminal

Abre una terminal (PowerShell, CMD, o Git Bash) en la carpeta del proyecto:
```
C:\Users\Miguel Angel\equipo
```

### 2. Instalar Dependencias (solo la primera vez)

Si es la primera vez que corres el proyecto, instala las dependencias:

```bash
npm install
```

### 3. Configurar Variables de Entorno

Crea un archivo `.env` en la raíz del proyecto con:

```env
VITE_API_URL=http://localhost:8000/api
VITE_FRONTEND_URL=http://localhost:3000
```

**Nota**: Cambia `http://localhost:8000` por la URL de tu backend si está en otro puerto.

### 4. Iniciar el Servidor de Desarrollo

Ejecuta el siguiente comando:

```bash
npm run dev
```

### 5. Abrir en el Navegador

Después de ejecutar `npm run dev`, verás algo como:

```
  VITE v7.0.4  ready in 500 ms

  ➜  Local:   http://localhost:3000/
  ➜  Network: use --host to expose
  ➜  press h + enter to show help
```

Abre tu navegador y ve a: **http://localhost:3000**

## 🛑 Detener el Servidor

Para detener el servidor, presiona `Ctrl + C` en la terminal.

## ⚠️ Requisitos Previos

1. **Node.js instalado**: Necesitas Node.js (versión 18 o superior)
   - Verifica con: `node --version`
   - Descarga desde: https://nodejs.org/

2. **Backend corriendo**: El backend debe estar corriendo en otro proyecto
   - Por defecto: `http://localhost:8000`
   - El backend debe tener CORS configurado

3. **Dependencias instaladas**: Ejecuta `npm install` si es la primera vez

## 🔧 Comandos Útiles

```bash
# Instalar dependencias
npm install

# Iniciar servidor de desarrollo
npm run dev

# Build para producción
npm run build

# Preview del build de producción
npm run preview
```

## 📝 Troubleshooting

### Error: "Cannot find module"
Ejecuta: `npm install`

### Error: "Port 3000 already in use"
Cierra otros procesos que usen el puerto 3000 o cambia el puerto en `vite.config.js`

### Error: "Network error" al conectar con el backend
- Verifica que el backend esté corriendo
- Verifica la URL en `.env`
- Verifica que CORS esté configurado en el backend

## ✅ Verificación

Una vez iniciado, deberías ver:
- ✅ La página de login en http://localhost:3000
- ✅ Sin errores en la consola del navegador (F12)
- ✅ El servidor corriendo en la terminal

