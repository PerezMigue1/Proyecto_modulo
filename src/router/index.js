import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/Login.vue'),
    meta: { requiresGuest: true }
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/views/Register.vue'),
    meta: { requiresGuest: true }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/Dashboard.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/password/request',
    name: 'password-request',
    component: () => import('@/views/ForgotPassword.vue'),
    meta: { requiresGuest: true }
  },
  {
    path: '/auth/callback',
    name: 'auth-callback',
    component: () => import('@/views/AuthCallback.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  const token = authStore.token

  // Si la ruta requiere autenticación
  if (to.meta.requiresAuth) {
    if (!token) {
      // No hay token, redirigir al login
      console.log('❌ No hay token, redirigiendo al login')
      next('/login')
      return
    }
    
    // Hay token, verificar si tenemos el usuario
    // Si ya lo tenemos (por ejemplo, después del login o OAuth), no hacer petición
    if (!authStore.user) {
      try {
        console.log('🔄 Obteniendo usuario del backend...')
        await authStore.fetchUser()
        console.log('✅ Usuario obtenido:', authStore.user?.email)
      } catch (error) {
        console.error('❌ Error al obtener usuario:', error)
        // Si es un error 401 (token inválido), limpiar auth y redirigir
        if (error.response?.status === 401) {
          console.error('❌ Token inválido (401), limpiando auth...')
          authStore.clearAuth()
          next('/login')
          return
        }
        // Para otros errores (red, servidor, etc.), permitir acceso
        // El dashboard intentará obtener el usuario nuevamente
        console.warn('⚠️ Error al obtener usuario, pero permitiendo acceso al dashboard')
      }
    }
    
    // Usuario autenticado (tiene token), permitir acceso
    console.log('✅ Acceso permitido a ruta protegida')
    next()
  } 
  // Si la ruta requiere que el usuario NO esté autenticado
  else if (to.meta.requiresGuest) {
    if (token) {
      // Ya está autenticado (tiene token), redirigir al dashboard
      console.log('✅ Usuario autenticado, redirigiendo al dashboard')
      next('/dashboard')
      return
    }
    
    // No está autenticado, permitir acceso
    next()
  } 
  // Ruta pública (como /auth/callback), permitir acceso
  else {
    next()
  }
})

export default router

