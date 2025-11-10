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
      next('/login')
      return
    }
    
    // Hay token, si no tenemos el usuario, intentar obtenerlo
    // Pero si ya lo tenemos (por ejemplo, después del login), no hacer la petición
    if (!authStore.user) {
      try {
        // Intentar obtener usuario
        await authStore.fetchUser()
      } catch (error) {
        console.error('Error al obtener usuario:', error)
        // Si es un error 401 (token inválido), limpiar auth y redirigir
        if (error.response?.status === 401) {
          authStore.clearAuth()
          next('/login')
          return
        }
        // Para otros errores, permitir acceso pero el componente Dashboard manejará el error
        // Esto evita que el usuario sea sacado inmediatamente después del login
      }
    }
    
    // Usuario autenticado, permitir acceso
    next()
  } 
  // Si la ruta requiere que el usuario NO esté autenticado
  else if (to.meta.requiresGuest) {
    if (token && authStore.user) {
      // Ya está autenticado y tiene usuario, redirigir al dashboard
      next('/dashboard')
      return
    }
    
    // No está autenticado o no tiene usuario, permitir acceso
    next()
  } 
  // Ruta pública, permitir acceso
  else {
    next()
  }
})

export default router

