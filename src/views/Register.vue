<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h1>Registro</h1>
        <p>Crea una nueva cuenta</p>
      </div>

      <div v-if="error" class="alert alert-error">
        <ul v-if="typeof error === 'object' && !Array.isArray(error)">
          <li v-for="(err, key) in error" :key="key">
            <strong>{{ key }}:</strong> 
            <span v-if="Array.isArray(err)">{{ err.join(', ') }}</span>
            <span v-else>{{ err }}</span>
          </li>
        </ul>
        <ul v-else-if="Array.isArray(error)">
          <li v-for="(err, index) in error" :key="index">{{ err }}</li>
        </ul>
        <span v-else>{{ error }}</span>
      </div>

      <div v-if="errorPreguntas" class="alert alert-warning" style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeaa7; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
        ⚠️ {{ errorPreguntas }}
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
            :disabled="loadingPreguntas"
          >
            <option value="">{{ loadingPreguntas ? 'Cargando preguntas...' : 'Selecciona una pregunta' }}</option>
            <option v-for="pregunta in preguntas" :key="pregunta._id || pregunta.id" :value="pregunta.pregunta">
              {{ pregunta.pregunta }}
            </option>
          </select>
          <p v-if="!loadingPreguntas && preguntas.length === 0" style="color: #e53e3e; font-size: 14px; margin-top: 5px;">
            No se pudieron cargar las preguntas secretas. Por favor, recarga la página.
          </p>
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
const loadingPreguntas = ref(true)
const errorPreguntas = ref('')

onMounted(async () => {
  // Log de información de debug
  console.log('🔍 Register Component Mounted')
  console.log('🔗 API URL:', import.meta.env.VITE_API_URL)
  console.log('🔗 Environment:', import.meta.env.MODE)
  
  loadingPreguntas.value = true
  errorPreguntas.value = ''
  
  try {
    console.log('📋 Cargando preguntas secretas...')
    const preguntasData = await getSecretQuestions()
    console.log('✅ Preguntas secretas recibidas:', preguntasData)
    console.log('✅ Tipo de dato:', typeof preguntasData, Array.isArray(preguntasData))
    
    if (Array.isArray(preguntasData)) {
      if (preguntasData.length > 0) {
        preguntas.value = preguntasData
        console.log('✅ Preguntas secretas cargadas:', preguntas.value.length)
        console.log('✅ Primera pregunta:', preguntas.value[0])
      } else {
        console.warn('⚠️ El array de preguntas está vacío')
        errorPreguntas.value = 'No hay preguntas secretas disponibles'
      }
    } else {
      console.warn('⚠️ La respuesta no es un array:', preguntasData)
      errorPreguntas.value = 'Error al cargar las preguntas secretas'
    }
  } catch (err) {
    console.error('❌ Error loading secret questions:', err)
    console.error('❌ Error completo:', JSON.stringify(err, null, 2))
    console.error('❌ Error response:', err.response)
    console.error('❌ Error status:', err.response?.status)
    console.error('❌ Error data:', err.response?.data)
    
    preguntas.value = []
    
    if (err.response) {
      errorPreguntas.value = `Error ${err.response.status}: ${err.response.statusText || 'Error al cargar preguntas'}`
    } else if (err.request) {
      errorPreguntas.value = 'No se pudo conectar con el servidor para cargar las preguntas'
    } else {
      errorPreguntas.value = 'Error al cargar las preguntas secretas'
    }
  } finally {
    loadingPreguntas.value = false
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
      console.error('❌ Errores detallados:', JSON.stringify(errorData, null, 2))
      
      if (errorData?.errors) {
        // Errores de validación - construir mensaje legible
        const errors = errorData.errors
        const errorMessages = []
        
        // Traducciones de mensajes comunes
        const translateError = (field, message) => {
          const translations = {
            'validation.unique': `El ${field === 'email' ? 'correo electrónico' : field} ya está registrado. Por favor, usa otro.`,
            'validation.required': `El campo ${field} es obligatorio.`,
            'validation.email': 'Por favor, ingresa un correo electrónico válido.',
            'validation.min.string': `La contraseña debe tener al menos 8 caracteres.`,
            'validation.confirmed': 'Las contraseñas no coinciden.',
            'validation.string': `El campo ${field} debe ser texto.`,
            'validation.max.string': `El campo ${field} es demasiado largo.`
          }
          
          // Si el mensaje es una clave de traducción, traducirlo
          if (translations[message]) {
            return translations[message]
          }
          
          // Si el mensaje ya es legible, usarlo directamente
          if (typeof message === 'string' && !message.startsWith('validation.')) {
            return message
          }
          
          // Mensaje por defecto
          return `Error en ${field}: ${message}`
        }
        
        // Recorrer todos los errores y construir mensajes
        for (const [field, messages] of Object.entries(errors)) {
          if (Array.isArray(messages)) {
            messages.forEach(msg => {
              const translatedMsg = translateError(field, msg)
              errorMessages.push(translatedMsg)
            })
          } else {
            const translatedMsg = translateError(field, messages)
            errorMessages.push(translatedMsg)
          }
        }
        
        // Si hay mensajes, mostrarlos
        if (errorMessages.length > 0) {
          error.value = errorMessages.join('. ')
        } else {
          error.value = 'Error de validación. Por favor, verifica los datos ingresados.'
        }
      } else if (errorData?.message) {
        error.value = errorData.message
      } else {
        error.value = `Error ${err.response.status}: ${err.response.statusText || 'Error al registrarse'}`
      }
    } else if (err.request) {
      // Error de red (sin respuesta del servidor)
      const apiUrl = import.meta.env.VITE_API_URL || 'https://backend-equipo.onrender.com/api'
      error.value = `No se pudo conectar con el servidor. Verifica que el backend esté funcionando en ${apiUrl}. Error: ${err.message || 'Sin conexión'}`
      console.error('❌ Network error:', err.request)
      console.error('❌ Error code:', err.code)
      console.error('❌ Error message:', err.message)
      console.error('❌ URL intentada:', err.config?.url)
      console.error('❌ Base URL:', err.config?.baseURL)
      console.error('❌ Full URL:', err.config?.baseURL + err.config?.url)
    } else {
      // Otro tipo de error
      error.value = err.message || 'Error al registrarse. Por favor, intenta de nuevo.'
      console.error('❌ Otro tipo de error:', err)
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

