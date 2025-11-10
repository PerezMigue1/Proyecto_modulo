<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h1>Recuperar Contraseña</h1>
        <p v-if="!step || step === 1">Ingresa tu correo electrónico</p>
        <p v-else-if="step === 2">Responde tu pregunta secreta</p>
        <p v-else-if="step === 3">Ingresa tu nueva contraseña</p>
      </div>

      <div v-if="error" class="alert alert-error">
        {{ error }}
      </div>

      <!-- Step 1: Email verification -->
      <form v-if="step === 1" @submit.prevent="handleVerifyEmail" class="login-form">
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

        <button type="submit" class="btn-primary" :disabled="loading">
          {{ loading ? 'Verificando...' : 'Continuar' }}
        </button>

        <div class="links">
          <router-link to="/login" class="link">Volver al login</router-link>
        </div>
      </form>

      <!-- Step 2: Secret question -->
      <form v-if="step === 2" @submit.prevent="handleVerifyAnswer" class="login-form">
        <div class="form-group">
          <label>Pregunta secreta</label>
          <p style="color: #718096; font-size: 14px; margin-bottom: 10px;">{{ preguntaSecreta }}</p>
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
          {{ loading ? 'Verificando...' : 'Verificar' }}
        </button>

        <div class="links">
          <a @click="step = 1" class="link" style="cursor: pointer;">Volver atrás</a>
        </div>
      </form>

      <!-- Step 3: New password -->
      <form v-if="step === 3" @submit.prevent="handleUpdatePassword" class="login-form">
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
          <a @click="step = 2" class="link" style="cursor: pointer;">Volver atrás</a>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { verifyEmail, verifyAnswer, updatePassword } from '@/services/passwordRecovery'

const router = useRouter()

const step = ref(1)
const form = ref({
  email: '',
  respuesta_secreta: '',
  new_password: '',
  new_password_confirmation: ''
})
const preguntaSecreta = ref('')
const error = ref('')
const loading = ref(false)
const respuestaSecreta = ref('')
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)

async function handleVerifyEmail() {
  error.value = ''
  loading.value = true

  try {
    const response = await verifyEmail(form.value.email)
    preguntaSecreta.value = response.pregunta_secreta
    step.value = 2
  } catch (err) {
    error.value = err.response?.data?.errors?.email?.[0] || 'Error al verificar el correo electrónico.'
  } finally {
    loading.value = false
  }
}

async function handleVerifyAnswer() {
  error.value = ''
  loading.value = true

  try {
    respuestaSecreta.value = form.value.respuesta_secreta
    await verifyAnswer(form.value.email, form.value.respuesta_secreta)
    step.value = 3
  } catch (err) {
    error.value = err.response?.data?.errors?.respuesta_secreta?.[0] || 'La respuesta secreta no es correcta.'
  } finally {
    loading.value = false
  }
}

async function handleUpdatePassword() {
  error.value = ''

  if (form.value.new_password !== form.value.new_password_confirmation) {
    error.value = 'Las contraseñas no coinciden.'
    return
  }

  loading.value = true

  try {
    await updatePassword(
      form.value.email,
      form.value.new_password,
      form.value.new_password_confirmation,
      respuestaSecreta.value
    )
    router.push('/login?status=Contraseña actualizada exitosamente. Ahora puedes iniciar sesión.')
  } catch (err) {
    error.value = err.response?.data?.errors?.new_password?.[0] || err.response?.data?.message || 'Error al actualizar la contraseña.'
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

