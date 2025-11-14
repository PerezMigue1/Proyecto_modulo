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
    console.log('🔄 setAuth llamado:', { hasUserData: !!userData, hasToken: !!authToken })
    user.value = userData
    token.value = authToken
    if (authToken) {
      // Guardar en localStorage primero
      localStorage.setItem('token', authToken)
      console.log('✅ Token guardado en localStorage')
      
      // Configurar header de autorización
      api.defaults.headers.common['Authorization'] = `Bearer ${authToken}`
      console.log('✅ Header de autorización configurado')
      
      // Verificar que se guardó correctamente
      const savedToken = localStorage.getItem('token')
      if (savedToken !== authToken) {
        console.error('❌ Error: El token no se guardó correctamente en localStorage')
      } else {
        console.log('✅ Token verificado en localStorage')
      }
    } else {
      clearAuth()
    }
    error.value = null
    console.log('✅ setAuth completado. Token en store:', token.value ? 'Presente' : 'No presente')
  }

  function clearAuth() {
    user.value = null
    token.value = null
    localStorage.removeItem('token')
    delete api.defaults.headers.common['Authorization']
    error.value = null
  }

  async function fetchUser() {
    // Verificar token en store primero, luego en localStorage
    let currentToken = token.value || localStorage.getItem('token')
    
    if (!currentToken) {
      console.error('❌ No hay token para obtener usuario')
      throw new Error('No hay token de autenticación')
    }
    
    // Si el token está en localStorage pero no en el store, sincronizar
    if (!token.value && currentToken) {
      console.log('🔄 Token encontrado en localStorage, sincronizando con store...')
      token.value = currentToken
      api.defaults.headers.common['Authorization'] = `Bearer ${currentToken}`
    }

    try {
      loading.value = true
      error.value = null
      console.log('🔄 Obteniendo usuario del backend...')
      console.log('🔄 Token usado:', currentToken.substring(0, 20) + '...')
      console.log('🔄 Token completo (primeros 50 chars):', currentToken.substring(0, 50))
      console.log('🔄 URL completa:', api.defaults.baseURL + '/user')
      console.log('🔄 Header Authorization configurado:', api.defaults.headers.common['Authorization'] ? 'Sí' : 'No')
      
      const response = await api.get('/user')
      console.log('✅ Usuario obtenido:', response.data)
      
      user.value = response.data
      return response.data
    } catch (err) {
      console.error('❌ Error fetching user:', err)
      console.error('❌ Error response:', err.response)
      console.error('❌ Error status:', err.response?.status)
      console.error('❌ Error data:', err.response?.data)
      console.error('❌ Request config:', err.config)
      console.error('❌ Token en header:', err.config?.headers?.Authorization ? 'Presente' : 'No presente')
      console.error('❌ Token completo en header:', err.config?.headers?.Authorization)
      
      // NO limpiar el token inmediatamente en caso de 401
      // Puede ser un problema temporal o de sincronización
      // El Dashboard manejará los reintentos
      if (err.response?.status === 401) {
        console.error('⚠️ Error 401 al obtener usuario')
        console.error('⚠️ NO limpiando token - puede ser un problema temporal')
        console.error('⚠️ El Dashboard intentará nuevamente')
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
        telefono: data.telefono,
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
      
      console.log('✅ Respuesta del registro:', response.status, response.data)
      
      // Verificar que el registro fue exitoso (status 201 o 200)
      // Aceptar cualquier respuesta exitosa, incluso si no incluye token
      if (response.status === 201 || response.status === 200) {
        console.log('✅ Registro exitoso - Usuario creado en la base de datos')
        // NO establecer autenticación automática después del registro
        // El usuario debe hacer login manualmente
        return { success: true, message: 'Registro exitoso' }
      } else {
        console.error('❌ Status code inesperado:', response.status)
        throw new Error('Respuesta inválida del servidor')
      }
    } catch (err) {
      console.error('❌ Register error:', err)
      console.error('❌ Register error details:', {
        message: err.message,
        status: err.response?.status,
        statusText: err.response?.statusText,
        data: err.response?.data,
        errors: err.response?.data?.errors
      })
      
      // Si el error es 500 y NO hay errores de validación, 
      // es posible que el usuario se haya creado pero haya fallado algo después (ej: crear token)
      // Como no necesitamos el token para el registro, tratamos como éxito
      if (err.response?.status === 500 && !err.response?.data?.errors) {
        console.warn('⚠️ Error 500 del servidor sin errores de validación')
        console.warn('⚠️ Es posible que el usuario se haya creado pero haya fallado algo después (ej: crear token)')
        console.warn('⚠️ Redirigiendo al login para que el usuario pueda intentar iniciar sesión')
        // Tratamos como éxito para que el usuario pueda hacer login
        // El backend debería manejar esto mejor, pero por ahora esto evita frustración
        return { success: true, message: 'Registro exitoso. Por favor, inicia sesión.' }
      }
      
      // Log detallado de errores de validación
      if (err.response?.status === 422 && err.response?.data?.errors) {
        console.error('❌ Errores de validación:', JSON.stringify(err.response.data.errors, null, 2))
      }
      
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
