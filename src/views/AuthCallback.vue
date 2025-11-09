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

onMounted(() => {
  const token = route.query.token

  if (token) {
    // Set token and fetch user
    authStore.setAuth(null, token)
    authStore.fetchUser()
      .then(() => {
        router.push('/dashboard')
      })
      .catch(() => {
        router.push('/login?error=Error al autenticar. Por favor, intenta de nuevo.')
      })
  } else {
    router.push('/login?error=Error al autenticar. Por favor, intenta de nuevo.')
  }
})
</script>

<style scoped>
@import '@/assets/auth.css';
</style>

