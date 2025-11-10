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
            @input="formatName"
            @blur="validateName"
          />
          <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
          <small class="help-text">Solo letras y espacios</small>
        </div>

        <div class="form-group">
          <label for="email">Correo electrónico</label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            required
            placeholder="tu@correo.com"
            @blur="validateEmail"
          />
          <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
        </div>

        <div class="form-group">
          <label for="telefono">Número de teléfono</label>
          <input
            type="tel"
            id="telefono"
            v-model="form.telefono"
            required
            placeholder="1234567890"
            maxlength="10"
            @input="formatPhone"
            @blur="validatePhone"
          />
          <span v-if="errors.telefono" class="error-message">{{ errors.telefono }}</span>
          <small class="help-text">10 dígitos sin espacios ni guiones</small>
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <div class="password-input-wrapper">
            <input
              :type="showPassword ? 'text' : 'password'"
              id="password"
              v-model="form.password"
              required
              minlength="8"
              placeholder="••••••••"
              @blur="validatePassword"
            />
            <button
              type="button"
              class="password-toggle"
              @click="showPassword = !showPassword"
              :aria-label="showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'"
            >
              <svg v-if="showPassword" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                <path d="M2 2l20 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M6.343 6.343l11.314 11.314" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
              <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
              </svg>
            </button>
          </div>
          <span v-if="errors.password" class="error-message">{{ errors.password }}</span>
          <small class="help-text">Mínimo 8 caracteres: letras, números y símbolo</small>
        </div>

        <div class="form-group">
          <label for="password_confirmation">Confirmar contraseña</label>
          <div class="password-input-wrapper">
            <input
              :type="showPasswordConfirmation ? 'text' : 'password'"
              id="password_confirmation"
              v-model="form.password_confirmation"
              required
              minlength="8"
              placeholder="••••••••"
              @blur="validatePasswordConfirmation"
            />
            <button
              type="button"
              class="password-toggle"
              @click="showPasswordConfirmation = !showPasswordConfirmation"
              :aria-label="showPasswordConfirmation ? 'Ocultar contraseña' : 'Mostrar contraseña'"
            >
              <svg v-if="showPasswordConfirmation" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                <path d="M2 2l20 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M6.343 6.343l11.314 11.314" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
              <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
              </svg>
            </button>
          </div>
          <span v-if="errors.password_confirmation" class="error-message">{{ errors.password_confirmation }}</span>
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
  telefono: '',
  password: '',
  password_confirmation: '',
  pregunta_secreta: '',
  respuesta_secreta: ''
})

const preguntas = ref([])
const error = ref('')
const errors = ref({
  name: '',
  email: '',
  telefono: '',
  password: '',
  password_confirmation: ''
})
const loading = ref(false)
const loadingPreguntas = ref(true)
const errorPreguntas = ref('')
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)

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

// Función para formatear nombre: solo letras y espacios en tiempo real
function formatName() {
  // Eliminar todo lo que no sea letra, espacio o acento
  form.value.name = form.value.name.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')
  // Eliminar espacios múltiples
  form.value.name = form.value.name.replace(/\s+/g, ' ')
  validateName()
}

// Función para validar nombre: solo letras y espacios
function validateName() {
  const name = form.value.name.trim()
  errors.value.name = ''
  
  if (!name) {
    errors.value.name = 'El nombre es obligatorio.'
    return false
  }
  
  // Solo letras, espacios y acentos
  const nameRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/
  if (!nameRegex.test(name)) {
    errors.value.name = 'El nombre solo puede contener letras y espacios.'
    return false
  }
  
  if (name.length < 2) {
    errors.value.name = 'El nombre debe tener al menos 2 caracteres.'
    return false
  }
  
  return true
}

// Función para validar email: formato válido
function validateEmail() {
  const email = form.value.email.trim()
  errors.value.email = ''
  
  if (!email) {
    errors.value.email = 'El correo electrónico es obligatorio.'
    return false
  }
  
  // Regex para validar formato de email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!emailRegex.test(email)) {
    errors.value.email = 'Por favor, ingresa un correo electrónico válido.'
    return false
  }
  
  return true
}

// Función para formatear y validar teléfono: solo números, 10 dígitos
function formatPhone() {
  // Eliminar todo lo que no sea número
  form.value.telefono = form.value.telefono.replace(/\D/g, '')
  // Limitar a 10 dígitos
  if (form.value.telefono.length > 10) {
    form.value.telefono = form.value.telefono.substring(0, 10)
  }
  validatePhone()
}

function validatePhone() {
  const telefono = form.value.telefono.trim()
  errors.value.telefono = ''
  
  if (!telefono) {
    errors.value.telefono = 'El número de teléfono es obligatorio.'
    return false
  }
  
  // Solo números
  const phoneRegex = /^\d+$/
  if (!phoneRegex.test(telefono)) {
    errors.value.telefono = 'El teléfono solo puede contener números.'
    return false
  }
  
  // Exactamente 10 dígitos
  if (telefono.length !== 10) {
    errors.value.telefono = 'El teléfono debe tener exactamente 10 dígitos.'
    return false
  }
  
  return true
}

// Función para validar contraseña: mínimo 8 caracteres, letras, números y símbolo
function validatePassword() {
  const password = form.value.password
  errors.value.password = ''
  
  if (!password) {
    errors.value.password = 'La contraseña es obligatoria.'
    return false
  }
  
  if (password.length < 8) {
    errors.value.password = 'La contraseña debe tener al menos 8 caracteres.'
    return false
  }
  
  // Verificar que tenga al menos una letra
  const hasLetter = /[a-zA-Z]/.test(password)
  if (!hasLetter) {
    errors.value.password = 'La contraseña debe contener al menos una letra.'
    return false
  }
  
  // Verificar que tenga al menos un número
  const hasNumber = /\d/.test(password)
  if (!hasNumber) {
    errors.value.password = 'La contraseña debe contener al menos un número.'
    return false
  }
  
  // Verificar que tenga al menos un símbolo
  const hasSymbol = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
  if (!hasSymbol) {
    errors.value.password = 'La contraseña debe contener al menos un símbolo (!@#$%^&*...).'
    return false
  }
  
  // Validar confirmación de contraseña si está llena
  if (form.value.password_confirmation) {
    validatePasswordConfirmation()
  }
  
  return true
}

// Función para validar confirmación de contraseña
function validatePasswordConfirmation() {
  const password = form.value.password
  const passwordConfirmation = form.value.password_confirmation
  errors.value.password_confirmation = ''
  
  if (!passwordConfirmation) {
    errors.value.password_confirmation = 'Por favor, confirma tu contraseña.'
    return false
  }
  
  if (password !== passwordConfirmation) {
    errors.value.password_confirmation = 'Las contraseñas no coinciden.'
    return false
  }
  
  return true
}

// Función para validar todos los campos
function validateAllFields() {
  let isValid = true
  
  // Validar cada campo
  if (!validateName()) isValid = false
  if (!validateEmail()) isValid = false
  if (!validatePhone()) isValid = false
  if (!validatePassword()) isValid = false
  if (!validatePasswordConfirmation()) isValid = false
  
  // Validar pregunta secreta
  if (!form.value.pregunta_secreta) {
    error.value = 'Por favor, selecciona una pregunta secreta.'
    isValid = false
  }
  
  // Validar respuesta secreta
  if (!form.value.respuesta_secreta || !form.value.respuesta_secreta.trim()) {
    error.value = 'Por favor, ingresa una respuesta secreta.'
    isValid = false
  }
  
  return isValid
}

async function handleRegister() {
  error.value = ''
  // Limpiar errores individuales
  errors.value = {
    name: '',
    email: '',
    telefono: '',
    password: '',
    password_confirmation: ''
  }
  loading.value = true

  // Validar todos los campos
  if (!validateAllFields()) {
    loading.value = false
    return
  }

  try {
    console.log('🔄 Iniciando proceso de registro...')
    console.log('📤 Datos a enviar:', {
      name: form.value.name,
      email: form.value.email,
      telefono: form.value.telefono,
      pregunta_secreta: form.value.pregunta_secreta,
      respuesta_secreta: form.value.respuesta_secreta ? '***' : 'No configurada'
    })
    
    // Preparar datos para enviar (limpiar espacios)
    const registerData = {
      name: form.value.name.trim(),
      email: form.value.email.trim(),
      telefono: form.value.telefono.trim(),
      password: form.value.password,
      password_confirmation: form.value.password_confirmation,
      pregunta_secreta: form.value.pregunta_secreta,
      respuesta_secreta: form.value.respuesta_secreta.trim()
    }
    
    const response = await authStore.register(registerData)
    
    console.log('✅ Registro exitoso, redirigiendo a login...')
    // Registro exitoso, redirigir al login con mensaje de éxito
    await router.push('/login?status=registro-exitoso')
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

.error-message {
  color: #e53e3e;
  font-size: 14px;
  margin-top: 5px;
  display: block;
}

.help-text {
  color: #718096;
  font-size: 12px;
  margin-top: 5px;
  display: block;
}

input:invalid {
  border-color: #e53e3e;
}

input:valid {
  border-color: #48bb78;
}

.password-input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
}

.password-input-wrapper input {
  width: 100%;
  padding: 12px 45px 12px 16px !important;
  box-sizing: border-box;
}

.password-toggle {
  position: absolute;
  right: 8px;
  top: 50%;
  transform: translateY(-50%);
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #718096;
  transition: color 0.2s;
  z-index: 10;
  width: 32px;
  height: 32px;
  border-radius: 4px;
}

.password-toggle:hover {
  color: #4a5568;
}

.password-toggle:focus {
  outline: none;
  color: #667eea;
}

.password-toggle:active {
  transform: translateY(-50%) scale(0.95);
}

.password-toggle svg {
  display: block;
  width: 20px;
  height: 20px;
}

select {
  width: 100%;
  box-sizing: border-box;
}

@media (max-width: 480px) {
  .password-input-wrapper input {
    padding: 11px 40px 11px 14px !important;
  }

  .password-toggle {
    right: 6px;
    width: 28px;
    height: 28px;
    padding: 4px;
  }

  .password-toggle svg {
    width: 18px;
    height: 18px;
  }

  select {
    padding: 11px 14px;
    font-size: 16px;
  }

  .help-text {
    font-size: 11px;
  }

  .error-message {
    font-size: 12px;
  }
}

@media (max-width: 360px) {
  .password-input-wrapper input {
    padding: 10px 38px 10px 12px !important;
  }

  .password-toggle {
    right: 5px;
    width: 26px;
    height: 26px;
  }

  .password-toggle svg {
    width: 16px;
    height: 16px;
  }
}
</style>

