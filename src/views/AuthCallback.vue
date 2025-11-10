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
    console.log('✅ Provider:', provider)
    console.log('✅ Token (primeros 20 caracteres):', token.substring(0, 20) + '...')
    
    // Guardar token en store PRIMERO y esperar a que se complete
    authStore.setAuth(null, token)
    console.log('✅ Token guardado en store y localStorage')
    
    // Verificar que el token se guardó correctamente
    if (!authStore.token) {
      console.error('❌ Error: El token no se guardó en el store')
      router.push('/login?error=' + encodeURIComponent('Error al guardar el token. Por favor, intenta de nuevo.'))
      return
    }
    
    console.log('✅ Token en store verificado:', authStore.token ? 'Presente' : 'No presente')
    console.log('✅ Token en localStorage:', localStorage.getItem('token') ? 'Presente' : 'No presente')
    
    // Esperar un momento para asegurar que el token está completamente guardado
    await new Promise(resolve => setTimeout(resolve, 100))
    
    // Intentar obtener usuario del backend (opcional, no bloquea la redirección)
    try {
      console.log('🔄 Intentando obtener usuario del backend...')
      await authStore.fetchUser()
      console.log('✅ Usuario obtenido exitosamente:', authStore.user?.email)
    } catch (fetchError) {
      console.warn('⚠️ No se pudo obtener usuario inmediatamente:', fetchError)
      console.warn('⚠️ Error status:', fetchError.response?.status)
      console.warn('⚠️ Esto no es crítico, el token está guardado y el dashboard lo obtendrá')
      // No bloqueamos la redirección si falla obtener el usuario
      // El dashboard intentará obtenerlo nuevamente
    }
    
    // Redirigir al dashboard
    console.log('✅ Redirigiendo al dashboard...')
    console.log('✅ Token antes de redirección:', authStore.token ? 'Presente' : 'No presente')
    console.log('✅ Token en localStorage antes de redirección:', localStorage.getItem('token') ? 'Presente' : 'No presente')
    
    // Usar window.location.href directamente para evitar problemas con el router guard
    // Esto fuerza una recarga completa de la página con el token ya guardado
    console.log('🔄 Forzando redirección con window.location.href...')
    window.location.href = '/dashboard'
  } catch (err) {
    console.error('❌ Error crítico en callback:', err)
    console.error('❌ Error completo:', JSON.stringify(err, null, 2))
    
    // Solo limpiar auth si es un error crítico real
    // Si el token está presente, intentar redirigir de todas formas
    if (!token) {
      authStore.clearAuth()
      router.push('/login?error=' + encodeURIComponent('Error al procesar la autenticación. Por favor, intenta de nuevo.'))
    } else {
      // El token está presente, guardarlo y redirigir al dashboard
      console.warn('⚠️ Error no crítico, guardando token y redirigiendo al dashboard...')
      authStore.setAuth(null, token)
      // Esperar un momento y redirigir
      setTimeout(() => {
        window.location.href = '/dashboard'
      }, 100)
    }
  }
})
</script>

<style scoped>
@import '@/assets/auth.css';
</style>

