import api from './api'

export async function verifyEmail(email, method = 'pregunta') {
  try {
    const response = await api.post('/password/verify-email', { 
      email,
      method // 'pregunta' o 'otp'
    })
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

export async function updatePassword(email, new_password, password_confirmation, respuesta_secreta = null, method = 'pregunta', otp_code = null) {
  try {
    const data = {
      email,
      new_password,
      new_password_confirmation: password_confirmation,
      method
    }
    
    if (method === 'pregunta' && respuesta_secreta) {
      data.respuesta_secreta = respuesta_secreta
    } else if (method === 'otp' && otp_code) {
      data.otp_code = otp_code
    }
    
    const response = await api.post('/password/update', data)
    return response.data
  } catch (error) {
    throw error
  }
}

