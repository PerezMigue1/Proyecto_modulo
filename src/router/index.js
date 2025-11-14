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
  },
  {
    path: '/verify-otp',
    name: 'verify-otp',
    component: () => import('@/views/VerifyOTP.vue'),
    meta: { requiresGuest: true }
  },
  {
    path: '/password/update',
    name: 'password-update',
    component: () => import('@/views/PasswordUpdate.vue'),
    meta: { requiresGuest: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  // Verificar token desde localStorage primero (más confiable después de OAuth)
  // Siempre leer de localStorage primero porque es la fuente de verdad
  let token = localStorage.getItem('token')
  
  // Si hay token en localStorage pero no en el store, actualizar el store
  if (token && !authStore.token) {
    console.log('🔄 Token encontrado en localStorage, actualizando store...')
    authStore.setAuth(null, token)
  }
  
  // Si no hay token en localStorage pero hay en el store, limpiar el store
  if (!token && authStore.token) {
    console.log('🔄 No hay token en localStorage, limpiando store...')
    authStore.clearAuth()
  }

  // Si la ruta requiere autenticación
  if (to.meta.requiresAuth) {
    if (!token) {
      // No hay token, redirigir al login
      console.log('❌ No hay token, redirigiendo al login')
      console.log('❌ Ruta actual:', to.path)
      next('/login')
      return
    }
    
    // Si hay token, permitir acceso inmediato
    // NO verificar nada más - el token es suficiente
    // El dashboard se encargará de obtener los datos del usuario si es necesario
    console.log('✅ Token encontrado, permitiendo acceso al dashboard')
    console.log('✅ Token:', token.substring(0, 20) + '...')
    console.log('✅ From:', from.path, 'To:', to.path)
    next()
    return
  } 
  // Si la ruta requiere que el usuario NO esté autenticado
  else if (to.meta.requiresGuest) {
    if (token) {
      // Ya está autenticado (tiene token), redirigir al dashboard
      console.log('✅ Usuario autenticado, redirigiendo al dashboard')
      console.log('✅ Ruta actual:', to.path)
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

