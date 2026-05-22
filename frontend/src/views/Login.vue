<template>
  <div class="min-h-[calc(100vh-5rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Fondos luminosos abstractos -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-neon-glow rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-neon-glow opacity-30 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-md w-full space-y-8 glass p-10 rounded-3xl border border-dark-border shadow-[0_8px_32px_rgba(0,255,102,0.05)] relative z-10 animate-[scaleIn_0.4s_ease-out]">
      <div class="text-center">
        <h2 class="mt-2 text-4xl font-display font-black tracking-tight text-white">Inicia sesión</h2>
        <p class="mt-3 text-sm text-slate-400 font-medium">
          O <router-link to="/register" class="text-neon hover:text-white transition-colors font-bold underline decoration-neon/30 underline-offset-4">crea una cuenta nueva</router-link>
        </p>
      </div>
      
      <form class="mt-8 space-y-5" @submit.prevent="handleLogin">
        <!-- Bloque de error -->
        <div v-if="error" class="bg-rose-500/10 border border-rose-500/30 text-rose-400 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-3">
          <span>⚠️</span> {{ error }}
        </div>

        <div class="space-y-4">
          <div>
            <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Correo Electrónico</label>
            <input 
              id="email" 
              v-model="form.email" 
              name="email" 
              type="email" 
              required 
              class="w-full bg-dark-base/50 border border-dark-border focus:border-neon focus:ring-1 focus:ring-neon rounded-xl px-4 py-3.5 text-white text-sm outline-none transition-all placeholder:text-slate-600" 
              placeholder="ejemplo@pulsefit.com" 
            />
          </div>
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Contraseña</label>
              <a href="#" class="text-xs font-bold text-neon hover:text-white transition-colors">¿Olvidaste tu clave?</a>
            </div>
            <input 
              id="password" 
              v-model="form.password" 
              name="password" 
              type="password" 
              required 
              class="w-full bg-dark-base/50 border border-dark-border focus:border-neon focus:ring-1 focus:ring-neon rounded-xl px-4 py-3.5 text-white text-sm outline-none transition-all placeholder:text-slate-600" 
              placeholder="••••••••" 
            />
          </div>
        </div>

        <div class="pt-2">
          <button 
            type="submit" 
            :disabled="loading"
            class="w-full py-3.5 rounded-xl bg-neon text-dark-base font-black text-sm hover:scale-102 active:scale-98 transition-all shadow-[0_0_15px_rgba(57,255,20,0.2)] flex items-center justify-center gap-2"
          >
            <span v-if="loading" class="w-4 h-4 border-2 border-dark-base border-t-transparent rounded-full animate-spin"></span>
            <span>{{ loading ? 'Autenticando...' : 'Acceder al Panel' }}</span>
          </button>
        </div>
      </form>

      <!-- ACCESO RÁPIDO PARA TRIBUNAL DAW -->
      <div class="mt-10 pt-8 border-t border-dark-border/60">
        <div class="text-center mb-4">
          <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-dark-panel px-2">Acceso Rápido para Tribunal</span>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <button @click="quickLogin('user@pulsefit.com')" class="flex flex-col items-center p-2 rounded-lg border border-dark-border hover:border-slate-500 hover:bg-slate-800/50 transition-colors group">
            <span class="text-lg mb-1 group-hover:scale-110 transition-transform">👤</span>
            <span class="text-[9px] font-bold text-slate-400 group-hover:text-white">Adrián</span>
            <span class="text-[8px] text-slate-500">Básico</span>
          </button>
          <button @click="quickLogin('premium@pulsefit.com')" class="flex flex-col items-center p-2 rounded-lg border border-neon/30 hover:border-neon hover:bg-neon/10 transition-colors group">
            <span class="text-lg mb-1 group-hover:scale-110 transition-transform">⚡</span>
            <span class="text-[9px] font-bold text-neon group-hover:text-neon-soft">Sofía</span>
            <span class="text-[8px] text-neon/70">Premium</span>
          </button>
          <button @click="quickLogin('admin@pulsefit.com')" class="flex flex-col items-center p-2 rounded-lg border border-purple-500/30 hover:border-purple-500 hover:bg-purple-500/10 transition-colors group">
            <span class="text-lg mb-1 group-hover:scale-110 transition-transform">👑</span>
            <span class="text-[9px] font-bold text-purple-400 group-hover:text-purple-300">Carlos</span>
            <span class="text-[8px] text-purple-500/70">Admin</span>
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { authStore } from '../services/api'
import axios from 'axios'

const router = useRouter()
const form = ref({
  email: '',
  password: ''
})
const error = ref('')
const loading = ref(false)

const handleLogin = async () => {
  error.value = ''
  loading.value = true
  
  try {
    const res = await axios.post('/api/login', form.value)
    if(res.data.token) {
      localStorage.setItem('auth_token', res.data.token)
      // Update global store
      authStore.token = res.data.token
      authStore.user = res.data.user
      authStore.isPremium = ['premium', 'admin'].includes(res.data.user.role)
      router.push('/dashboard')
    }
  } catch (err) {
    // Si la validación falla (ej. 422), Laravel nos manda un JSON con los errores.
    if (err.response?.status === 422) {
      // Tomamos el primer error de la lista
      const validationErrors = err.response.data.errors
      if (validationErrors) {
        error.value = Object.values(validationErrors)[0][0]
      } else {
        error.value = err.response.data.message || 'Credenciales inválidas'
      }
    } else {
      error.value = err.response?.data?.message || 'Error de conexión con el servidor'
    }
  } finally {
    loading.value = false
  }
}

// Botones mágicos para evaluación rápida DAW
const quickLogin = (email) => {
  form.value.email = email
  form.value.password = 'password123'
  handleLogin()
}
</script>
