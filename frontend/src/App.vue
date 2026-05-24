<template>
  <!-- Guest Layout: Login / Register / Home -->
  <div v-if="!isAuthenticated" class="min-h-screen bg-surface-container-lowest text-on-surface">
    <router-view />
  </div>

  <!-- Authenticated Layout: Sidebar + Main -->
  <div v-else class="flex min-h-screen bg-surface-dim text-on-surface">

    <!-- Sidebar Desktop -->
    <aside class="fixed left-0 h-screen w-64 bg-surface-container-lowest border-r border-white/5 flex flex-col py-8 z-40 hidden md:flex">
      <div class="px-6 mb-12">
        <h1 class="font-extrabold text-2xl text-primary-container tracking-tighter">PulseFit</h1>
        <p class="text-xs text-on-surface-variant mt-1 tracking-widest uppercase font-mono">Elite Performance</p>
      </div>

      <nav class="flex-1 flex flex-col space-y-1">
        <router-link
          to="/dashboard"
          class="flex items-center gap-4 py-3 px-6 transition-all duration-200"
          :class="isActive('/dashboard')
            ? 'bg-neon-glow text-primary-fixed-dim border-r-4 border-primary-container translate-x-px'
            : 'text-on-surface-variant hover:bg-surface-variant/50 hover:text-on-surface'"
        >
          <span class="material-symbols-outlined text-[22px]">dashboard</span>
          <span class="text-xs tracking-widest uppercase font-mono font-semibold">Panel</span>
        </router-link>

        <router-link
          to="/routines"
          class="flex items-center gap-4 py-3 px-6 transition-all duration-200"
          :class="isActive('/routines')
            ? 'bg-neon-glow text-primary-fixed-dim border-r-4 border-primary-container translate-x-px'
            : 'text-on-surface-variant hover:bg-surface-variant/50 hover:text-on-surface'"
        >
          <span class="material-symbols-outlined text-[22px]">fitness_center</span>
          <span class="text-xs tracking-widest uppercase font-mono font-semibold">Rutinas</span>
        </router-link>

        <router-link
          to="/nutrition"
          class="flex items-center gap-4 py-3 px-6 transition-all duration-200"
          :class="isActive('/nutrition')
            ? 'bg-neon-glow text-primary-fixed-dim border-r-4 border-primary-container translate-x-px'
            : 'text-on-surface-variant hover:bg-surface-variant/50 hover:text-on-surface'"
        >
          <span class="material-symbols-outlined text-[22px]">restaurant</span>
          <span class="text-xs tracking-widest uppercase font-mono font-semibold">Nutrición</span>
        </router-link>

        <router-link
          to="/profile"
          class="flex items-center gap-4 py-3 px-6 transition-all duration-200"
          :class="isActive('/profile')
            ? 'bg-neon-glow text-primary-fixed-dim border-r-4 border-primary-container translate-x-px'
            : 'text-on-surface-variant hover:bg-surface-variant/50 hover:text-on-surface'"
        >
          <span class="material-symbols-outlined text-[22px]">person</span>
          <span class="text-xs tracking-widest uppercase font-mono font-semibold">Perfil</span>
        </router-link>

        <router-link
          v-if="isAdmin"
          to="/admin"
          class="flex items-center gap-4 py-3 px-6 transition-all duration-200"
          :class="isActive('/admin')
            ? 'bg-neon-glow text-primary-fixed-dim border-r-4 border-primary-container translate-x-px'
            : 'text-on-surface-variant hover:bg-surface-variant/50 hover:text-on-surface'"
        >
          <span class="material-symbols-outlined text-[22px]">admin_panel_settings</span>
          <span class="text-xs tracking-widest uppercase font-mono font-semibold">Admin</span>
        </router-link>
      </nav>

      <div class="px-6 mt-auto">
        <router-link
          v-if="!isPremium"
          to="/register"
          class="w-full py-3 bg-primary-container text-on-primary font-bold rounded-lg text-sm shadow-[0_0_15px_rgba(57,255,20,0.3)] hover:shadow-[0_0_25px_rgba(57,255,20,0.5)] transition-all mb-6 text-center block"
        >
          Actualizar a Élite
        </router-link>
        <div class="border-t border-white/5 pt-4 space-y-1">
          <button
            @click="logout"
            class="flex items-center gap-4 text-on-surface-variant py-2 px-0 hover:text-on-surface transition-colors w-full"
          >
            <span class="material-symbols-outlined text-[22px]">logout</span>
            <span class="text-xs tracking-widest uppercase font-mono font-semibold">Cerrar Sesión</span>
          </button>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">

      <!-- Top Header -->
      <header class="bg-surface-glass backdrop-blur-md sticky top-0 z-30 border-b border-white/5 h-16 flex items-center justify-between px-4 md:px-12">
        <div class="flex items-center gap-4">
          <button class="md:hidden text-primary-container">
            <span class="material-symbols-outlined">menu</span>
          </button>
          <span class="md:hidden font-extrabold text-xl text-primary-container tracking-tighter">PulseFit</span>
        </div>
        <div class="flex items-center gap-6">
          <div class="relative">
            <button @click="showNotifs = !showNotifs" class="text-on-surface-variant hover:text-primary-fixed-dim transition-colors relative">
              <span class="material-symbols-outlined">notifications</span>
              <span v-if="notifications.length" class="absolute top-0 right-0 w-2 h-2 bg-primary-container rounded-full"></span>
            </button>
            <div v-if="showNotifs" class="absolute right-0 top-10 w-80 glass-card rounded-xl border border-white/10 shadow-2xl z-50 overflow-hidden">
              <div class="p-4 border-b border-white/5 flex items-center justify-between">
                <span class="text-xs font-mono font-bold text-white uppercase tracking-wider">Notificaciones</span>
                <button @click="notifications = []; showNotifs = false" class="text-[10px] text-on-surface-variant font-mono hover:text-primary-container transition-colors">LIMPIAR</button>
              </div>
              <div v-if="notifications.length === 0" class="p-6 text-center text-xs text-on-surface-variant font-mono">Sin notificaciones</div>
              <div v-else class="divide-y divide-white/5 max-h-64 overflow-y-auto">
                <div v-for="n in notifications" :key="n.id" class="p-4 hover:bg-surface-container transition-colors">
                  <p class="text-sm text-white font-semibold">{{ n.title }}</p>
                  <p class="text-xs text-on-surface-variant mt-0.5">{{ n.body }}</p>
                </div>
              </div>
            </div>
          </div>
          <router-link to="/profile" class="w-9 h-9 rounded-full bg-surface-container-high border border-primary-container/30 flex items-center justify-center font-bold text-sm text-primary-container hover:border-primary-container transition-colors">
            {{ userInitial }}
          </router-link>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 pb-20 md:pb-0">
        <router-view />
      </main>

      <!-- Footer -->
      <footer class="bg-surface-container-lowest border-t border-white/5 hidden md:block">
        <div class="flex justify-between items-center px-12 py-6">
          <p class="text-xs text-on-surface-variant font-mono">© 2024 PulseFit Ecosystem. All rights reserved.</p>
          <div class="flex gap-6">
            <a class="text-xs text-on-surface-variant hover:text-primary-container transition-colors font-mono" href="#">Privacidad</a>
            <a class="text-xs text-on-surface-variant hover:text-primary-container transition-colors font-mono" href="#">Términos</a>
            <a class="text-xs text-on-surface-variant hover:text-primary-container transition-colors font-mono" href="#">Soporte</a>
          </div>
        </div>
      </footer>
    </div>

    <!-- Mobile Bottom Nav -->
    <nav class="md:hidden fixed bottom-0 left-0 w-full bg-surface-glass backdrop-blur-md border-t border-white/5 flex justify-around py-3 z-50">
      <router-link to="/dashboard" class="flex flex-col items-center gap-1" :class="isActive('/dashboard') ? 'text-primary-container' : 'text-on-surface-variant'">
        <span class="material-symbols-outlined text-[22px]">dashboard</span>
        <span class="text-[10px] font-mono">Panel</span>
      </router-link>
      <router-link to="/routines" class="flex flex-col items-center gap-1" :class="isActive('/routines') ? 'text-primary-container' : 'text-on-surface-variant'">
        <span class="material-symbols-outlined text-[22px]">fitness_center</span>
        <span class="text-[10px] font-mono">Rutinas</span>
      </router-link>
      <router-link to="/nutrition" class="flex flex-col items-center gap-1" :class="isActive('/nutrition') ? 'text-primary-container' : 'text-on-surface-variant'">
        <span class="material-symbols-outlined text-[22px]">restaurant</span>
        <span class="text-[10px] font-mono">Nutrición</span>
      </router-link>
      <router-link to="/profile" class="flex flex-col items-center gap-1" :class="isActive('/profile') ? 'text-primary-container' : 'text-on-surface-variant'">
        <span class="material-symbols-outlined text-[22px]">person</span>
        <span class="text-[10px] font-mono">Perfil</span>
      </router-link>
    </nav>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const route = useRoute()

const isAuthenticated = ref(false)
const isAdmin = ref(false)
const isPremium = ref(false)
const userName = ref('')
const showNotifs = ref(false)
const notifications = ref([
  { id: 1, title: 'Rutina generada', body: 'Tu rutina de Pecho está lista para entrenar.' },
  { id: 2, title: 'Objetivo nutricional', body: 'Llevas el 80% de tus calorías diarias.' },
])

const userInitial = computed(() => userName.value ? userName.value.charAt(0).toUpperCase() : 'U')

const isActive = (path) => route.path === path

const checkAuth = async () => {
  const token = localStorage.getItem('auth_token')
  if (!token) {
    isAuthenticated.value = false
    return
  }
  try {
    const res = await axios.get('/api/user', { headers: { Authorization: `Bearer ${token}` } })
    isAuthenticated.value = true
    isAdmin.value = res.data.role === 'admin'
    isPremium.value = ['premium', 'admin'].includes(res.data.role)
    userName.value = res.data.name || ''
  } catch {
    isAuthenticated.value = false
    isAdmin.value = false
    isPremium.value = false
    localStorage.removeItem('auth_token')
  }
}

onMounted(checkAuth)
watch(() => route.path, checkAuth)

const logout = async () => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    try {
      await axios.post('/api/logout', {}, { headers: { Authorization: `Bearer ${token}` } })
    } catch {}
  }
  localStorage.removeItem('auth_token')
  isAuthenticated.value = false
  router.push('/login')
}
</script>
