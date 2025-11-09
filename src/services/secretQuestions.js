import api from './api'

export async function getSecretQuestions() {
  try {
    const response = await api.get('/preguntas-secretas')
    return response.data.preguntas
  } catch (error) {
    console.error('Error fetching secret questions:', error)
    return []
  }
}

