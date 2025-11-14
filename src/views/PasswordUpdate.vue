<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h1>Actualizar Contraseña</h1>
        <p>Ingresa tu nueva contraseña</p>
      </div>

      <div v-if="error" class="alert alert-error">
        {{ error }}
      </div>

      <form @submit.prevent="handleUpdatePassword" class="login-form">
        <div class="form-group">
          <label for="new_password">Nueva contraseña</label>
          <div class="password-input-wrapper">
            <input
              :type="showPassword ? 'text' : 'password'"
              id="new_password"
              v-model="form.new_password"
              required
              minlength="8"
              placeholder="••••••••"
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
          <small class="help-text">Mínimo 8 caracteres: letras, números y símbolo</small>
        </div>

        <div class="form-group">
          <label for="new_password_confirmation">Confirmar nueva contraseña</label>
          <div class="password-input-wrapper">
            <input
              :type="showPasswordConfirmation ? 'text' : 'password'"
              id="new_password_confirmation"
              v-model="form.new_password_confirmation"
              required
              minlength="8"
              placeholder="••••••••"
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
        </div>

        <button type="submit" class="btn-primary" :disabled="loading">
          {{ loading ? 'Actualizando...' : 'Actualizar contraseña' }}
        </button>

        <div class="links">
          <router-link to="/login" class="link">Volver al login</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { updatePassword } from '@/services/passwordRecovery'

const router = useRouter()
const route = useRoute()

const form = ref({
  new_password: '',
  new_password_confirmation: ''
})
const error = ref('')
const loading = ref(false)
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)

const email = ref('')
const method = ref('pregunta')
const otpCode = ref('')
const respuestaSecreta = ref('')

onMounted(() => {
  // Obtener parámetros de la URL
  email.value = route.query.email || ''
  method.value = route.query.method || 'pregunta'
  otpCode.value = route.query.otp_code || ''
  respuestaSecreta.value = route.query.respuesta_secreta || ''

  if (!email.value) {
    error.value = 'No se encontró el correo electrónico. Por favor, vuelve a intentar.'
  }
})

async function handleUpdatePassword() {
  error.value = ''

  if (form.value.new_password !== form.value.new_password_confirmation) {
    error.value = 'Las contraseñas no coinciden.'
    return
  }

  if (form.value.new_password.length < 8) {
    error.value = 'La contraseña debe tener al menos 8 caracteres.'
    return
  }

  if (!email.value) {
    error.value = 'No se encontró el correo electrónico. Por favor, vuelve a intentar.'
    return
  }

  loading.value = true

  try {
    await updatePassword(
      email.value,
      form.value.new_password,
      form.value.new_password_confirmation,
      respuestaSecreta.value || null,
      method.value,
      otpCode.value || null
    )
    
    router.push('/login?status=Contraseña actualizada exitosamente. Ahora puedes iniciar sesión.')
  } catch (err) {
    console.error('❌ Error actualizando contraseña:', err)
    
    if (err.response?.status === 400) {
      const message = err.response.data?.message || 'Error al actualizar la contraseña'
      
      if (message.includes('incorrecto') || message.includes('expirado')) {
        error.value = 'Código incorrecto o expirado. Solicita uno nuevo.'
      } else {
        error.value = message
      }
    } else if (err.response?.data?.errors) {
      const errors = err.response.data.errors
      const firstError = Object.values(errors)[0]
      error.value = Array.isArray(firstError) ? firstError[0] : firstError
    } else {
      error.value = err.response?.data?.message || 'Error al actualizar la contraseña. Por favor, intenta de nuevo.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
@import '@/assets/auth.css';

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

.help-text {
  color: #718096;
  font-size: 12px;
  margin-top: 5px;
  display: block;
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
}
</style>

