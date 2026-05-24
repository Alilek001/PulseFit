<template>
  <div class="flex flex-col md:flex-row gap-6 p-4 md:p-12">

    <!-- Left: Routines -->
    <section class="flex-1 space-y-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h2 class="text-3xl font-black text-on-surface tracking-tight">Rutinas de Entrenamiento</h2>
          <p class="text-xs text-on-surface-variant font-mono mt-1">
            {{ isPremium ? 'Acceso Élite — Rutinas avanzadas desbloqueadas' : 'Plan Básico — Rutinas de introducción' }}
          </p>
        </div>
      </div>

      <!-- Search -->
      <div class="relative">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant text-[18px]">search</span>
        <input v-model="searchQuery" type="text" placeholder="Buscar rutina..."
          class="w-full h-11 pl-11 pr-4 bg-surface-container border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
      </div>

      <!-- Filter chips -->
      <div class="flex gap-3 overflow-x-auto pb-2" style="-ms-overflow-style:none;scrollbar-width:none;">
        <button v-for="f in filters" :key="f" @click="activeFilter = f"
          class="px-5 py-2 rounded-full text-xs font-mono whitespace-nowrap transition-colors"
          :class="activeFilter === f ? 'bg-primary-container text-on-primary' : 'bg-surface-container text-on-surface-variant border border-white/5 hover:border-primary-container/50'">
          {{ f }}
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center py-12">
        <div class="w-8 h-8 border-2 border-surface-container-high border-t-primary-container rounded-full animate-spin"></div>
      </div>

      <!-- Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <div v-if="filteredRoutines.length === 0" class="col-span-full text-center py-16 border-2 border-dashed border-surface-container-high rounded-xl">
          <span class="material-symbols-outlined text-5xl text-surface-container-highest mb-4 block">fitness_center</span>
          <p class="font-bold text-white mb-2">Sin rutinas{{ activeFilter !== 'Todo' ? ' de ' + activeFilter : '' }}</p>
          <p class="text-sm text-on-surface-variant">{{ isPremium ? 'Genera una con IA o espera nuevas del administrador.' : 'El administrador añadirá más pronto.' }}</p>
        </div>

        <div v-for="routine in filteredRoutines" :key="routine.id"
          class="glass-card rounded-xl overflow-hidden group transition-all duration-300 flex flex-col"
          :class="routine.tier === 'elite' ? 'hover:border-primary-container/30' : 'hover:border-white/10'">

          <!-- Image -->
          <div class="h-40 relative overflow-hidden bg-surface-container">
            <img :src="getRoutineImage(routine)" :alt="routine.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute top-3 right-3 text-xs font-mono px-3 py-1 rounded-full font-bold"
              :class="routine.tier === 'elite' ? 'bg-primary-container text-on-primary' : 'bg-surface-glass backdrop-blur-md text-on-surface'">
              {{ routine.tier === 'elite' ? 'ÉLITE' : 'BÁSICO' }}
            </div>
          </div>

          <!-- Basic card (normal users): minimal info -->
          <div v-if="!isPremium" class="p-5 flex-1 flex flex-col">
            <h3 class="text-sm font-bold text-white mb-1">{{ routine.name }}</h3>
            <p class="text-xs text-on-surface-variant line-clamp-2 flex-1">{{ routine.description }}</p>
            <div class="flex items-center gap-3 mt-3 text-xs text-on-surface-variant font-mono">
              <span>{{ routine.exercises?.length || 0 }} ejercicios</span>
              <span>•</span>
              <span>{{ routine.training_days }}d/sem</span>
            </div>
            <button @click="openPlanModal(routine)" class="mt-4 w-full border border-white/10 py-2 rounded-lg text-xs font-mono hover:bg-surface-container-high transition-all">
              Añadir a Mi Plan
            </button>
          </div>

          <!-- Elite card (premium): full details -->
          <div v-else class="p-6 flex-1 flex flex-col">
            <div class="flex items-center gap-2 mb-3">
              <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-full border"
                :class="routine.tier === 'elite' ? 'text-primary-container border-primary-container/50 bg-primary-container/10' : 'text-secondary-fixed border-secondary-fixed/50 bg-secondary-fixed/10'">
                {{ routine.tier === 'elite' ? 'ÉLITE' : 'BÁSICO' }}
              </span>
              <span class="text-[10px] text-on-surface-variant font-mono">{{ (routine.exercises?.length || 1) * 8 }}min est.</span>
            </div>
            <h3 class="text-base font-bold text-white mb-1 group-hover:text-primary-container transition-colors leading-tight">{{ routine.name }}</h3>
            <p class="text-xs text-on-surface-variant mb-3 line-clamp-2 flex-1">{{ routine.description }}</p>
            <div class="flex items-center gap-3 text-xs text-on-surface-variant font-mono mb-4">
              <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">fitness_center</span>{{ routine.exercises?.length || 0 }} ejercicios</span>
              <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">calendar_today</span>{{ routine.training_days }}d/sem</span>
            </div>
            <div class="flex gap-2 mt-auto">
              <button @click="openDetail(routine)" class="flex-1 border border-white/10 py-2 rounded-lg text-xs font-mono hover:bg-surface-container-high transition-all">
                Ver Detalles
              </button>
              <button @click="openPlanModal(routine)" class="flex-1 bg-primary-container/10 border border-primary-container/30 text-primary-container py-2 rounded-lg text-xs font-mono hover:bg-primary-container hover:text-on-primary transition-all">
                + Mi Plan
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Right: Weekly Plan -->
    <aside class="w-full md:w-80 lg:w-96 shrink-0">
      <div class="glass-card rounded-xl p-6 sticky top-24">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-on-surface">Mi Plan Semanal</h3>
          <span class="material-symbols-outlined text-primary-container">calendar_today</span>
        </div>

        <div class="space-y-2">
          <div v-for="day in weekDays" :key="day.index"
            class="flex items-center gap-3 p-3 rounded-xl border-l-4 transition-colors"
            :class="day.isToday ? 'bg-surface-container-high border-primary-container' : 'bg-surface-variant/20 border-transparent'">
            <div class="text-center w-10 shrink-0">
              <span class="block text-[10px] text-on-surface-variant font-mono uppercase">{{ day.label }}</span>
              <span class="block text-lg font-bold" :class="day.isToday ? 'text-primary-container' : 'text-on-surface'">{{ day.num }}</span>
            </div>
            <div class="flex-1 min-w-0">
              <template v-if="weeklyPlan[day.index]">
                <p class="font-semibold text-sm text-white truncate">{{ weeklyPlan[day.index].routine?.name }}</p>
                <p class="text-[10px] text-on-surface-variant font-mono">{{ weeklyPlan[day.index].routine?.exercises?.length || 0 }} ejercicios</p>
              </template>
              <template v-else>
                <p class="text-sm text-on-surface-variant">Descanso</p>
                <p class="text-[10px] text-on-surface-variant/50 font-mono">Sin rutina asignada</p>
              </template>
            </div>
            <button v-if="weeklyPlan[day.index]" @click="removePlanDay(day.index)"
              class="text-on-surface-variant hover:text-error transition-colors shrink-0">
              <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
            <span v-else class="material-symbols-outlined text-on-surface-variant/30 text-[22px] shrink-0">hotel</span>
          </div>
        </div>

        <div class="mt-6 pt-4 border-t border-white/5">
          <div class="flex justify-between items-center mb-2">
            <span class="text-xs text-on-surface-variant font-mono uppercase">Progreso Semanal</span>
            <span class="text-sm font-bold font-mono text-primary-container">{{ weekProgress }}%</span>
          </div>
          <div class="w-full h-2 bg-surface-container-high rounded-full overflow-hidden">
            <div class="h-full bg-primary-container shadow-[0_0_10px_rgba(57,255,20,0.4)] transition-all" :style="{ width: weekProgress + '%' }"></div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Add to Plan Modal -->
    <Teleport to="body">
      <div v-if="planModalRoutine" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="planModalRoutine = null">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div class="relative glass-card rounded-xl w-full max-w-sm border border-white/10 shadow-2xl">
          <div class="p-5 border-b border-white/5 flex items-center justify-between">
            <div>
              <h3 class="font-bold text-white text-sm">Añadir a Mi Plan</h3>
              <p class="text-xs text-on-surface-variant mt-0.5">{{ planModalRoutine.name }}</p>
            </div>
            <button @click="planModalRoutine = null" class="text-on-surface-variant hover:text-white">
              <span class="material-symbols-outlined">close</span>
            </button>
          </div>
          <div class="p-5 grid grid-cols-4 gap-2">
            <button v-for="day in weekDays" :key="day.index" @click="assignDay(day.index)"
              class="flex flex-col items-center p-3 rounded-lg border transition-all"
              :class="weeklyPlan[day.index]
                ? 'border-primary-container/40 bg-primary-container/10 text-primary-container'
                : 'border-white/10 bg-surface-container hover:border-primary-container/50 text-on-surface-variant hover:text-white'">
              <span class="text-[10px] font-mono uppercase">{{ day.label }}</span>
              <span class="text-sm font-bold mt-0.5">{{ day.num }}</span>
              <span v-if="weeklyPlan[day.index]" class="text-[8px] font-mono text-primary-container mt-0.5 text-center leading-tight truncate w-full text-center">OCUPADO</span>
            </button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Detail Modal -->
    <Teleport to="body">
      <div v-if="selectedRoutine" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="selectedRoutine = null">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div class="relative glass-card rounded-xl w-full max-w-lg max-h-[85vh] flex flex-col border border-white/10 shadow-2xl">
          <div class="p-6 border-b border-white/5 flex items-start justify-between shrink-0">
            <div>
              <h3 class="text-xl font-bold text-white">{{ selectedRoutine.name }}</h3>
              <p class="text-xs text-on-surface-variant mt-1">{{ selectedRoutine.description }}</p>
            </div>
            <button @click="selectedRoutine = null" class="text-on-surface-variant hover:text-white ml-4">
              <span class="material-symbols-outlined">close</span>
            </button>
          </div>
          <div class="overflow-y-auto flex-1 p-6 space-y-3">
            <div v-for="(ex, i) in selectedRoutine.exercises" :key="ex.id"
              class="flex items-center gap-4 p-4 bg-surface-container rounded-xl border border-white/5">
              <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-xs font-mono text-primary-container font-bold shrink-0">
                {{ String(i + 1).padStart(2, '0') }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-bold text-white text-sm">{{ ex.name }}</p>
                <p class="text-xs text-on-surface-variant font-mono mt-0.5">{{ ex.muscle_group }}</p>
                <p v-if="ex.pivot.notes" class="text-[10px] text-primary-container/70 font-mono mt-0.5">{{ ex.pivot.notes }}</p>
              </div>
              <div class="text-right shrink-0">
                <p class="text-xs font-mono font-bold text-primary-container">{{ ex.pivot.sets }} × {{ ex.pivot.reps }}</p>
                <p class="text-[10px] text-on-surface-variant font-mono">series × reps</p>
              </div>
            </div>
          </div>
          <div class="p-4 border-t border-white/5 shrink-0">
            <button @click="openPlanModal(selectedRoutine); selectedRoutine = null"
              class="w-full py-3 bg-primary-container text-on-primary font-bold rounded-lg text-sm neon-glow-btn transition-all">
              Añadir a Mi Plan Semanal
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { fitnessService } from '../services/api'
import axios from 'axios'

const muscleImages = {
  Pecho: 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=600&q=80',
  Espalda: 'https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=600&q=80',
  Piernas: 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?w=600&q=80',
  Hombros: 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=600&q=80',
  Brazos: 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=600&q=80',
  Core: 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&q=80',
  Cardio: 'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?w=600&q=80',
  default: 'https://images.unsplash.com/photo-1534438327980-6b4b83e4e8e1?w=600&q=80',
}

const getRoutineImage = (routine) => {
  for (const [key, url] of Object.entries(muscleImages)) {
    if (key !== 'default' && (routine.name?.toLowerCase().includes(key.toLowerCase()) ||
      routine.exercises?.some(e => e.muscle_group?.toLowerCase().includes(key.toLowerCase())))) return url
  }
  return muscleImages.default
}

const routines = ref([])
const weeklyPlan = ref({})
const loading = ref(true)
const activeFilter = ref('Todo')
const searchQuery = ref('')
const selectedRoutine = ref(null)
const planModalRoutine = ref(null)
const isPremium = ref(false)

const filters = ['Todo', 'Pecho', 'Espalda', 'Piernas', 'Hombros', 'Core', 'Cardio']

const weekDays = computed(() => {
  const labels = ['LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB', 'DOM']
  const today = new Date()
  const dow = today.getDay()
  const monday = new Date(today)
  monday.setDate(today.getDate() - (dow === 0 ? 6 : dow - 1))
  return labels.map((label, i) => {
    const d = new Date(monday)
    d.setDate(monday.getDate() + i)
    return { label, index: i, num: String(d.getDate()).padStart(2, '0'), isToday: d.toDateString() === today.toDateString() }
  })
})

const weekProgress = computed(() => {
  const assigned = Object.keys(weeklyPlan.value).length
  return Math.round((assigned / 7) * 100)
})

const filteredRoutines = computed(() => {
  let list = routines.value
  if (activeFilter.value !== 'Todo') {
    list = list.filter(r =>
      r.name?.toLowerCase().includes(activeFilter.value.toLowerCase()) ||
      r.exercises?.some(e => e.muscle_group?.toLowerCase().includes(activeFilter.value.toLowerCase()))
    )
  }
  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase()
    list = list.filter(r => r.name?.toLowerCase().includes(q) || r.description?.toLowerCase().includes(q))
  }
  return list
})

const token = () => localStorage.getItem('auth_token')
const headers = () => ({ Authorization: `Bearer ${token()}` })

const loadWeeklyPlan = async () => {
  const res = await axios.get('/api/weekly-plan', { headers: headers() })
  weeklyPlan.value = res.data
}

onMounted(async () => {
  try {
    const [userRes, routinesRes] = await Promise.all([
      axios.get('/api/user', { headers: headers() }),
      fitnessService.getRoutines(),
    ])
    isPremium.value = ['premium', 'admin'].includes(userRes.data.role)
    routines.value = routinesRes.data
    await loadWeeklyPlan()
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})

const openDetail = (r) => { selectedRoutine.value = r }
const openPlanModal = (r) => { planModalRoutine.value = r }

const assignDay = async (dayIndex) => {
  try {
    await axios.post('/api/weekly-plan', {
      routine_id: planModalRoutine.value.id,
      day_of_week: dayIndex,
    }, { headers: headers() })
    await loadWeeklyPlan()
    planModalRoutine.value = null
  } catch (e) { console.error(e) }
}

const removePlanDay = async (dayIndex) => {
  try {
    await axios.delete(`/api/weekly-plan/${dayIndex}`, { headers: headers() })
    await loadWeeklyPlan()
  } catch (e) { console.error(e) }
}

</script>
