<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h1>Bienvenido</h1>
        <p>Inicia sesión en tu cuenta</p>
      </div>

      <div v-if="error" class="alert alert-error">
        {{ error }}
      </div>

      <div v-if="success" class="alert alert-success">
        {{ success }}
      </div>

      <form @submit.prevent="handleLogin" class="login-form">
        <div class="form-group">
          <label for="email">Correo electrónico</label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            required
            autofocus
            placeholder="tu@correo.com"
          />
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input
            type="password"
            id="password"
            v-model="form.password"
            required
            placeholder="••••••••"
          />
        </div>

        <div class="form-group">
          <label class="checkbox-label">
            <input type="checkbox" v-model="form.remember" />
            <span>Recordarme</span>
          </label>
        </div>

        <button type="submit" class="btn-primary" :disabled="loading">
          {{ loading ? 'Iniciando sesión...' : 'Iniciar sesión' }}
        </button>

        <div class="divider">
          <span>o</span>
        </div>

        <a :href="googleLoginUrl" class="btn-google">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
          </svg>
          Continuar con Google
        </a>

        <a :href="facebookLoginUrl" class="btn-facebook">
          <span class="btn-provider-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="12" r="12" fill="#1877F2"/>
              <path d="M15.504 12H13.458v6H10.68v-6H9.5v-2.244h1.18v-1.442c0-1.392.648-2.214 2.224-2.214h1.6v2.071h-1.023c-.38 0-.454.156-.454.447v1.138h1.497L15.504 12z" fill="#fff"/>
            </svg>
          </span>
          <span class="btn-provider-text">Continuar con Facebook</span>
        </a>

        <div class="links">
          <router-link to="/password/request" class="link">¿Olvidaste tu contraseña?</router-link>
        </div>
      </form>

      <div class="login-footer">
        <p>¿No tienes cuenta? <router-link to="/register" class="link">Regístrate aquí</router-link></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: '',
  remember: false
})

const error = ref('')
const success = ref('')
const loading = ref(false)

// Construir URL del backend (sin /api)
const getBackendBaseUrl = () => {
  const apiUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'
  // Remover /api si existe al final
  return apiUrl.replace(/\/api\/?$/, '')
}

const googleLoginUrl = computed(() => `${getBackendBaseUrl()}/auth/google`)
const facebookLoginUrl = computed(() => `${getBackendBaseUrl()}/auth/facebook`)

onMounted(() => {
  if (route.query.error) {
    error.value = route.query.error
  }
  if (route.query.status) {
    success.value = route.query.status
  }
})

async function handleLogin() {
  error.value = ''
  loading.value = true

  try {
    await authStore.login(form.value.email, form.value.password)
    router.push('/dashboard')
  } catch (err) {
    error.value = err.response?.data?.errors?.email?.[0] || err.response?.data?.message || 'Error al iniciar sesión. Por favor, intenta de nuevo.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
@import '@/assets/auth.css';
</style>

