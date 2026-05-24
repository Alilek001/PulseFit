<template>
  <div class="p-4 md:p-12 max-w-[1280px] mx-auto space-y-8">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
      <div>
        <span class="text-xs text-primary-container tracking-widest font-mono uppercase">Bienvenido de vuelta</span>
        <h2 class="text-3xl font-black text-white mt-1 tracking-tight">{{ user?.name || 'Atleta' }}</h2>
        <p class="text-xs text-on-surface-variant font-mono mt-1">{{ today }}</p>
      </div>
      <div class="flex gap-3">
        <button class="flex items-center gap-2 px-4 py-2.5 bg-surface-container border border-white/10 rounded-lg text-xs font-mono text-on-surface-variant hover:text-on-surface hover:border-white/20 transition-all">
          <span class="material-symbols-outlined text-[16px]">share</span>
          Compartir Stats
        </button>
        <button @click="showWorkoutModal = true" class="flex items-center gap-2 px-4 py-2.5 bg-primary-container text-on-primary rounded-lg text-xs font-mono font-bold neon-glow-btn transition-all active:scale-95">
          <span class="material-symbols-outlined text-[16px]">add</span>
          Registrar Entrenamiento
        </button>
      </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- Calorías hoy (real) -->
      <div class="glass-card rounded-xl p-5 border border-white/5 hover:border-primary-container/30 transition-colors">
        <div class="flex justify-between items-start mb-3">
          <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-mono">Calorías Hoy</p>
          <span class="material-symbols-outlined text-primary-container text-[18px]">local_fire_department</span>
        </div>
        <div class="flex items-baseline gap-1">
          <span class="text-3xl font-black text-white">{{ todayTotals.calories }}</span>
          <span class="text-xs text-primary-container font-mono">kcal</span>
        </div>
        <div class="mt-2 w-full h-1.5 bg-surface-container-lowest rounded-full overflow-hidden">
          <div class="h-full bg-primary-container rounded-full transition-all" :style="{ width: Math.min((todayTotals.calories / goals.calories) * 100, 100) + '%' }"></div>
        </div>
        <p class="text-[10px] text-on-surface-variant font-mono mt-1">/ {{ goals.calories }} objetivo</p>
      </div>

      <!-- Entrenamientos esta semana (real) -->
      <div class="glass-card rounded-xl p-5 border border-white/5 hover:border-primary-container/30 transition-colors">
        <div class="flex justify-between items-start mb-3">
          <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-mono">Esta Semana</p>
          <span class="material-symbols-outlined text-secondary-fixed text-[18px]">fitness_center</span>
        </div>
        <div class="flex items-baseline gap-1">
          <span class="text-3xl font-black text-white">{{ workoutStats.thisWeek }}</span>
          <span class="text-xs text-on-surface-variant font-mono">/ 5 días</span>
        </div>
        <div class="mt-2 w-full h-1.5 bg-surface-container-lowest rounded-full overflow-hidden">
          <div class="h-full bg-secondary-fixed rounded-full" :style="{ width: Math.min((workoutStats.thisWeek / 5) * 100, 100) + '%' }"></div>
        </div>
        <p class="text-[10px] text-on-surface-variant font-mono mt-1">{{ workoutStats.minutesThisWeek }} min totales</p>
      </div>

      <!-- Último peso (real) -->
      <div class="glass-card rounded-xl p-5 border border-white/5 hover:border-primary-container/30 transition-colors">
        <div class="flex justify-between items-start mb-3">
          <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-mono">Último Peso</p>
          <span class="material-symbols-outlined text-error text-[18px]">monitor_weight</span>
        </div>
        <div class="flex items-baseline gap-1">
          <span class="text-3xl font-black text-white">{{ lastWeight || '—' }}</span>
          <span v-if="lastWeight" class="text-xs text-on-surface-variant font-mono">kg</span>
        </div>
        <p class="text-[10px] text-on-surface-variant font-mono mt-2">{{ lastWeightDate || 'Sin registros aún' }}</p>
      </div>

      <!-- Plan semanal (real) -->
      <div class="glass-card rounded-xl p-5 border border-primary-container/20 hover:border-primary-container/40 transition-colors bg-neon-glow">
        <div class="flex justify-between items-start mb-3">
          <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-mono">Plan Semanal</p>
          <span class="material-symbols-outlined text-primary-container text-[18px]">calendar_today</span>
        </div>
        <div class="flex items-baseline gap-1">
          <span class="text-3xl font-black text-primary-container">{{ weeklyPlanCount }}/7</span>
        </div>
        <p class="text-[10px] text-primary-container font-mono mt-2 uppercase tracking-wider">Días planificados</p>
      </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Left col -->
      <div class="lg:col-span-2 space-y-6">

        <!-- Weight Progress Chart -->
        <div class="glass-card rounded-xl p-6 border border-white/5">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider font-mono">Progreso de Peso</h3>
            <span class="text-[10px] text-on-surface-variant font-mono">HISTORIAL</span>
          </div>
          <div v-if="progressHistory.length >= 2">
            <svg :viewBox="`0 0 300 70`" class="w-full" preserveAspectRatio="none" style="height:70px">
              <polyline :points="chartPoints" fill="none" stroke="rgba(57,255,20,0.8)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <circle v-for="(pt, i) in chartDots" :key="i" :cx="pt.x" :cy="pt.y" r="3" fill="rgb(57,255,20)"/>
            </svg>
            <div class="flex justify-between text-[10px] font-mono text-on-surface-variant mt-2">
              <span>{{ progressHistory[0].date }}</span>
              <span class="text-primary-container font-bold">Actual: {{ lastWeight }} kg</span>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <p class="text-xs text-on-surface-variant font-mono">Registra tu peso para ver el gráfico</p>
          </div>
        </div>

        <!-- Today's Exercises -->
        <div class="glass-card rounded-xl p-8 border border-white/5">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
              <span class="material-symbols-outlined text-primary-container">task_alt</span>
              Ejercicios de Hoy
            </h3>
            <div class="flex items-center gap-3">
              <span v-if="exercises.length" class="text-xs text-on-surface-variant font-mono">{{ completedExercises.size }}/{{ exercises.length }} completados</span>
              <router-link to="/routines" class="text-xs font-mono text-primary-container border border-primary-container/30 px-3 py-1.5 rounded-lg bg-neon-glow hover:bg-primary-container hover:text-on-primary transition-colors uppercase tracking-wider">
                Ver todo
              </router-link>
            </div>
          </div>

          <div v-if="loading" class="flex justify-center py-12">
            <div class="w-8 h-8 border-2 border-surface-container-high border-t-primary-container rounded-full animate-spin"></div>
          </div>

          <div v-else-if="activeRoutine && exercises.length" class="space-y-3">
            <div
              v-for="(exercise, idx) in exercises"
              :key="exercise.id"
              class="p-4 rounded-xl border flex items-center gap-4 transition-colors"
              :class="completedExercises.has(exercise.id)
                ? 'bg-primary-container/5 border-primary-container/20'
                : 'bg-surface-container border-white/5 hover:border-white/10'"
            >
              <div class="w-10 h-10 rounded-lg border flex items-center justify-center text-xs font-mono font-bold shrink-0 transition-all"
                :class="completedExercises.has(exercise.id)
                  ? 'bg-primary-container border-primary-container text-on-primary'
                  : 'bg-surface-container-high border-white/5 text-primary-container'"
              >
                <span v-if="completedExercises.has(exercise.id)" class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1">check</span>
                <span v-else>{{ String(idx + 1).padStart(2, '0') }}</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-bold text-sm transition-colors" :class="completedExercises.has(exercise.id) ? 'text-on-surface-variant line-through' : 'text-white'">{{ exercise.name }}</p>
                <p class="text-xs text-on-surface-variant mt-0.5 flex items-center gap-2 font-mono">
                  <span>{{ exercise.pivot.sets }} × {{ exercise.pivot.reps }} reps</span>
                  <span class="text-primary-container/50">•</span>
                  <span class="text-on-surface-variant/70">~{{ exercise.pivot.sets * 3 }}min</span>
                </p>
              </div>
              <button
                @click="toggleExercise(exercise.id)"
                class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all border shrink-0"
                :class="completedExercises.has(exercise.id)
                  ? 'bg-primary-container/10 text-primary-container border-primary-container/30 hover:bg-error/10 hover:text-error hover:border-error/30'
                  : 'bg-surface-container-high text-on-surface-variant border-white/5 hover:bg-primary-container hover:text-on-primary hover:border-primary-container'"
              >
                {{ completedExercises.has(exercise.id) ? 'Hecho ✓' : 'Completar' }}
              </button>
            </div>
            <div class="w-full h-1.5 bg-surface-container-lowest rounded-full overflow-hidden mt-2">
              <div class="h-full bg-primary-container rounded-full shadow-[0_0_8px_rgba(57,255,20,0.4)] transition-all duration-500"
                :style="{ width: exercises.length ? (completedExercises.size / exercises.length * 100) + '%' : '0%' }"></div>
            </div>
          </div>

          <div v-else class="text-center py-16 border-2 border-dashed border-surface-container-high rounded-xl">
            <span class="material-symbols-outlined text-5xl text-surface-container-highest mb-4 block">fitness_center</span>
            <p class="font-bold text-white mb-2">Sin rutina para hoy</p>
            <p class="text-sm text-on-surface-variant mb-6">Asigna una rutina a hoy en tu plan semanal.</p>
            <router-link to="/routines" class="inline-block bg-primary-container text-on-primary text-sm font-bold py-3 px-8 rounded-lg neon-glow-btn transition-all">
              Ir a Rutinas
            </router-link>
          </div>
        </div>

        <!-- Workout History -->
        <div class="glass-card rounded-xl p-6 border border-white/5">
          <h3 class="text-sm font-bold text-white uppercase tracking-wider font-mono mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">history</span>
            Historial de Entrenamientos
          </h3>
          <div v-if="workoutLogs.length === 0" class="text-center py-8 text-xs text-on-surface-variant font-mono">
            Sin entrenamientos registrados aún.
          </div>
          <div v-else class="space-y-2">
            <div v-for="log in workoutLogs.slice(0, 5)" :key="log.id" class="flex items-center gap-4 p-3 rounded-lg bg-surface-container border border-white/5">
              <div class="w-9 h-9 rounded-lg bg-surface-container-high flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-primary-container text-[18px]">fitness_center</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ log.routine_name }}</p>
                <p class="text-xs text-on-surface-variant font-mono">{{ log.logged_at }} · {{ log.duration_minutes }} min</p>
              </div>
              <span class="text-xs font-mono text-primary-container">{{ log.duration_minutes }}m</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar Right -->
      <div class="space-y-6">

        <!-- Nutrition Goals -->
        <div class="glass-card rounded-xl p-6 border border-white/5">
          <h3 class="text-sm font-bold text-white mb-6 uppercase tracking-wider flex items-center gap-2 font-mono">
            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">local_fire_department</span>
            Objetivos Nutricionales
          </h3>
          <div class="space-y-5">
            <div>
              <div class="flex justify-between text-xs font-mono mb-2">
                <span class="text-on-surface-variant uppercase">Calorías</span>
                <span class="text-white">{{ todayTotals.calories }} <span class="text-on-surface-variant">/ {{ goals.calories }} kcal</span></span>
              </div>
              <div class="w-full bg-surface-container-lowest h-2 rounded-full overflow-hidden">
                <div class="bg-white h-full rounded-full transition-all" :style="{ width: Math.min((todayTotals.calories / goals.calories) * 100, 100) + '%' }"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-xs font-mono mb-2">
                <span class="text-on-surface-variant uppercase">Proteína</span>
                <span class="text-white">{{ todayTotals.proteins }}g <span class="text-on-surface-variant">/ {{ goals.proteins }}g</span></span>
              </div>
              <div class="w-full bg-surface-container-lowest h-2 rounded-full overflow-hidden">
                <div class="bg-primary-container h-full rounded-full shadow-[0_0_8px_rgba(57,255,20,0.4)] transition-all" :style="{ width: Math.min((todayTotals.proteins / goals.proteins) * 100, 100) + '%' }"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-xs font-mono mb-2">
                <span class="text-on-surface-variant uppercase">Carbohidratos</span>
                <span class="text-white">{{ todayTotals.carbs }}g <span class="text-on-surface-variant">/ {{ goals.carbs }}g</span></span>
              </div>
              <div class="w-full bg-surface-container-lowest h-2 rounded-full overflow-hidden">
                <div class="bg-secondary-fixed h-full rounded-full transition-all" :style="{ width: Math.min((todayTotals.carbs / goals.carbs) * 100, 100) + '%' }"></div>
              </div>
            </div>
          </div>
          <router-link to="/nutrition" class="block text-center mt-6 text-xs font-mono text-on-surface-variant hover:text-primary-container transition-colors uppercase tracking-wider">
            Ajustar Macros →
          </router-link>
        </div>

        <!-- Log Progress -->
        <div class="glass-card rounded-xl p-6 border border-white/5">
          <h3 class="text-sm font-bold text-white mb-4 uppercase tracking-wider font-mono flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-primary-container">trending_up</span>
            Registrar Peso
          </h3>
          <form @submit.prevent="logProgress" class="space-y-4">
            <div>
              <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Peso (kg)</label>
              <input
                v-model="progressForm.weight"
                type="number"
                step="0.1"
                placeholder="75.0"
                class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40"
              />
            </div>
            <button
              type="submit"
              :disabled="loggingProgress"
              class="w-full h-11 bg-primary-container text-on-primary font-bold rounded-lg text-sm neon-glow-btn transition-all active:scale-95 disabled:opacity-60"
            >
              {{ loggingProgress ? 'Guardando...' : 'Guardar Peso' }}
            </button>
          </form>
        </div>

        <!-- Premium Banner -->
        <div v-if="user?.role === 'user'" class="glass-card rounded-xl p-6 border border-primary-container/20 relative overflow-hidden">
          <div class="absolute top-0 right-0 w-32 h-32 bg-primary-container/10 rounded-full blur-[50px] pointer-events-none"></div>
          <h3 class="font-black text-white mb-2 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-primary-container">stars</span>
            Desbloquear PulseFit Élite
          </h3>
          <p class="text-sm text-on-surface-variant mb-4 leading-relaxed">Rutinas avanzadas y analíticas de biomecánica.</p>
          <div v-if="upgradeStatus === 'pending'" class="w-full py-3 rounded-lg text-xs font-mono text-center text-on-surface-variant border border-white/10 bg-surface-container">
            Solicitud pendiente de aprobación...
          </div>
          <div v-else-if="upgradeStatus === 'approved'" class="w-full py-3 rounded-lg text-xs font-mono text-center text-primary-container border border-primary-container/30">
            ¡Aprobado! Recarga la página.
          </div>
          <button v-else @click="requestUpgrade" :disabled="requestingUpgrade"
            class="w-full border-2 border-primary-container text-primary-container font-black py-3 rounded-lg hover:bg-primary-container hover:text-on-primary transition-colors text-xs font-mono uppercase tracking-wider disabled:opacity-60">
            {{ requestingUpgrade ? 'Enviando...' : 'Solicitar Acceso Élite' }}
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Workout Log Modal -->
  <Teleport to="body">
    <div v-if="showWorkoutModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="showWorkoutModal = false">
      <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
      <div class="relative glass-card rounded-xl w-full max-w-md border border-white/10 shadow-2xl">
        <div class="p-6 border-b border-white/5 flex items-center justify-between">
          <h3 class="font-bold text-white">Registrar Entrenamiento</h3>
          <button @click="showWorkoutModal = false" class="text-on-surface-variant hover:text-white">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        <div class="p-6 space-y-4">
          <div>
            <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Nombre del Entrenamiento</label>
            <input v-model="workoutForm.routine_name" type="text" placeholder="Ej: Pecho y Tríceps"
              class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
          </div>
          <div>
            <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Duración (minutos)</label>
            <input v-model.number="workoutForm.duration_minutes" type="number" min="1" placeholder="45"
              class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
          </div>
          <div>
            <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Notas (opcional)</label>
            <textarea v-model="workoutForm.notes" rows="2" placeholder="Cómo fue el entrenamiento..."
              class="w-full px-4 py-3 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40 resize-none"></textarea>
          </div>
          <button @click="submitWorkout" :disabled="loggingWorkout"
            class="w-full h-11 bg-primary-container text-on-primary font-bold rounded-lg text-sm neon-glow-btn transition-all active:scale-95 disabled:opacity-60">
            {{ loggingWorkout ? 'Guardando...' : 'Guardar Entrenamiento' }}
          </button>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const user = ref(null)
const activeRoutine = ref(null)
const exercises = ref([])
const loading = ref(true)
const loggingProgress = ref(false)
const progressForm = ref({ weight: '' })
const completedExercises = ref(new Set())
const goals = ref({ calories: 2200, proteins: 150, carbs: 350, fats: 85 })
const todayTotals = ref({ calories: 0, proteins: 0, carbs: 0, fats: 0 })
const progressHistory = ref([])
const workoutStats = ref({ thisWeek: 0, minutesThisWeek: 0 })
const weeklyPlanCount = ref(0)
const workoutLogs = ref([])
const upgradeStatus = ref(null)
const requestingUpgrade = ref(false)
const showWorkoutModal = ref(false)
const loggingWorkout = ref(false)
const workoutForm = ref({ routine_name: '', duration_minutes: 45, notes: '' })

const today = computed(() => new Date().toLocaleDateString('es-ES', { weekday: 'long', month: 'long', day: 'numeric' }))

const lastWeight = computed(() => progressHistory.value.length ? progressHistory.value[progressHistory.value.length - 1].weight : null)
const lastWeightDate = computed(() => progressHistory.value.length ? progressHistory.value[progressHistory.value.length - 1].date : null)

const chartDots = computed(() => {
  if (progressHistory.value.length < 2) return []
  const weights = progressHistory.value.map(p => parseFloat(p.weight))
  const min = Math.min(...weights) - 1
  const range = Math.max(...weights) + 1 - min
  return progressHistory.value.map((p, i) => ({
    x: (i / (progressHistory.value.length - 1)) * 300,
    y: 70 - ((parseFloat(p.weight) - min) / range) * 70,
  }))
})

const chartPoints = computed(() => chartDots.value.map(d => `${d.x},${d.y}`).join(' '))

const toggleExercise = (id) => {
  const s = new Set(completedExercises.value)
  s.has(id) ? s.delete(id) : s.add(id)
  completedExercises.value = s
}

onMounted(async () => {
  const token = localStorage.getItem('auth_token')
  const headers = { Authorization: `Bearer ${token}` }

  const [dashRes, foodRes, upgradeRes, logsRes] = await Promise.allSettled([
    axios.get('/api/dashboard', { headers }),
    axios.get('/api/food-log', { headers }),
    axios.get('/api/upgrade/status', { headers }),
    axios.get('/api/workout-logs', { headers }),
  ])

  if (dashRes.status === 'fulfilled') {
    const d = dashRes.value.data
    user.value = d.user
    goals.value = d.goals
    activeRoutine.value = d.routine
    exercises.value = d.routine?.exercises || []
    progressHistory.value = d.progress || []
    workoutStats.value = d.workoutStats || { thisWeek: 0, minutesThisWeek: 0 }
    weeklyPlanCount.value = d.weeklyPlanCount || 0
  }
  if (foodRes.status === 'fulfilled') todayTotals.value = foodRes.value.data.totals
  if (upgradeRes.status === 'fulfilled' && upgradeRes.value.data) upgradeStatus.value = upgradeRes.value.data.status
  if (logsRes.status === 'fulfilled') workoutLogs.value = logsRes.value.data

  loading.value = false
})

const logProgress = async () => {
  if (!progressForm.value.weight) return
  loggingProgress.value = true
  try {
    const token = localStorage.getItem('auth_token')
    const res = await axios.post('/api/progress', { weight: progressForm.value.weight, date: new Date().toISOString().split('T')[0] }, {
      headers: { Authorization: `Bearer ${token}` }
    })
    progressHistory.value.push(res.value?.data || { weight: progressForm.value.weight, date: new Date().toISOString().split('T')[0] })
    progressForm.value.weight = ''
  } catch (e) {
    console.error('Progress error', e)
  } finally {
    loggingProgress.value = false
  }
}

const submitWorkout = async () => {
  if (!workoutForm.value.routine_name || !workoutForm.value.duration_minutes) return
  loggingWorkout.value = true
  try {
    const token = localStorage.getItem('auth_token')
    const res = await axios.post('/api/workout-logs', {
      ...workoutForm.value,
      logged_at: new Date().toISOString().split('T')[0],
    }, { headers: { Authorization: `Bearer ${token}` } })
    workoutLogs.value.unshift(res.data)
    workoutStats.value.thisWeek++
    workoutStats.value.minutesThisWeek += workoutForm.value.duration_minutes
    workoutForm.value = { routine_name: '', duration_minutes: 45, notes: '' }
    showWorkoutModal.value = false
  } catch (e) {
    console.error('Workout log error', e)
  } finally {
    loggingWorkout.value = false
  }
}

const requestUpgrade = async () => {
  requestingUpgrade.value = true
  try {
    const token = localStorage.getItem('auth_token')
    await axios.post('/api/upgrade/request', {}, { headers: { Authorization: `Bearer ${token}` } })
    upgradeStatus.value = 'pending'
  } catch (e) {
    if (e.response?.data?.message) alert(e.response.data.message)
  } finally {
    requestingUpgrade.value = false
  }
}
</script>
