<template>
  <div class="min-h-screen bg-dark-base text-white font-sans selection:bg-neon selection:text-dark-base">
    <!-- Navbar Premium Glassmorphism -->
    <nav class="fixed top-0 w-full z-50 bg-dark-base/40 backdrop-blur-2xl border-b border-white/5 transition-all duration-300">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
          <div class="flex items-center">
            <router-link to="/" class="flex items-center gap-3 group">
              <div class="w-8 h-8 bg-neon rounded-lg flex items-center justify-center shadow-[0_0_15px_rgba(57,255,20,0.4)] group-hover:shadow-[0_0_25px_rgba(57,255,20,0.6)] transition-all">
                <svg class="w-5 h-5 text-dark-base" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
              </div>
              <span class="font-heading font-black text-xl tracking-tighter text-white group-hover:text-neon transition-colors">PULSE<span class="text-slate-400">FIT</span></span>
            </router-link>
          </div>
          
          <div class="hidden md:flex items-center gap-8" v-if="isAuthenticated">
            <router-link to="/dashboard" class="text-sm font-bold text-slate-400 hover:text-neon transition-all" active-class="text-white border-b-2 border-neon py-1">Inicio</router-link>
            <router-link to="/routines" class="text-sm font-bold text-slate-400 hover:text-neon transition-all" active-class="text-white border-b-2 border-neon py-1">Entrenamientos</router-link>
            <router-link to="/nutrition" class="text-sm font-bold text-slate-400 hover:text-neon transition-all" active-class="text-white border-b-2 border-neon py-1">Nutrición</router-link>
            <router-link v-if="isAdmin" to="/admin" class="text-sm font-bold text-amber-500 hover:text-amber-400 transition-all flex items-center gap-1" active-class="text-amber-400 border-b-2 border-amber-400 py-1">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
              Admin
            </router-link>
            
            <div class="h-6 w-px bg-dark-border"></div>
            
            <router-link to="/profile" class="text-sm font-bold text-slate-400 hover:text-white transition-all">Perfil</router-link>
            <button @click="logout" class="text-sm font-bold text-slate-400 hover:text-rose-400 transition-all">Salir</button>
          </div>
          
          <div class="flex items-center gap-4" v-else>
            <router-link to="/login" class="text-sm font-bold text-slate-400 hover:text-white transition-colors">Entrar</router-link>
            <router-link to="/register" class="text-sm font-bold bg-white text-dark-base px-5 py-2.5 rounded-xl hover:bg-neon transition-all shadow-sm">Registrarse</router-link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen pt-20">
      <router-view></router-view>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const route = useRoute()

const isAuthenticated = ref(false)
const isAdmin = ref(false)

const checkAuth = async () => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    try {
      const res = await axios.get('/api/user', {
        headers: { Authorization: `Bearer ${token}` }
      })
      isAuthenticated.value = true
      isAdmin.value = res.data.role === 'admin'
    } catch (e) {
      isAuthenticated.value = false
      isAdmin.value = false
      localStorage.removeItem('auth_token')
    }
  } else {
    isAuthenticated.value = false
    isAdmin.value = false
  }
}

onMounted(() => {
  checkAuth()
})

watch(() => route.path, () => {
  checkAuth()
})

const logout = async () => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    try {
      await axios.post('/api/logout', {}, {
        headers: { Authorization: `Bearer ${token}` }
      })
    } catch (e) {}
  }
  localStorage.removeItem('auth_token')
  isAuthenticated.value = false
  isAdmin.value = false
  router.push('/login')
}
</script>
