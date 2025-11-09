import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token') || null)

  const isAuthenticated = computed(() => !!token.value && !!user.value)

  function setAuth(userData, authToken) {
    user.value = userData
    token.value = authToken
    localStorage.setItem('token', authToken)
    if (authToken) {
      api.defaults.headers.common['Authorization'] = `Bearer ${authToken}`
    }
  }

  function clearAuth() {
    user.value = null
    token.value = null
    localStorage.removeItem('token')
    delete api.defaults.headers.common['Authorization']
  }

  async function fetchUser() {
    try {
      const response = await api.get('/user')
      user.value = response.data
      return response.data
    } catch (error) {
      clearAuth()
      throw error
    }
  }

  async function login(email, password) {
    try {
      const response = await api.post('/login', { email, password })
      setAuth(response.data.user, response.data.token)
      return response.data
    } catch (error) {
      throw error
    }
  }

  async function register(data) {
    try {
      const response = await api.post('/register', data)
      setAuth(response.data.user, response.data.token)
      return response.data
    } catch (error) {
      throw error
    }
  }

  async function logout() {
    try {
      await api.post('/logout')
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      clearAuth()
    }
  }

  // Initialize auth token in API if exists
  if (token.value) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  return {
    user,
    token,
    isAuthenticated,
    setAuth,
    clearAuth,
    fetchUser,
    login,
    register,
    logout
  }
})

