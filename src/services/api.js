import axios from 'axios'

// Obtener URL de la API desde variables de entorno
// En producción, usar la URL de Render por defecto si no está configurada
const API_URL = import.meta.env.VITE_API_URL || 
  (import.meta.env.PROD 
    ? 'https://backend-equipo.onrender.com/api' 
    : 'http://localhost:8000/api')

// Log para debugging (siempre visible)
console.log('🔗 API URL:', API_URL)
console.log('🔗 Environment:', import.meta.env.MODE)
console.log('🔗 VITE_API_URL:', import.meta.env.VITE_API_URL)
console.log('🔗 PROD:', import.meta.env.PROD)

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  withCredentials: true,
  timeout: 30000 // 30 segundos de timeout
})

// Request interceptor
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    
    // Log para debugging (siempre visible en desarrollo, también en producción para debugging)
    console.log('📤 Request:', config.method?.toUpperCase(), config.url, {
      baseURL: config.baseURL,
      data: config.data
    })
    
    return config
  },
  (error) => {
    console.error('❌ Request Error:', error)
    return Promise.reject(error)
  }
)

// Response interceptor
api.interceptors.response.use(
  (response) => {
    // Log para debugging (siempre visible)
    console.log('📥 Response:', response.status, response.config.url, response.data)
    return response
  },
  (error) => {
    // Log detallado del error (siempre visible)
    console.error('❌ API Error:', {
      message: error.message,
      status: error.response?.status,
      statusText: error.response?.statusText,
      url: error.config?.url,
      baseURL: error.config?.baseURL,
      fullURL: error.config?.baseURL + error.config?.url,
      data: error.response?.data,
      networkError: !error.response,
      code: error.code
    })
    
    // Si es un error de red (sin respuesta del servidor)
    if (!error.response) {
      console.error('❌ Network Error - No se pudo conectar con el servidor')
      console.error('❌ Verifica que el backend esté funcionando en:', error.config?.baseURL)
      console.error('❌ Error code:', error.code)
      console.error('❌ Error message:', error.message)
    }
    
    // Log detallado de errores 422 (validación)
    if (error.response?.status === 422 && error.response?.data) {
      console.error('❌ Errores de validación (422):', JSON.stringify(error.response.data, null, 2))
      if (error.response.data.errors) {
        console.error('❌ Campos con error:', Object.keys(error.response.data.errors))
        Object.entries(error.response.data.errors).forEach(([field, messages]) => {
          console.error(`  - ${field}:`, messages)
        })
      }
    }
    
    // Log detallado de errores 500 (error del servidor)
    if (error.response?.status === 500) {
      console.error('❌ Error 500 - Error interno del servidor')
      console.error('❌ Verifica los logs del backend')
    }
    
    // Log detallado de errores 404 (no encontrado)
    if (error.response?.status === 404) {
      console.error('❌ Error 404 - Ruta no encontrada')
      console.error('❌ Verifica que la ruta exista en el backend:', error.config?.url)
    }

    if (error.response?.status === 401) {
      // Limpiar token y redirigir al login
      localStorage.removeItem('token')
      // Solo redirigir si no estamos ya en la página de login
      if (window.location.pathname !== '/login' && window.location.pathname !== '/register') {
        window.location.href = '/login'
      }
    }
    
    return Promise.reject(error)
  }
)

export default api

