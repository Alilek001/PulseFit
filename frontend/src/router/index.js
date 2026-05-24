import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/Home.vue'),
    meta: { guestOnly: true }
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue'),
    meta: { guestOnly: true }
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/Register.vue'),
    meta: { guestOnly: true }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('../views/Dashboard.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/routines',
    name: 'Routines',
    component: () => import('../views/Routines.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/nutrition',
    name: 'Nutrition',
    component: () => import('../views/Nutrition.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/profile',
    name: 'Profile',
    component: () => import('../views/Profile.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/admin',
    name: 'Admin',
    component: () => import('../views/Admin.vue'),
    meta: { requiresAuth: true, adminOnly: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem('auth_token')
  let user = null
  let role = 'user'

  if (token) {
    try {
      const res = await axios.get('/api/user', {
        headers: { Authorization: `Bearer ${token}` }
      })
      user = res.data
      role = user.role
    } catch (e) {
      localStorage.removeItem('auth_token')
    }
  }

  if (to.meta.requiresAuth && !user) {
    next({ name: 'Login' })
  } else if (to.meta.guestOnly && user) {
    next({ name: 'Dashboard' })
  } else if (to.meta.adminOnly && role !== 'admin') {
    next({ name: 'Dashboard' })
  } else {
    next()
  }
})

export default router
