import api from './api'

export async function getSecretQuestions() {
  try {
    console.log('📋 Obteniendo preguntas secretas...')
    const response = await api.get('/preguntas-secretas')
    console.log('✅ Preguntas secretas recibidas:', response.data)
    
    // Verificar que la respuesta tenga la estructura esperada
    if (response.data && response.data.preguntas) {
      return response.data.preguntas
    } else if (Array.isArray(response.data)) {
      // Si la respuesta es un array directo
      return response.data
    } else {
      console.warn('⚠️ Respuesta inesperada de preguntas secretas:', response.data)
      return []
    }
  } catch (error) {
    console.error('❌ Error fetching secret questions:', error)
    console.error('❌ Error details:', {
      message: error.message,
      status: error.response?.status,
      data: error.response?.data,
      url: error.config?.url,
      baseURL: error.config?.baseURL
    })
    return []
  }
}

