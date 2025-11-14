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
  
  // Verificar token desde localStorage primero (más confiable después de OAuth)
  let token = localStorage.getItem('token') || authStore.token
  
  // Detectar si acabamos de venir de OAuth (token en localStorage pero no en store)
  // Esto indica que el token fue guardado recientemente por AuthCallback
  const isFromOAuth = token && !authStore.token
  
  // Si hay token en localStorage pero no en el store, actualizar el store
  if (token && !authStore.token) {
    console.log('🔄 Token encontrado en localStorage, actualizando store...')
    console.log('✅ Detectado flujo de OAuth - acceso inmediato sin verificaciones')
    authStore.setAuth(null, token)
  }

  // Si la ruta requiere autenticación
  if (to.meta.requiresAuth) {
    if (!token) {
      // No hay token, redirigir al login
      console.log('❌ No hay token, redirigiendo al login')
      next('/login')
      return
    }
    
    // Si viene de OAuth callback O si detectamos flujo de OAuth (token recién guardado)
    // O si estamos navegando al dashboard desde el callback
    // permitir acceso INMEDIATO sin ninguna verificación
    // NO intentar obtener usuario, NO verificar nada - solo el token es suficiente
    const comingFromCallback = from.path === '/auth/callback' || from.name === 'auth-callback'
    const goingToDashboard = to.path === '/dashboard' && comingFromCallback
    const hasTokenFromOAuth = isFromOAuth || (token && comingFromCallback)
    
    if (comingFromCallback || goingToDashboard || hasTokenFromOAuth) {
      console.log('✅ Viniendo de OAuth - acceso inmediato sin verificaciones')
      console.log('✅ Token presente:', token ? 'Sí' : 'No')
      console.log('✅ From:', from.path, 'To:', to.path)
      next()
      return
    }
    
    // Para otras rutas, verificar token y permitir acceso
    // NO intentar obtener usuario aquí - el dashboard lo hará si es necesario
    console.log('✅ Token encontrado, permitiendo acceso')
    next()
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

