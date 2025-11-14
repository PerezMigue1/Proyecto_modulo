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
    
    // Guardar token en store y localStorage PRIMERO
    authStore.setAuth(null, token)
    
    // Esperar un momento para asegurar que el token se guardó completamente
    await new Promise(resolve => setTimeout(resolve, 100))
    
    // Verificar que el token se guardó correctamente en ambos lugares
    const tokenInStore = authStore.token
    const tokenInLocalStorage = localStorage.getItem('token')
    
    console.log('🔍 Verificación de token:', {
      tokenInStore: tokenInStore ? 'Presente' : 'No presente',
      tokenInLocalStorage: tokenInLocalStorage ? 'Presente' : 'No presente',
      tokensMatch: tokenInStore === tokenInLocalStorage
    })
    
    if (!tokenInStore || !tokenInLocalStorage || tokenInStore !== token) {
      console.error('❌ Error: El token no se guardó correctamente')
      console.error('❌ Token en store:', tokenInStore)
      console.error('❌ Token en localStorage:', tokenInLocalStorage)
      console.error('❌ Token original:', token)
      router.push('/login?error=' + encodeURIComponent('Error al guardar el token. Por favor, intenta de nuevo.'))
      return
    }
    
    console.log('✅ Token guardado correctamente, redirigiendo al dashboard...')
    console.log('✅ No se requiere ninguna autenticación adicional - solo el token es suficiente')
    
    // Usar window.location.href para forzar una recarga completa
    // Esto asegura que el router guard vea el token en localStorage
    // No se intenta obtener el usuario aquí - el dashboard lo hará de forma asíncrona
    window.location.href = '/dashboard'
  } catch (err) {
    console.error('❌ Error en callback:', err)
    
    // Si hay token, intentar guardarlo y redirigir de todas formas
    if (token) {
      console.warn('⚠️ Error no crítico, intentando guardar token y redirigir...')
      authStore.setAuth(null, token)
      await new Promise(resolve => setTimeout(resolve, 200))
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

