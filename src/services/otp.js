import api from './api'

/**
 * Verificar código OTP para activación de cuenta
 * @param {string} email - Email del usuario
 * @param {string} code - Código OTP de 6 dígitos
 * @returns {Promise<Object>} Respuesta con token y datos del usuario
 */
export async function verifyActivationOTP(email, code) {
  try {
    const response = await api.post('/otp/verify-activation', {
      email,
      code
    })
    return response.data
  } catch (error) {
    throw error
  }
}

/**
 * Reenviar código OTP de activación
 * @param {string} email - Email del usuario
 * @returns {Promise<Object>} Respuesta con mensaje de confirmación
 */
export async function resendActivationOTP(email) {
  try {
    const response = await api.post('/otp/resend-activation', {
      email
    })
    return response.data
  } catch (error) {
    throw error
  }
}

/**
 * Verificar código OTP para recuperación de contraseña
 * @param {string} email - Email del usuario
 * @param {string} code - Código OTP de 6 dígitos
 * @returns {Promise<Object>} Respuesta con mensaje de confirmación
 */
export async function verifyPasswordRecoveryOTP(email, code) {
  try {
    const response = await api.post('/otp/verify-password-recovery', {
      email,
      code
    })
    return response.data
  } catch (error) {
    throw error
  }
}

