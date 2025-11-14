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
  
  // Verificar token desde localStorage también (por si el store no se ha actualizado)
  let token = authStore.token || localStorage.getItem('token')
  
  // Si hay token en localStorage pero no en el store, actualizar el store
  if (token && !authStore.token) {
    console.log('🔄 Token encontrado en localStorage, actualizando store...')
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
    
    // Si viene de OAuth callback, solo verificar token y permitir acceso
    // No intentar obtener usuario aquí, el dashboard lo hará
    if (from.path === '/auth/callback') {
      console.log('✅ Viniendo de OAuth callback, permitiendo acceso directo')
      next()
      return
    }
    
    console.log('✅ Token encontrado')
    
    // Si ya tenemos el usuario, no hacer petición
    if (authStore.user) {
      console.log('✅ Usuario ya disponible en store')
      next()
      return
    }
    
    // Intentar obtener usuario solo si no viene de OAuth
    // Pero no bloquear el acceso si falla (excepto 401)
    try {
      console.log('🔄 Obteniendo usuario del backend...')
      await authStore.fetchUser()
      console.log('✅ Usuario obtenido:', authStore.user?.email)
    } catch (error) {
      console.error('❌ Error al obtener usuario:', error)
      
      // Solo bloquear si es un error 401 (token inválido)
      if (error.response?.status === 401) {
        console.error('❌ Token inválido (401), limpiando auth...')
        authStore.clearAuth()
        next('/login')
        return
      }
      // Para otros errores, permitir acceso - el dashboard manejará el error
      console.warn('⚠️ Error al obtener usuario, pero permitiendo acceso')
    }
    
    // Usuario autenticado (tiene token), permitir acceso
    console.log('✅ Acceso permitido a ruta protegida:', to.path)
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

