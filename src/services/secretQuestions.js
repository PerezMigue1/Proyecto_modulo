import api from './api'

export async function getSecretQuestions() {
  try {
    console.log('📋 Obteniendo preguntas secretas...')
    console.log('📋 URL completa:', api.defaults.baseURL + '/preguntas-secretas')
    
    const response = await api.get('/preguntas-secretas')
    
    console.log('✅ Response status:', response.status)
    console.log('✅ Response data:', response.data)
    console.log('✅ Response data type:', typeof response.data)
    console.log('✅ Is array:', Array.isArray(response.data))
    
    // Verificar que la respuesta tenga la estructura esperada
    if (response.data) {
      // Caso 1: response.data.preguntas existe (objeto con propiedad preguntas)
      if (response.data.preguntas && Array.isArray(response.data.preguntas)) {
        console.log('✅ Encontradas preguntas en response.data.preguntas:', response.data.preguntas.length)
        return response.data.preguntas
      }
      
      // Caso 2: response.data es un array directo
      if (Array.isArray(response.data)) {
        console.log('✅ response.data es un array directo:', response.data.length)
        return response.data
      }
      
      // Caso 3: response.data tiene otra estructura
      console.warn('⚠️ Respuesta inesperada de preguntas secretas:', response.data)
      console.warn('⚠️ Keys en response.data:', Object.keys(response.data))
      return []
    } else {
      console.warn('⚠️ response.data es null o undefined')
      return []
    }
  } catch (error) {
    console.error('❌ Error fetching secret questions:', error)
    console.error('❌ Error details:', {
      message: error.message,
      status: error.response?.status,
      statusText: error.response?.statusText,
      data: error.response?.data,
      url: error.config?.url,
      baseURL: error.config?.baseURL,
      fullURL: error.config?.baseURL + error.config?.url,
      code: error.code
    })
    
    // Re-lanzar el error para que el componente pueda manejarlo
    throw error
  }
}

