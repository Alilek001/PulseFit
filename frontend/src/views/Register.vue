<template>
  <div class="flex items-center justify-center min-h-screen p-4 md:p-8">
    <main class="w-full max-w-[1100px] grid grid-cols-1 md:grid-cols-2 glass-card rounded-xl overflow-hidden shadow-2xl">

      <!-- Left: Branding -->
      <section class="relative hidden md:flex flex-col justify-end p-12 min-h-[600px] overflow-hidden">
        <div class="absolute inset-0">
          <div class="absolute inset-0 bg-gradient-to-br from-surface-container-lowest via-surface-container to-surface-container-low"></div>
          <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#39ff14 1px, transparent 1px); background-size: 24px 24px;"></div>
          <div class="absolute bottom-0 left-0 w-64 h-64 bg-primary-container/10 rounded-full blur-[80px]"></div>
        </div>
        <div class="relative z-10">
          <div class="mb-6">
            <span class="text-3xl font-black text-primary-container tracking-tighter">PulseFit</span>
          </div>
          <h1 class="text-6xl font-black text-white tracking-tight leading-none mb-4">
            Comienza Tu<br/>Aventura
          </h1>
          <p class="text-on-surface-variant text-lg max-w-sm">
            Únete a miles de atletas de élite. Entrenamiento basado en datos diseñado para el máximo rendimiento.
          </p>
        </div>
      </section>

      <!-- Right: Register Form -->
      <section class="flex flex-col justify-center p-8 md:p-16 bg-surface-container-lowest">
        <div class="md:hidden mb-8">
          <span class="text-2xl font-black text-primary-container tracking-tighter">PulseFit</span>
        </div>
        <div class="mb-10">
          <h2 class="text-2xl font-semibold text-white mb-2">Crear Cuenta</h2>
          <p class="text-on-surface-variant">Completa tus datos para unirte al ecosistema.</p>
        </div>

        <div v-if="error" class="mb-6 bg-error-container/20 border border-error/30 text-error px-4 py-3 rounded-lg text-sm font-semibold flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">error</span>
          {{ error }}
        </div>

        <form class="space-y-5" @submit.prevent="handleRegister">
          <div class="space-y-2">
            <label class="block text-xs text-on-surface-variant uppercase tracking-widest font-mono">Nombre Completo</label>
            <div class="relative">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">person</span>
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="John Doe"
                class="w-full h-14 pl-12 pr-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white placeholder-on-surface-variant/40 transition-all input-focus-ring"
              />
            </div>
          </div>

          <div class="space-y-2">
            <label class="block text-xs text-on-surface-variant uppercase tracking-widest font-mono">Correo Electrónico</label>
            <div class="relative">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">mail</span>
              <input
                v-model="form.email"
                type="email"
                required
                placeholder="name@example.com"
                class="w-full h-14 pl-12 pr-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white placeholder-on-surface-variant/40 transition-all input-focus-ring"
              />
            </div>
          </div>

          <div class="space-y-2">
            <label class="block text-xs text-on-surface-variant uppercase tracking-widest font-mono">Contraseña</label>
            <div class="relative">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">lock</span>
              <input
                v-model="form.password"
                type="password"
                required
                placeholder="••••••••"
                class="w-full h-14 pl-12 pr-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white placeholder-on-surface-variant/40 transition-all input-focus-ring"
              />
            </div>
          </div>

          <div class="space-y-2">
            <label class="block text-xs text-on-surface-variant uppercase tracking-widest font-mono">Confirmar Contraseña</label>
            <div class="relative">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">lock_reset</span>
              <input
                v-model="form.password_confirmation"
                type="password"
                required
                placeholder="••••••••"
                class="w-full h-14 pl-12 pr-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white placeholder-on-surface-variant/40 transition-all input-focus-ring"
              />
            </div>
          </div>

          <div class="flex flex-col gap-4 pt-2">
            <button
              type="submit"
              :disabled="loading"
              class="h-14 bg-primary-container text-on-primary font-semibold text-lg rounded-lg flex items-center justify-center gap-2 neon-glow-btn transition-all active:scale-95 disabled:opacity-60"
            >
              <span v-if="loading" class="w-5 h-5 border-2 border-on-primary border-t-transparent rounded-full animate-spin"></span>
              {{ loading ? 'Creando...' : 'Crear Cuenta' }}
            </button>

            <div class="relative flex items-center py-1">
              <div class="flex-grow border-t border-white/5"></div>
              <span class="mx-4 text-xs text-on-surface-variant font-mono">¿YA TIENES CUENTA?</span>
              <div class="flex-grow border-t border-white/5"></div>
            </div>

            <router-link
              to="/login"
              class="h-14 bg-surface-variant text-white font-semibold text-lg rounded-lg flex items-center justify-center gap-2 hover:bg-surface-variant/80 transition-all active:scale-95"
            >
              Iniciar Sesión
            </router-link>
          </div>
        </form>

        <footer class="mt-8 text-center">
          <p class="text-xs text-on-surface-variant/60 font-mono">© 2024 PulseFit Ecosystem. All rights reserved.</p>
        </footer>
      </section>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const form = ref({ name: '', email: '', password: '', password_confirmation: '' })
const error = ref('')
const loading = ref(false)

const handleRegister = async () => {
  error.value = ''
  loading.value = true
  try {
    const res = await axios.post('/api/register', form.value)
    if (res.data.token) {
      localStorage.setItem('auth_token', res.data.token)
      router.push('/dashboard')
    }
  } catch (err) {
    if (err.response?.status === 422) {
      const errs = err.response.data.errors
      error.value = errs ? Object.values(errs)[0][0] : err.response.data.message
    } else {
      error.value = err.response?.data?.message || 'Error creating account'
    }
  } finally {
    loading.value = false
  }
}
</script>
