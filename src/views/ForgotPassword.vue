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
          <input
            type="password"
            id="new_password"
            v-model="form.new_password"
            required
            minlength="8"
            placeholder="••••••••"
          />
        </div>

        <div class="form-group">
          <label for="new_password_confirmation">Confirmar nueva contraseña</label>
          <input
            type="password"
            id="new_password_confirmation"
            v-model="form.new_password_confirmation"
            required
            minlength="8"
            placeholder="••••••••"
          />
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
</style>

