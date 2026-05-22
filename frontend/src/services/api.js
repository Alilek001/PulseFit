import axios from 'axios'
import { reactive } from 'vue'

// Configuración base de Axios para conectar con Laravel
axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Content-Type'] = 'application/json'
// Fundamental para enviar cookies de sesión o encabezados CORS si fuera necesario
axios.defaults.withCredentials = true

// Estado global de autenticación (reactivo)
export const authStore = reactive({
  user: null,
  token: localStorage.getItem('auth_token') || null,
  isPremium: false,
})

// Interceptor: inyecta el token de Sanctum en cada petición al backend
axios.interceptors.request.use(config => {
  if (authStore.token) {
    config.headers.Authorization = `Bearer ${authStore.token}`
  }
  return config
})

// Colección de servicios API para la plataforma
export const fitnessService = {
  getDashboard() {
    return axios.get('/api/dashboard')
  },
  logProgress(data) {
    return axios.post('/api/progress', data)
  },
  updateProfile(data) {
    return axios.post('/api/profile', data)
  },
  getAdminStats() {
    return axios.get('/api/admin/stats')
  },
  updateUserRole(id, role) {
    return axios.post(`/api/admin/users/${id}/role`, { role })
  },
  getRoutines() {
    return axios.get('/api/routines')
  },
  generateRoutine(data) {
    return axios.post('/api/routines/generate', data)
  },
  getRecipes() {
    return axios.get('/api/recipes')
  }
}
