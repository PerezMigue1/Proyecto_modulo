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
    console.log('🔄 Procesando token de OAuth...')
    
    // Guardar token en localStorage directamente (más rápido)
    localStorage.setItem('token', token)
    console.log('✅ Token guardado en localStorage')
    
    // Verificar que se guardó correctamente
    const savedToken = localStorage.getItem('token')
    if (savedToken !== token) {
      console.error('❌ Error: El token no se guardó correctamente')
      throw new Error('Error al guardar token')
    }
    
    // Guardar en store también
    authStore.setAuth(null, token)
    console.log('✅ Token guardado en store')
    
    // Pequeña pausa para asegurar que todo se guardó correctamente
    // antes de redirigir
    setTimeout(() => {
      console.log('🔄 Redirigiendo al dashboard...')
      window.location.href = '/dashboard'
    }, 100)
  } catch (err) {
    console.error('❌ Error en callback:', err)
    // Si hay token, intentar guardarlo y redirigir de todas formas
    if (token) {
      try {
        localStorage.setItem('token', token)
        authStore.setAuth(null, token)
        console.log('✅ Token guardado en fallback, redirigiendo...')
        setTimeout(() => {
          window.location.href = '/dashboard'
        }, 100)
      } catch (fallbackErr) {
        console.error('❌ Error en fallback:', fallbackErr)
        authStore.clearAuth()
        window.location.href = '/login?error=' + encodeURIComponent('Error al procesar la autenticación. Por favor, intenta de nuevo.')
      }
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

