<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h1>Registro</h1>
        <p>Crea una nueva cuenta</p>
      </div>

      <div v-if="error" class="alert alert-error">
        <ul v-if="typeof error === 'object'">
          <li v-for="(err, key) in error" :key="key">{{ err[0] }}</li>
        </ul>
        <span v-else>{{ error }}</span>
      </div>

      <form @submit.prevent="handleRegister" class="login-form">
        <div class="form-group">
          <label for="name">Nombre completo</label>
          <input
            type="text"
            id="name"
            v-model="form.name"
            required
            placeholder="Juan Pérez"
          />
        </div>

        <div class="form-group">
          <label for="email">Correo electrónico</label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            required
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
            minlength="8"
            placeholder="••••••••"
          />
        </div>

        <div class="form-group">
          <label for="password_confirmation">Confirmar contraseña</label>
          <input
            type="password"
            id="password_confirmation"
            v-model="form.password_confirmation"
            required
            minlength="8"
            placeholder="••••••••"
          />
        </div>

        <div class="form-group">
          <label for="pregunta_secreta">Pregunta secreta</label>
          <select
            id="pregunta_secreta"
            v-model="form.pregunta_secreta"
            required
          >
            <option value="">Selecciona una pregunta</option>
            <option v-for="pregunta in preguntas" :key="pregunta._id" :value="pregunta.pregunta">
              {{ pregunta.pregunta }}
            </option>
          </select>
        </div>

        <div class="form-group">
          <label for="respuesta_secreta">Respuesta secreta</label>
          <input
            type="text"
            id="respuesta_secreta"
            v-model="form.respuesta_secreta"
            required
            placeholder="Tu respuesta"
          />
        </div>

        <button type="submit" class="btn-primary" :disabled="loading">
          {{ loading ? 'Registrando...' : 'Registrarse' }}
        </button>
      </form>

      <div class="login-footer">
        <p>¿Ya tienes cuenta? <router-link to="/login" class="link">Inicia sesión aquí</router-link></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { getSecretQuestions } from '@/services/secretQuestions'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  pregunta_secreta: '',
  respuesta_secreta: ''
})

const preguntas = ref([])
const error = ref('')
const loading = ref(false)

onMounted(async () => {
  try {
    preguntas.value = await getSecretQuestions()
  } catch (err) {
    console.error('Error loading secret questions:', err)
  }
})

async function handleRegister() {
  error.value = ''
  loading.value = true

  try {
    await authStore.register(form.value)
    router.push('/dashboard')
  } catch (err) {
    if (err.response?.data?.errors) {
      error.value = err.response.data.errors
    } else {
      error.value = err.response?.data?.message || 'Error al registrarse. Por favor, intenta de nuevo.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
@import '@/assets/auth.css';

select {
  padding: 12px 16px;
  border: 2px solid #e5e7eb;
  border-radius: 10px;
  font-size: 16px;
  transition: all 0.3s ease;
  outline: none;
  background: white;
  cursor: pointer;
}

select:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}
</style>

