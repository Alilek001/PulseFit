<template>
  <div class="p-4 md:p-12 max-w-[900px] mx-auto space-y-8">

    <!-- Header -->
    <div>
      <span class="text-xs text-primary-container tracking-widest font-mono uppercase">Cuenta</span>
      <h2 class="text-3xl font-black text-white mt-1 tracking-tight">Mi Perfil</h2>
    </div>

    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-2 border-surface-container-high border-t-primary-container rounded-full animate-spin"></div>
    </div>

    <template v-else>
      <!-- Avatar + Identity Card -->
      <div class="glass-card rounded-xl p-8 border border-white/5">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
          <div class="relative shrink-0">
            <div class="w-24 h-24 rounded-full bg-surface-container-high border-2 border-primary-container flex items-center justify-center text-3xl font-black text-primary-container shadow-[0_0_20px_rgba(57,255,20,0.2)]">
              {{ user?.name ? user.name.charAt(0).toUpperCase() : 'U' }}
            </div>
            <div class="absolute bottom-0 right-0 w-6 h-6 bg-primary-container rounded-full flex items-center justify-center">
              <span class="material-symbols-outlined text-[14px] text-on-primary" style="font-variation-settings:'FILL' 1;">verified</span>
            </div>
          </div>
          <div class="flex-1">
            <h3 class="text-2xl font-bold text-white">{{ user?.name || 'User Name' }}</h3>
            <p class="text-on-surface-variant text-sm mt-0.5">{{ user?.email }}</p>
            <div class="flex items-center gap-3 mt-3">
              <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-bold"
                :class="user?.role === 'admin' ? 'bg-tertiary-fixed/10 border border-tertiary-fixed/30 text-tertiary-fixed' :
                        user?.role === 'premium' ? 'bg-primary-container/10 border border-primary-container/30 text-primary-container' :
                        'bg-surface-container-high border border-white/5 text-on-surface-variant'"
              >
                <span class="material-symbols-outlined text-[12px]" style="font-variation-settings:'FILL' 1;">{{ user?.role === 'admin' ? 'shield' : user?.role === 'premium' ? 'stars' : 'person' }}</span>
                {{ user?.role === 'admin' ? 'Administrador' : user?.role === 'premium' ? 'Miembro Élite' : 'Miembro Estándar' }}
              </div>
              <span class="text-xs text-on-surface-variant font-mono">Registrado Oct 2023</span>
            </div>
          </div>
          <div class="text-right shrink-0">
            <p class="text-[10px] text-on-surface-variant font-mono uppercase tracking-wider">Puntuación Física</p>
            <p class="text-4xl font-black text-primary-container font-mono">75<span class="text-lg">%</span></p>
            <p class="text-[10px] text-primary-container font-mono uppercase mt-1">Top 5% del grupo</p>
          </div>
        </div>
      </div>

      <!-- Biometric Stats -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card rounded-xl p-5 border border-white/5 text-center">
          <p class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest mb-2">Peso</p>
          <p class="text-2xl font-black text-white font-mono">{{ user?.weight || '--' }}</p>
          <p class="text-xs text-on-surface-variant font-mono mt-0.5">kg</p>
        </div>
        <div class="glass-card rounded-xl p-5 border border-white/5 text-center">
          <p class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest mb-2">Altura</p>
          <p class="text-2xl font-black text-white font-mono">{{ user?.height || '--' }}</p>
          <p class="text-xs text-on-surface-variant font-mono mt-0.5">cm</p>
        </div>
        <div class="glass-card rounded-xl p-5 border border-white/5 text-center">
          <p class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest mb-2">IMC</p>
          <p class="text-2xl font-black font-mono" :class="bmiColor">{{ bmi }}</p>
          <p class="text-xs font-mono mt-0.5" :class="bmiColor">{{ bmiLabel }}</p>
        </div>
        <div class="glass-card rounded-xl p-5 border border-white/5 text-center">
          <p class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest mb-2">Objetivo</p>
          <p class="text-sm font-bold text-primary-container font-mono leading-tight mt-1">{{ goalLabel }}</p>
        </div>
      </div>

      <!-- Goals Progress -->
      <div class="glass-card rounded-xl p-8 border border-white/5">
        <h3 class="text-sm font-bold text-white mb-6 uppercase tracking-wider font-mono flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px] text-primary-container">emoji_events</span>
          Objetivos de Rendimiento
        </h3>
        <div class="space-y-6">
          <div v-for="goal in goals" :key="goal.label">
            <div class="flex justify-between items-start mb-2">
              <div>
                <p class="text-sm font-semibold text-white">{{ goal.label }}</p>
                <p class="text-xs text-on-surface-variant font-mono mt-0.5">{{ goal.detail }}</p>
              </div>
              <span class="text-sm font-bold font-mono" :class="goal.color">{{ goal.percent }}%</span>
            </div>
            <div class="w-full h-2 bg-surface-container-lowest rounded-full overflow-hidden">
              <div class="h-full rounded-full transition-all duration-1000" :class="goal.barColor" :style="{ width: goal.percent + '%' }"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit Form -->
      <div class="glass-card rounded-xl border border-white/5 overflow-hidden">
        <div class="p-6 border-b border-white/5">
          <h3 class="font-bold text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">edit</span>
            Estadísticas Físicas
          </h3>
        </div>
        <div class="p-8">
          <form @submit.prevent="saveProfile" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="block text-xs text-on-surface-variant uppercase tracking-widest font-mono">Peso (kg)</label>
                <input
                  v-model="form.weight"
                  type="number"
                  step="0.1"
                  placeholder="75.5"
                  class="w-full h-12 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white input-focus-ring placeholder-on-surface-variant/40 text-sm"
                />
              </div>
              <div class="space-y-2">
                <label class="block text-xs text-on-surface-variant uppercase tracking-widest font-mono">Altura (cm)</label>
                <input
                  v-model="form.height"
                  type="number"
                  placeholder="180"
                  class="w-full h-12 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white input-focus-ring placeholder-on-surface-variant/40 text-sm"
                />
              </div>
            </div>

            <div class="space-y-2">
              <label class="block text-xs text-on-surface-variant uppercase tracking-widest font-mono">Objetivo</label>
              <select
                v-model="form.goal"
                class="w-full h-12 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring"
              >
                <option value="lose_weight">Perder Peso</option>
                <option value="gain_muscle">Ganar Músculo</option>
                <option value="maintain">Mantener Forma</option>
              </select>
            </div>

            <div v-if="saveSuccess" class="text-sm text-primary-container font-mono flex items-center gap-2">
              <span class="material-symbols-outlined text-[18px]">check_circle</span>
              Perfil guardado correctamente.
            </div>

            <div class="flex justify-end pt-2">
              <button
                type="submit"
                :disabled="saving"
                class="px-8 py-3 bg-primary-container text-on-primary font-bold rounded-lg text-sm neon-glow-btn transition-all active:scale-95 disabled:opacity-60 flex items-center gap-2"
              >
                <span v-if="saving" class="w-4 h-4 border-2 border-on-primary border-t-transparent rounded-full animate-spin"></span>
                {{ saving ? 'Guardando...' : 'Guardar Cambios' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const user = ref(null)
const loading = ref(true)
const saving = ref(false)
const saveSuccess = ref(false)
const form = ref({ weight: '', height: '', goal: 'gain_muscle' })

const bmi = computed(() => {
  if (!user.value?.weight || !user.value?.height) return '--'
  const h = user.value.height / 100
  return (user.value.weight / (h * h)).toFixed(1)
})

const bmiLabel = computed(() => {
  const b = parseFloat(bmi.value)
  if (isNaN(b)) return '--'
  if (b < 18.5) return 'Bajo Peso'
  if (b < 25) return 'Normal'
  if (b < 30) return 'Sobrepeso'
  return 'Obesidad'
})

const bmiColor = computed(() => {
  const b = parseFloat(bmi.value)
  if (isNaN(b)) return 'text-on-surface-variant'
  if (b < 18.5) return 'text-secondary-fixed'
  if (b < 25) return 'text-primary-container'
  if (b < 30) return 'text-error'
  return 'text-error'
})

const goalLabel = computed(() => {
  const map = { lose_weight: 'Perder Peso', gain_muscle: 'Ganar Músculo', maintain: 'Mantener Forma' }
  return map[user.value?.goal] || '--'
})

const goals = computed(() => [
  {
    label: 'Ganar 5kg Masa Muscular',
    detail: 'Actualmente +3.2kg. Completado en ~4 semanas.',
    percent: 65,
    color: 'text-primary-container',
    barColor: 'bg-primary-container shadow-[0_0_8px_rgba(57,255,20,0.3)]',
  },
  {
    label: 'Correr 10km en -45min',
    detail: 'Mejor marca personal. Enfoque en intervalos de tempo.',
    percent: 90,
    color: 'text-secondary-fixed',
    barColor: 'bg-secondary-fixed',
  },
  {
    label: 'Grasa Corporal al 12%',
    detail: 'Actualmente 14.5%. Plan nutricional ajustado.',
    percent: 40,
    color: 'text-error',
    barColor: 'bg-error',
  },
])

onMounted(async () => {
  try {
    const token = localStorage.getItem('auth_token')
    const res = await axios.get('/api/user', { headers: { Authorization: `Bearer ${token}` } })
    user.value = res.data
    form.value.weight = res.data.weight || ''
    form.value.height = res.data.height || ''
    form.value.goal = res.data.goal || 'gain_muscle'
  } catch (e) {
    console.error('Profile error', e)
  } finally {
    loading.value = false
  }
})

const saveProfile = async () => {
  saving.value = true
  saveSuccess.value = false
  try {
    const token = localStorage.getItem('auth_token')
    await axios.post('/api/profile', form.value, { headers: { Authorization: `Bearer ${token}` } })
    user.value = { ...user.value, ...form.value }
    saveSuccess.value = true
    setTimeout(() => { saveSuccess.value = false }, 3000)
  } catch (e) {
    console.error('Save profile error', e)
  } finally {
    saving.value = false
  }
}
</script>
