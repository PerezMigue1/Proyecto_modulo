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

onMounted(async () => {
  // Obtener el token de la query string
  // Vue Router decodifica automáticamente los parámetros de la URL
  // Si el token viene codificado, intentar decodificarlo, pero si ya está decodificado, usarlo tal cual
  let token = route.query.token || null
  
  // Si el token existe pero parece estar codificado (contiene %), decodificarlo
  if (token && typeof token === 'string' && token.includes('%')) {
    try {
      token = decodeURIComponent(token)
    } catch (e) {
      console.warn('⚠️ Error al decodificar token, usando tal cual:', e)
    }
  }
  
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
    console.log('🔄 Token recibido:', token.substring(0, 20) + '...')
    
    // Guardar token en localStorage primero
    localStorage.setItem('token', token)
    console.log('✅ Token guardado en localStorage')
    
    // Guardar en store también (esto también guarda en localStorage, pero es más seguro)
    authStore.setAuth(null, token)
    console.log('✅ Token guardado en store')
    
    // Verificar que se guardó correctamente
    const savedToken = localStorage.getItem('token')
    if (savedToken !== token) {
      console.error('❌ Error: El token no se guardó correctamente')
      throw new Error('Error al guardar token')
    }
    
    console.log('✅ Token verificado correctamente')
    
    // Intentar obtener los datos del usuario antes de redirigir
    // Esto asegura que el nombre aparezca inmediatamente en el dashboard
    console.log('🔄 Obteniendo datos del usuario...')
    try {
      const userData = await authStore.fetchUser()
      console.log('✅ Usuario obtenido:', userData)
      // El usuario ya está guardado en el store por fetchUser
    } catch (userError) {
      console.warn('⚠️ No se pudo obtener usuario ahora, se intentará en el dashboard:', userError)
      // Continuar de todas formas - el dashboard intentará obtenerlo
    }
    
    console.log('🔄 Redirigiendo al dashboard...')
    
    // Usar window.location.href para forzar recarga completa
    // Esto asegura que el token esté disponible cuando el router guard se ejecute
    window.location.href = '/dashboard'
  } catch (err) {
    console.error('❌ Error en callback:', err)
    // Si hay token, intentar guardarlo y redirigir de todas formas
    if (token) {
      try {
        localStorage.setItem('token', token)
        authStore.setAuth(null, token)
        console.log('✅ Token guardado en fallback')
        
        // Intentar obtener usuario
        try {
          await authStore.fetchUser()
          console.log('✅ Usuario obtenido en fallback')
        } catch (userError) {
          console.warn('⚠️ No se pudo obtener usuario en fallback:', userError)
        }
        
        console.log('🔄 Redirigiendo al dashboard...')
        window.location.href = '/dashboard'
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

