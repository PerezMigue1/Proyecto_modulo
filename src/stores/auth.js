import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token') || null)
  const loading = ref(false)
  const error = ref(null)

  // Inicializar token en API si existe
  if (token.value) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  function setAuth(userData, authToken) {
    user.value = userData
    token.value = authToken
    if (authToken) {
      localStorage.setItem('token', authToken)
      api.defaults.headers.common['Authorization'] = `Bearer ${authToken}`
    } else {
      clearAuth()
    }
    error.value = null
  }

  function clearAuth() {
    user.value = null
    token.value = null
    localStorage.removeItem('token')
    delete api.defaults.headers.common['Authorization']
    error.value = null
  }

  async function fetchUser() {
    if (!token.value) {
      throw new Error('No hay token de autenticación')
    }

    try {
      loading.value = true
      error.value = null
      const response = await api.get('/user')
      user.value = response.data
      return response.data
    } catch (err) {
      console.error('Error fetching user:', err)
      // Si es un error 401, limpiar auth
      if (err.response?.status === 401) {
        clearAuth()
      }
      throw err
    } finally {
      loading.value = false
    }
  }

  async function login(email, password) {
    try {
      loading.value = true
      error.value = null
      console.log('🔐 Intentando login con:', { email })
      
      const response = await api.post('/login', { email, password })
      
      if (response.data.user && response.data.token) {
        setAuth(response.data.user, response.data.token)
        console.log('✅ Login exitoso')
        return response.data
      } else {
        throw new Error('Respuesta inválida del servidor')
      }
    } catch (err) {
      console.error('❌ Login error:', err)
      error.value = err
      throw err
    } finally {
      loading.value = false
    }
  }

  async function register(data) {
    try {
      loading.value = true
      error.value = null
      
      // Preparar datos para enviar al backend
      const registerData = {
        name: data.name,
        email: data.email,
        password: data.password,
        password_confirmation: data.password_confirmation,
        pregunta_secreta: data.pregunta_secreta,
        respuesta_secreta: data.respuesta_secreta
      }
      
      console.log('📝 Intentando registro con:', { 
        email: registerData.email, 
        name: registerData.name,
        pregunta_secreta: registerData.pregunta_secreta ? 'Configurada' : 'No configurada'
      })
      
      const response = await api.post('/register', registerData)
      
      if (response.data.user && response.data.token) {
        setAuth(response.data.user, response.data.token)
        console.log('✅ Registro exitoso')
        return response.data
      } else {
        throw new Error('Respuesta inválida del servidor')
      }
    } catch (err) {
      console.error('❌ Register error:', err)
      console.error('❌ Register error details:', {
        message: err.message,
        status: err.response?.status,
        data: err.response?.data
      })
      error.value = err
      throw err
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      if (token.value) {
        await api.post('/logout')
      }
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      clearAuth()
    }
  }

  const isAuthenticated = computed(() => {
    return !!token.value
  })

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    setAuth,
    clearAuth,
    fetchUser,
    login,
    register,
    logout
  }
})
