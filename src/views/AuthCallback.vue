<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h1>Autenticando...</h1>
        <p>Por favor espera mientras te redirigimos.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

onMounted(async () => {
  const token = route.query.token
  const error = route.query.error

  if (error) {
    // Si hay error en la URL, redirigir al login con el error
    router.push(`/login?error=${encodeURIComponent(error)}`)
    return
  }

  if (token) {
    try {
      // Set token primero
      authStore.setAuth(null, token)
      
      // Intentar obtener usuario
      await authStore.fetchUser()
      
      // Redirigir al dashboard
      router.push('/dashboard')
    } catch (err) {
      console.error('Error en callback:', err)
      // Limpiar auth y redirigir al login con error
      authStore.clearAuth()
      router.push('/login?error=' + encodeURIComponent('Error al autenticar. Por favor, intenta de nuevo.'))
    }
  } else {
    // No hay token, redirigir al login
    router.push('/login?error=' + encodeURIComponent('No se recibió el token de autenticación.'))
  }
})
</script>

<style scoped>
@import '@/assets/auth.css';
</style>

