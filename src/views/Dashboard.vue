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
  // Si ya tenemos el usuario del store (después del login), usarlo
  if (authStore.user) {
    user.value = authStore.user
    return
  }
  
  // Si no tenemos el usuario, intentar obtenerlo
  try {
    user.value = await authStore.fetchUser()
  } catch (error) {
    console.error('Error al obtener usuario en dashboard:', error)
    // Solo redirigir si es un error 401 (token inválido)
    if (error.response?.status === 401) {
      router.push('/login')
    }
    // Para otros errores, mostrar el error pero no redirigir
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

