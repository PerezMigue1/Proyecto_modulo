<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h1>Verificación de Código</h1>
        <p v-if="purpose === 'activation'">Ingresa el código de 6 dígitos enviado a tu correo para activar tu cuenta</p>
        <p v-else-if="purpose === 'password-recovery'">Ingresa el código de 6 dígitos enviado a tu correo para recuperar tu contraseña</p>
        <p v-else>Ingresa el código de 6 dígitos enviado a tu correo</p>
        <p v-if="email" class="email-display">{{ email }}</p>
      </div>

      <div v-if="error" class="alert alert-error">
        {{ error }}
      </div>

      <div v-if="success" class="alert alert-success">
        {{ success }}
      </div>

      <form @submit.prevent="handleVerifyOTP" class="login-form">
        <div class="form-group">
          <label for="code">Código de verificación</label>
          <input
            type="text"
            id="code"
            v-model="code"
            required
            maxlength="6"
            placeholder="000000"
            class="otp-input"
            @input="formatCode"
            autofocus
          />
          <small class="help-text">Ingresa el código de 6 dígitos</small>
        </div>

        <div v-if="timeRemaining > 0" class="timer">
          <p>El código expira en: <strong>{{ formatTime(timeRemaining) }}</strong></p>
        </div>

        <button type="submit" class="btn-primary" :disabled="loading || code.length !== 6">
          {{ loading ? 'Verificando...' : 'Verificar código' }}
        </button>

        <div class="resend-section">
          <button
            type="button"
            class="btn-resend"
            @click="handleResendOTP"
            :disabled="resendLoading || resendCooldown > 0"
          >
            {{ resendLoading ? 'Enviando...' : resendCooldown > 0 ? `Reenviar en ${resendCooldown}s` : 'Reenviar código' }}
          </button>
        </div>

        <div class="links">
          <router-link v-if="purpose === 'activation'" to="/login" class="link">Volver al login</router-link>
          <router-link v-else-if="purpose === 'password-recovery'" to="/password/request" class="link">Volver atrás</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { verifyActivationOTP, resendActivationOTP, verifyPasswordRecoveryOTP } from '@/services/otp'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

// Props desde query params
const email = ref(route.query.email || '')
const purpose = ref(route.query.purpose || 'activation') // 'activation' o 'password-recovery'

const code = ref('')
const error = ref('')
const success = ref('')
const loading = ref(false)
const resendLoading = ref(false)
const resendCooldown = ref(0)
const timeRemaining = ref(600) // 10 minutos en segundos

let timerInterval = null
let cooldownInterval = null

onMounted(() => {
  // Iniciar contador de expiración
  timerInterval = setInterval(() => {
    if (timeRemaining.value > 0) {
      timeRemaining.value--
    } else {
      clearInterval(timerInterval)
    }
  }, 1000)

  // Si no hay email en query, intentar obtenerlo de localStorage
  if (!email.value) {
    const savedEmail = localStorage.getItem('pending_verification_email')
    if (savedEmail) {
      email.value = savedEmail
    }
  }
})

onUnmounted(() => {
  if (timerInterval) clearInterval(timerInterval)
  if (cooldownInterval) clearInterval(cooldownInterval)
})

function formatCode() {
  // Solo permitir números
  code.value = code.value.replace(/\D/g, '')
  // Limitar a 6 dígitos
  if (code.value.length > 6) {
    code.value = code.value.substring(0, 6)
  }
}

function formatTime(seconds) {
  const mins = Math.floor(seconds / 60)
  const secs = seconds % 60
  return `${mins}:${secs.toString().padStart(2, '0')}`
}

async function handleVerifyOTP() {
  error.value = ''
  success.value = ''
  
  if (code.value.length !== 6) {
    error.value = 'Por favor, ingresa un código de 6 dígitos'
    return
  }

  if (!email.value) {
    error.value = 'No se encontró el correo electrónico. Por favor, vuelve a intentar el registro.'
    return
  }

  loading.value = true

  try {
    if (purpose.value === 'activation') {
      // Verificar OTP de activación
      const response = await verifyActivationOTP(email.value, code.value)
      
      if (response.token && response.user) {
        // Guardar token y usuario
        authStore.setAuth(response.user, response.token)
        
        // Limpiar email pendiente
        localStorage.removeItem('pending_verification_email')
        
        // Redirigir al dashboard
        router.push('/dashboard')
      } else {
        throw new Error('Respuesta inválida del servidor')
      }
    } else if (purpose.value === 'password-recovery') {
      // Verificar OTP de recuperación de contraseña
      await verifyPasswordRecoveryOTP(email.value, code.value)
      
      // Redirigir a actualización de contraseña con el código OTP
      router.push({
        path: '/password/update',
        query: {
          email: email.value,
          method: 'otp',
          otp_code: code.value
        }
      })
      return
    }
  } catch (err) {
    console.error('❌ Error verificando OTP:', err)
    
    if (err.response?.status === 400) {
      const message = err.response.data?.message || 'Error al verificar el código'
      
      if (message.includes('expirado')) {
        error.value = 'El código ha expirado. Solicita uno nuevo.'
        timeRemaining.value = 0
      } else if (message.includes('incorrecto')) {
        error.value = 'Código incorrecto. Verifica el código e intenta nuevamente.'
      } else {
        error.value = message
      }
    } else if (err.response?.status === 404) {
      error.value = 'Usuario no encontrado. Por favor, verifica tu correo electrónico.'
    } else {
      error.value = 'Error al verificar el código. Por favor, intenta de nuevo.'
    }
  } finally {
    loading.value = false
  }
}

async function handleResendOTP() {
  if (resendCooldown.value > 0 || resendLoading.value) {
    return
  }

  if (!email.value) {
    error.value = 'No se encontró el correo electrónico. Por favor, vuelve a intentar el registro.'
    return
  }

  error.value = ''
  resendLoading.value = true

  try {
    if (purpose.value === 'activation') {
      await resendActivationOTP(email.value)
      success.value = 'Nuevo código enviado a tu correo. Recuerda que el código expira en 10 minutos.'
      
      // Reiniciar contador
      timeRemaining.value = 600
      
      // Iniciar cooldown de 60 segundos
      resendCooldown.value = 60
      cooldownInterval = setInterval(() => {
        if (resendCooldown.value > 0) {
          resendCooldown.value--
        } else {
          clearInterval(cooldownInterval)
        }
      }, 1000)
    } else {
      // Para recuperación de contraseña, redirigir a la página de recuperación
      router.push({
        path: '/password/request',
        query: { email: email.value, method: 'otp' }
      })
    }
  } catch (err) {
    console.error('❌ Error reenviando OTP:', err)
    
    if (err.response?.status === 400) {
      const message = err.response.data?.message || 'Error al reenviar el código'
      
      if (message.includes('ya está activada')) {
        error.value = 'Esta cuenta ya está activada. Puedes iniciar sesión.'
        setTimeout(() => {
          router.push('/login')
        }, 2000)
      } else {
        error.value = message
      }
    } else {
      error.value = 'Error al reenviar el código. Por favor, intenta de nuevo.'
    }
  } finally {
    resendLoading.value = false
  }
}
</script>

<style scoped>
@import '@/assets/auth.css';

.otp-input {
  text-align: center;
  font-size: 24px;
  letter-spacing: 8px;
  font-weight: 600;
  padding: 16px;
}

.email-display {
  color: #667eea;
  font-weight: 500;
  margin-top: 8px;
  font-size: 14px;
}

.timer {
  text-align: center;
  margin: 16px 0;
  padding: 12px;
  background-color: #f7fafc;
  border-radius: 8px;
}

.timer p {
  margin: 0;
  color: #4a5568;
  font-size: 14px;
}

.timer strong {
  color: #667eea;
  font-size: 16px;
}

.resend-section {
  text-align: center;
  margin-top: 16px;
}

.btn-resend {
  background: transparent;
  border: 1px solid #667eea;
  color: #667eea;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 14px;
  transition: all 0.2s;
}

.btn-resend:hover:not(:disabled) {
  background: #667eea;
  color: white;
}

.btn-resend:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.help-text {
  color: #718096;
  font-size: 12px;
  margin-top: 5px;
  display: block;
  text-align: center;
}

@media (max-width: 480px) {
  .otp-input {
    font-size: 20px;
    letter-spacing: 6px;
    padding: 14px;
  }
}
</style>

