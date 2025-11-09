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
  // Log de información de debug
  console.log('🔍 Register Component Mounted')
  console.log('🔗 API URL:', import.meta.env.VITE_API_URL)
  
  try {
    console.log('📋 Cargando preguntas secretas...')
    preguntas.value = await getSecretQuestions()
    console.log('✅ Preguntas secretas cargadas:', preguntas.value.length)
  } catch (err) {
    console.error('❌ Error loading secret questions:', err)
    // No mostrar error fatal, solo log
  }
})

async function handleRegister() {
  error.value = ''
  loading.value = true

  // Validar que todos los campos estén completos
  if (!form.value.name || !form.value.email || !form.value.password || 
      !form.value.password_confirmation || !form.value.pregunta_secreta || 
      !form.value.respuesta_secreta) {
    error.value = 'Por favor, completa todos los campos.'
    loading.value = false
    return
  }

  // Validar que las contraseñas coincidan
  if (form.value.password !== form.value.password_confirmation) {
    error.value = 'Las contraseñas no coinciden.'
    loading.value = false
    return
  }

  // Validar que la contraseña tenga al menos 8 caracteres
  if (form.value.password.length < 8) {
    error.value = 'La contraseña debe tener al menos 8 caracteres.'
    loading.value = false
    return
  }

  try {
    console.log('🔄 Iniciando proceso de registro...')
    console.log('📤 Datos a enviar:', {
      name: form.value.name,
      email: form.value.email,
      pregunta_secreta: form.value.pregunta_secreta,
      respuesta_secreta: form.value.respuesta_secreta ? '***' : 'No configurada'
    })
    
    const response = await authStore.register(form.value)
    
    console.log('✅ Registro exitoso, redirigiendo a dashboard...')
    // Registro exitoso, redirigir al dashboard
    await router.push('/dashboard')
  } catch (err) {
    console.error('❌ Error en registro:', err)
    console.error('❌ Error completo:', JSON.stringify(err, null, 2))
    
    // Manejar diferentes tipos de errores
    if (err.response) {
      // Error de respuesta del servidor
      const errorData = err.response.data
      console.error('❌ Error del servidor:', errorData)
      
      if (errorData?.errors) {
        // Errores de validación
        error.value = errorData.errors
      } else if (errorData?.message) {
        error.value = errorData.message
      } else {
        error.value = `Error ${err.response.status}: ${err.response.statusText || 'Error al registrarse'}`
      }
    } else if (err.request) {
      // Error de red (sin respuesta del servidor)
      error.value = 'No se pudo conectar con el servidor. Verifica tu conexión a internet y que el backend esté funcionando en https://backend-equipo.onrender.com'
      console.error('❌ Network error:', err.request)
      console.error('❌ URL intentada:', err.config?.url)
      console.error('❌ Base URL:', err.config?.baseURL)
    } else {
      // Otro tipo de error
      error.value = err.message || 'Error al registrarse. Por favor, intenta de nuevo.'
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

