<template>
  <div class="dashboard-container">
    <div class="dashboard-card">
      <div class="user-info">
        <div>
          <h2>Bienvenido, {{ user?.name }}</h2>
          <div class="user-details">
            {{ user?.email }}
          </div>
        </div>
        <button @click="handleLogout" class="btn-logout">Cerrar sesión</button>
      </div>

      <div class="content-box">
        <h3>📋 Información del Proyecto</h3>
        <p><strong>NOMBRE DE LA ACTIVIDAD:</strong></p>
        <p>Definición de la práctica de clase – Módulo de usuario con métodos de autenticación y cifrado.</p>
        
        <p style="margin-top: 20px;"><strong>INTEGRANTES DEL EQUIPO:</strong></p>
        <ul style="color: #718096; line-height: 1.8; margin-left: 20px;">
          <li>Ontiveros Sanjuan Diana Monserrat - 20230019</li>
          <li>Flores cervantes Elizabeth - 20230015</li>
          <li>Martínez Ramírez Karla Yoselin – 20221078</li>
          <li>Hernández Valdes Francisco - 20230079</li>
          <li>Pérez de la Cruz Miguel Ángel - 20230091</li>
          <li>Ontiveros García Axali Jerusalén - 20230039</li>
        </ul>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
          <h3 style="margin-bottom: 15px;">🔐 Métodos de Cifrado Implementados</h3>
          
          <div style="margin-bottom: 20px;">
            <p style="font-weight: 600; color: #1a202c; margin-bottom: 8px;">• Cifrado AES (Advanced Encryption Standard):</p>
            <p style="color: #718096; line-height: 1.6;">
              Es un algoritmo de cifrado simétrico que utiliza la misma clave para cifrar y descifrar los datos. 
              Se caracteriza por eficiencia y seguridad, siendo adoptado para proteger información confidencial en sistemas modernos. 
              AES garantiza que los datos almacenados o transmitidos no puedan ser leídos por terceros no autorizados.
            </p>
          </div>

          <div>
            <p style="font-weight: 600; color: #1a202c; margin-bottom: 8px;">• Cifrado RSA (Rivest–Shamir–Adleman):</p>
            <p style="color: #718096; line-height: 1.6;">
              Es un algoritmo de cifrado asimétrico que emplea un par de claves: una pública y una privada. 
              La clave pública se usa para cifrar la información y la privada para descifrarla. Este mecanismo permite intercambiar 
              datos de forma segura incluso en entornos no confiables, además de ser utilizado en firmas digitales para verificar 
              la autenticidad e integridad de los mensajes.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const user = ref(null)

onMounted(async () => {
  // Asegurar que el token del store esté sincronizado con localStorage
  const tokenFromStorage = localStorage.getItem('token')
  if (tokenFromStorage && !authStore.token) {
    console.log('🔄 Sincronizando token desde localStorage al store...')
    authStore.setAuth(null, tokenFromStorage)
  }
  
  // Si ya tenemos el usuario del store (después del login), usarlo
  if (authStore.user) {
    user.value = authStore.user
    console.log('✅ Usuario encontrado en store:', authStore.user)
    return
  }
  
  // Verificar que tenemos token antes de intentar obtener usuario
  if (!authStore.token && !tokenFromStorage) {
    console.error('❌ No hay token disponible')
    router.push('/login')
    return
  }
  
  // Intentar obtener usuario de forma asíncrona sin bloquear
  // NO redirigir al login - el usuario está autenticado (tiene token)
  // Después de OAuth, solo tenemos el token y necesitamos obtener los datos del usuario
  console.log('🔄 No hay usuario en store, obteniendo del backend...')
  console.log('🔄 Token disponible:', authStore.token ? 'Sí (en store)' : tokenFromStorage ? 'Sí (en localStorage)' : 'No')
  
  // Función para intentar obtener usuario
  const attemptFetchUser = async (attemptNumber = 1) => {
    try {
      // Sincronizar token antes de cada intento
      const tokenFromStorage = localStorage.getItem('token')
      if (tokenFromStorage && (!authStore.token || authStore.token !== tokenFromStorage)) {
        console.log(`🔄 Intento ${attemptNumber}: Sincronizando token desde localStorage...`)
        authStore.setAuth(null, tokenFromStorage)
      }
      
      console.log(`🔄 Intento ${attemptNumber} de obtener usuario...`)
      console.log(`🔄 Token en store:`, authStore.token ? 'Presente' : 'No presente')
      console.log(`🔄 Token en localStorage:`, tokenFromStorage ? 'Presente' : 'No presente')
      
      const userData = await authStore.fetchUser()
      user.value = userData
      console.log('✅ Usuario obtenido del backend:', userData)
      return true
    } catch (error) {
      console.error(`❌ Error en intento ${attemptNumber}:`, error)
      console.error('❌ Error details:', {
        status: error.response?.status,
        message: error.message,
        data: error.response?.data,
        hasToken: !!authStore.token,
        hasTokenInStorage: !!localStorage.getItem('token')
      })
      
      // Si es un 401, verificar que el token todavía esté presente
      if (error.response?.status === 401) {
        const tokenStillExists = localStorage.getItem('token')
        if (!tokenStillExists) {
          console.error('❌ Token fue eliminado, no se puede reintentar')
          return false
        }
        console.log('⚠️ Token todavía existe, se puede reintentar')
      }
      
      return false
    }
  }
  
  // Primer intento inmediato
  const success = await attemptFetchUser(1)
  
  if (!success) {
    // Segundo intento después de 1 segundo
    console.log('⚠️ Primer intento falló, reintentando en 1 segundo...')
    setTimeout(async () => {
      const success2 = await attemptFetchUser(2)
      if (!success2) {
        // Tercer intento después de 2 segundos más
        console.log('⚠️ Segundo intento falló, reintentando en 2 segundos más...')
        setTimeout(async () => {
          const success3 = await attemptFetchUser(3)
          if (!success3) {
            console.error('❌ No se pudo obtener usuario después de 3 intentos')
            // No redirigir - mostrar dashboard sin nombre pero funcional
          }
        }, 2000)
      }
    }, 1000)
  }
})

async function handleLogout() {
  try {
    await authStore.logout()
  } catch (error) {
    console.error('Logout error:', error)
  } finally {
    // Siempre redirigir al login, incluso si hay error
    router.push('/login')
  }
}
</script>

<style scoped>
.dashboard-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 40px 20px;
}

.dashboard-card {
  background: white;
  border-radius: 20px;
  padding: 40px;
  max-width: 1200px;
  margin: 0 auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.user-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 2px solid #e5e7eb;
}

.user-info h2 {
  font-size: 28px;
  color: #1a202c;
}

.user-info .user-details {
  color: #718096;
  font-size: 14px;
}

.btn-logout {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
  text-decoration: none;
  display: inline-block;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-logout:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.content-box {
  background: #f9fafb;
  border-radius: 12px;
  padding: 30px;
  margin-top: 20px;
}

.content-box h3 {
  color: #1a202c;
  margin-bottom: 15px;
}

.content-box p {
  color: #718096;
  line-height: 1.6;
}
</style>

