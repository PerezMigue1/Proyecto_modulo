import axios from 'axios'

// Obtener URL de la API desde variables de entorno
// En producción, usar la URL de Render por defecto si no está configurada
const API_URL = import.meta.env.VITE_API_URL || 
  (import.meta.env.PROD 
    ? 'https://backend-equipo.onrender.com/api' 
    : 'http://localhost:8000/api')

// Log para debugging
console.log('🔗 API URL:', API_URL)
console.log('🔗 Environment:', import.meta.env.MODE)
console.log('🔗 VITE_API_URL:', import.meta.env.VITE_API_URL)

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
    
    // Log para debugging (solo en desarrollo)
    if (import.meta.env.DEV) {
      console.log('📤 Request:', config.method?.toUpperCase(), config.url, config.data)
    }
    
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
    // Log para debugging (solo en desarrollo)
    if (import.meta.env.DEV) {
      console.log('📥 Response:', response.status, response.config.url, response.data)
    }
    return response
  },
  (error) => {
    // Log detallado del error
    console.error('❌ API Error:', {
      message: error.message,
      status: error.response?.status,
      statusText: error.response?.statusText,
      url: error.config?.url,
      data: error.response?.data,
      networkError: !error.response
    })

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

