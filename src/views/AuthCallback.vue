<template>
  <!-- No mostrar nada - procesamiento inmediato y redirección -->
  <div style="display: none;"></div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

onMounted(() => {
  const token = route.query.token
  const error = route.query.error

  // Procesar inmediatamente sin esperas ni pantallas intermedias
  if (error) {
    const errorMessage = decodeURIComponent(error)
    window.location.href = `/login?error=${encodeURIComponent(errorMessage)}`
    return
  }

  if (!token) {
    window.location.href = '/login?error=' + encodeURIComponent('No se recibió el token de autenticación. Por favor, intenta de nuevo.')
    return
  }

  // Guardar token inmediatamente y redirigir
  try {
    // Guardar token en localStorage directamente (más rápido)
    localStorage.setItem('token', token)
    
    // Guardar en store también
    authStore.setAuth(null, token)
    
    // Redirigir inmediatamente al dashboard sin esperas
    window.location.href = '/dashboard'
  } catch (err) {
    console.error('❌ Error en callback:', err)
    // Si hay token, intentar guardarlo y redirigir de todas formas
    if (token) {
      localStorage.setItem('token', token)
      authStore.setAuth(null, token)
      window.location.href = '/dashboard'
    } else {
      authStore.clearAuth()
      window.location.href = '/login?error=' + encodeURIComponent('Error al procesar la autenticación. Por favor, intenta de nuevo.')
    }
  }
})
</script>

<style scoped>
@import '@/assets/auth.css';
</style>

