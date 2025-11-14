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
  const provider = route.query.provider

  console.log('🔍 AuthCallback mounted')
  console.log('🔍 Token:', token ? 'Presente' : 'No presente')
  console.log('🔍 Error:', error)
  console.log('🔍 Provider:', provider)
  console.log('🔍 Query params completos:', route.query)

  // Si hay error en la URL, redirigir al login con el error
  if (error) {
    console.error('❌ Error en callback:', error)
    const errorMessage = decodeURIComponent(error)
    console.error('❌ Mensaje de error decodificado:', errorMessage)
    router.push(`/login?error=${encodeURIComponent(errorMessage)}`)
    return
  }

  // Si no hay token, redirigir al login con error
  if (!token) {
    console.error('❌ No se recibió el token de autenticación')
    console.error('❌ Query params:', route.query)
    router.push('/login?error=' + encodeURIComponent('No se recibió el token de autenticación. Por favor, intenta de nuevo.'))
    return
  }

  // Tenemos token, procesarlo
  try {
    console.log('✅ Token recibido de OAuth, guardando en store...')
    
    // Guardar token en store y localStorage
    authStore.setAuth(null, token)
    
    // Verificar que el token se guardó correctamente
    if (!authStore.token) {
      console.error('❌ Error: El token no se guardó en el store')
      router.push('/login?error=' + encodeURIComponent('Error al guardar el token. Por favor, intenta de nuevo.'))
      return
    }
    
    // Redirigir inmediatamente al dashboard
    // El dashboard obtendrá el usuario automáticamente
    window.location.href = '/dashboard'
  } catch (err) {
    console.error('❌ Error en callback:', err)
    
    // Si hay token, guardarlo y redirigir de todas formas
    if (token) {
      authStore.setAuth(null, token)
      window.location.href = '/dashboard'
    } else {
      authStore.clearAuth()
      router.push('/login?error=' + encodeURIComponent('Error al procesar la autenticación. Por favor, intenta de nuevo.'))
    }
  }
})
</script>

<style scoped>
@import '@/assets/auth.css';
</style>

