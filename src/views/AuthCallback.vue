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

  console.log('🔍 AuthCallback mounted:', { token: token ? 'Presente' : 'No presente', error, provider })

  if (error) {
    // Si hay error en la URL, redirigir al login con el error
    console.error('❌ Error en callback:', error)
    router.push(`/login?error=${encodeURIComponent(error)}`)
    return
  }

  if (token) {
    try {
      console.log('✅ Token recibido, guardando en store...')
      // Set token primero
      authStore.setAuth(null, token)
      console.log('✅ Token guardado en store')
      
      // Intentar obtener usuario, pero si falla, no es crítico
      // El token ya está guardado y el router guard permitirá el acceso
      try {
        console.log('🔄 Intentando obtener usuario...')
        await authStore.fetchUser()
        console.log('✅ Usuario obtenido exitosamente')
      } catch (fetchError) {
        console.warn('⚠️ No se pudo obtener el usuario, pero el token está guardado:', fetchError)
        // No es crítico, el token está guardado y el usuario puede acceder
        // El dashboard intentará obtener el usuario nuevamente
      }
      
      // Redirigir al dashboard
      console.log('✅ Redirigiendo al dashboard...')
      await router.push('/dashboard')
    } catch (err) {
      console.error('❌ Error en callback:', err)
      console.error('❌ Error details:', {
        message: err.message,
        response: err.response,
        status: err.response?.status
      })
      
      // Solo limpiar auth si es un error crítico
      // Si el token está presente, intentar usarlo de todas formas
      if (err.response?.status === 401) {
        // Token inválido, limpiar auth
        authStore.clearAuth()
        router.push('/login?error=' + encodeURIComponent('Token inválido. Por favor, intenta de nuevo.'))
      } else {
        // Otro error, pero el token puede ser válido
        // Intentar redirigir al dashboard de todas formas
        console.warn('⚠️ Error no crítico, intentando redirigir al dashboard...')
        await router.push('/dashboard')
      }
    }
  } else {
    // No hay token, redirigir al login
    console.error('❌ No se recibió el token de autenticación')
    router.push('/login?error=' + encodeURIComponent('No se recibió el token de autenticación.'))
  }
})
</script>

<style scoped>
@import '@/assets/auth.css';
</style>

