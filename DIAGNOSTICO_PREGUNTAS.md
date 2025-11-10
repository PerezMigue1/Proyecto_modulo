# Diagnostico de Preguntas Secretas

## Problema
Las preguntas secretas no aparecen en el formulario de registro.

## URLs del Backend
- **API Base**: `https://backend-equipo.onrender.com/api`
- **Preguntas Secretas**: `https://backend-equipo.onrender.com/api/preguntas-secretas`
- **Usuarios**: `https://backend-equipo.onrender.com/api/usuarios/list`

## Posibles Causas

### 1. Backend dormido en Render
Los servicios gratuitos de Render se duermen después de 15 minutos de inactividad.
- **Sintoma**: La primera peticion puede tardar mucho o fallar
- **Solucion**: Hacer una peticion manual para despertar el servicio
- **Prueba**: Abrir `https://backend-equipo.onrender.com/api/preguntas-secretas` en el navegador

### 2. Estructura de respuesta incorrecta
El backend puede estar devolviendo datos en un formato diferente.

**Formato esperado:**
```json
{
  "preguntas": [
    {
      "_id": "...",
      "pregunta": "¿Cuál es el nombre de tu mascota?"
    }
  ]
}
```

**O formato alternativo:**
```json
[
  {
    "_id": "...",
    "pregunta": "¿Cuál es el nombre de tu mascota?"
  }
]
```

### 3. Error de CORS
El backend puede no estar permitiendo peticiones desde el frontend de Netlify.

**Verificar en el backend:**
- `config/cors.php` debe incluir el dominio de Netlify
- `allowed_origins` debe incluir `https://modulo-usuario.netlify.app` o el dominio correcto

### 4. Error en la ruta del backend
La ruta `/api/preguntas-secretas` puede no estar definida correctamente.

**Verificar en el backend:**
- `routes/api.php` debe tener la ruta definida
- El controlador debe existir y funcionar correctamente

## Como Diagnosticar

### 1. Abrir la consola del navegador (F12)
Al cargar la pagina de registro, buscar:
- `📋 Obteniendo preguntas secretas...`
- `✅ Preguntas secretas recibidas:` o `❌ Error fetching secret questions:`

### 2. Verificar la respuesta del backend
En la consola, buscar:
- `✅ Response status:` (debe ser 200)
- `✅ Response data:` (debe mostrar las preguntas)
- `✅ Response data type:` (debe ser "object")
- `✅ Is array:` (true o false segun la estructura)

### 3. Probar el endpoint directamente
Abrir en el navegador:
```
https://backend-equipo.onrender.com/api/preguntas-secretas
```

Debe mostrar un JSON con las preguntas.

### 4. Verificar la URL de la API
En la consola, buscar:
- `🔗 API URL:` (debe ser `https://backend-equipo.onrender.com/api`)
- `📋 URL completa:` (debe ser `https://backend-equipo.onrender.com/api/preguntas-secretas`)

## Soluciones

### Si el backend esta dormido
1. Abrir `https://backend-equipo.onrender.com` en el navegador
2. Esperar a que cargue (puede tardar 30-60 segundos)
3. Recargar la pagina de registro

### Si hay error de CORS
1. Verificar que `config/cors.php` en el backend incluya el dominio de Netlify
2. Verificar que el backend este usando el middleware de CORS
3. Verificar que `APP_URL` en el backend sea correcto

### Si la estructura de respuesta es diferente
1. Verificar el controlador `SecretQuestionController.php` en el backend
2. Verificar que devuelva las preguntas en el formato correcto
3. Actualizar `src/services/secretQuestions.js` si es necesario

### Si la ruta no existe
1. Verificar que `routes/api.php` tenga la ruta definida
2. Verificar que el controlador exista y funcione
3. Verificar que el middleware de autenticacion no este bloqueando la ruta (debe ser publica)

## Logs del Frontend

El frontend ahora muestra:
- Estado de carga: "Cargando preguntas..." mientras se cargan
- Mensaje de error si no se pueden cargar
- Logs detallados en la consola del navegador

## Siguiente Paso

1. Abrir la aplicacion en Netlify
2. Abrir la consola del navegador (F12)
3. Ir a la pagina de registro
4. Revisar los logs en la consola
5. Compartir los logs para diagnosticar el problema

