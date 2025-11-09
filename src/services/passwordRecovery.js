import api from './api'

export async function verifyEmail(email) {
  try {
    const response = await api.post('/password/verify-email', { email })
    return response.data
  } catch (error) {
    throw error
  }
}

export async function verifyAnswer(email, respuesta_secreta) {
  try {
    const response = await api.post('/password/verify-answer', {
      email,
      respuesta_secreta
    })
    return response.data
  } catch (error) {
    throw error
  }
}

export async function updatePassword(email, new_password, password_confirmation, respuesta_secreta) {
  try {
    const response = await api.post('/password/update', {
      email,
      new_password,
      new_password_confirmation: password_confirmation,
      respuesta_secreta
    })
    return response.data
  } catch (error) {
    throw error
  }
}

