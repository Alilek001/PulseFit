<template>
  <div class="p-4 md:p-12 max-w-[1280px] mx-auto space-y-8">

    <div>
      <span class="text-xs text-primary-container tracking-widest font-mono uppercase">Administración</span>
      <h2 class="text-3xl font-black text-white mt-1 tracking-tight flex items-center gap-3">
        <span class="material-symbols-outlined text-on-surface-variant">admin_panel_settings</span>
        Panel de Admin
      </h2>
    </div>

    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-2 border-surface-container-high border-t-primary-container rounded-full animate-spin"></div>
    </div>

    <div v-else-if="authError" class="glass-card rounded-xl p-12 text-center border border-error/20">
      <span class="material-symbols-outlined text-5xl text-error block mb-4">lock</span>
      <p class="text-on-surface-variant">Acceso restringido a administradores.</p>
    </div>

    <div v-else class="space-y-8">

      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="glass-card rounded-xl p-6 border border-white/5">
          <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-mono">Total Usuarios</p>
          <p class="text-4xl font-black text-white mt-3 font-mono">{{ stats.total_users || 0 }}</p>
        </div>
        <div class="glass-card rounded-xl p-6 border border-white/5">
          <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-mono">Suscriptores Élite</p>
          <p class="text-4xl font-black text-primary-container mt-3 font-mono">{{ stats.premium_subs || 0 }}</p>
        </div>
        <div class="glass-card rounded-xl p-6 border border-white/5">
          <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-mono">Rutinas Generadas</p>
          <p class="text-4xl font-black text-white mt-3 font-mono">{{ stats.active_workouts || 0 }}</p>
        </div>
        <div class="glass-card rounded-xl p-6 border border-primary-container/20 bg-neon-glow cursor-pointer" @click="activeTab = 'upgrades'">
          <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-mono">Solicitudes Élite</p>
          <p class="text-4xl font-black text-primary-container mt-3 font-mono">{{ pendingUpgrades.length }}</p>
          <p v-if="pendingUpgrades.length" class="text-[10px] text-primary-container font-mono mt-1 uppercase">Pendientes</p>
        </div>
      </div>

      <!-- Tabs -->
      <div class="flex gap-2 border-b border-white/5 pb-0">
        <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
          class="px-5 py-2.5 text-xs font-mono uppercase tracking-wider transition-all border-b-2 -mb-px"
          :class="activeTab === tab.key ? 'text-primary-container border-primary-container' : 'text-on-surface-variant border-transparent hover:text-on-surface'">
          {{ tab.label }}
        </button>
      </div>

      <!-- Users Tab -->
      <div v-if="activeTab === 'users'" class="glass-card rounded-xl overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="bg-surface-container-lowest text-[10px] uppercase font-mono tracking-widest text-on-surface-variant border-b border-white/5">
                <th class="px-6 py-4">Nombre</th>
                <th class="px-6 py-4">Correo</th>
                <th class="px-6 py-4">Plan</th>
                <th class="px-6 py-4 text-right">Rol</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="u in recentUsers" :key="u.id" class="border-b border-white/5 hover:bg-surface-container/50 transition-colors">
                <td class="px-6 py-4 font-bold text-white">{{ u.name }}</td>
                <td class="px-6 py-4 text-on-surface-variant text-sm">{{ u.email }}</td>
                <td class="px-6 py-4">
                  <span class="px-2 py-1 rounded text-xs font-mono font-bold"
                    :class="['premium','admin'].includes(u.role) ? 'bg-primary-container/10 text-primary-container border border-primary-container/20' : 'bg-surface-container text-on-surface-variant border border-white/5'">
                    {{ ['premium','admin'].includes(u.role) ? 'Élite' : 'Básico' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <select v-model="u.role" @change="changeRole(u.id, u.role)"
                    class="bg-surface-container border border-white/5 rounded-lg px-3 py-1.5 text-xs text-white font-mono input-focus-ring cursor-pointer">
                    <option value="user">Usuario Básico</option>
                    <option value="premium">Élite Premium</option>
                    <option value="admin">Administrador</option>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Upgrade Requests Tab -->
      <div v-if="activeTab === 'upgrades'" class="space-y-4">
        <div v-if="upgradeRequests.length === 0" class="glass-card rounded-xl p-12 text-center border border-white/5">
          <span class="material-symbols-outlined text-4xl text-on-surface-variant/30 block mb-3">inbox</span>
          <p class="text-on-surface-variant">No hay solicitudes pendientes.</p>
        </div>
        <div v-for="req in upgradeRequests" :key="req.id"
          class="glass-card rounded-xl p-5 border flex items-center gap-4"
          :class="req.status === 'pending' ? 'border-primary-container/20' : 'border-white/5'">
          <div class="w-10 h-10 rounded-full bg-surface-container-high border border-white/5 flex items-center justify-center text-sm font-black text-primary-container shrink-0">
            {{ req.user?.name?.charAt(0)?.toUpperCase() }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-bold text-white text-sm">{{ req.user?.name }}</p>
            <p class="text-xs text-on-surface-variant font-mono">{{ req.user?.email }}</p>
            <p v-if="req.message" class="text-xs text-on-surface-variant mt-1 italic">"{{ req.message }}"</p>
          </div>
          <div class="flex items-center gap-2 shrink-0">
            <span class="text-[10px] font-mono px-2 py-1 rounded-full"
              :class="req.status === 'pending' ? 'bg-primary-container/10 text-primary-container border border-primary-container/30' :
                      req.status === 'approved' ? 'bg-secondary-fixed/10 text-secondary-fixed border border-secondary-fixed/30' :
                      'bg-error/10 text-error border border-error/30'">
              {{ req.status === 'pending' ? 'PENDIENTE' : req.status === 'approved' ? 'APROBADO' : 'RECHAZADO' }}
            </span>
            <template v-if="req.status === 'pending'">
              <button @click="approveUpgrade(req.id)"
                class="px-3 py-1.5 bg-primary-container text-on-primary text-xs font-mono font-bold rounded-lg hover:opacity-90 transition-all">
                Aprobar
              </button>
              <button @click="rejectUpgrade(req.id)"
                class="px-3 py-1.5 border border-error/30 text-error text-xs font-mono rounded-lg hover:bg-error/10 transition-all">
                Rechazar
              </button>
            </template>
          </div>
        </div>
      </div>

      <!-- Routines Tab -->
      <div v-if="activeTab === 'routines'" class="space-y-6">
        <button @click="showRoutineForm = !showRoutineForm"
          class="flex items-center gap-2 bg-primary-container text-on-primary font-bold py-2.5 px-5 rounded-lg text-sm neon-glow-btn transition-all">
          <span class="material-symbols-outlined text-[18px]">add</span>
          Nueva Rutina Global
        </button>

        <!-- Create Routine Form -->
        <div v-if="showRoutineForm" class="glass-card rounded-xl p-6 border border-primary-container/20 space-y-5">
          <h3 class="font-bold text-white">Crear Rutina Global</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Nombre</label>
              <input v-model="routineForm.name" type="text" placeholder="Nombre de la rutina"
                class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
            </div>
            <div>
              <label class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Nivel</label>
              <div class="flex gap-2 h-11">
                <button type="button" @click="routineForm.tier = 'basic'"
                  class="flex-1 rounded-lg text-xs font-mono border transition-all"
                  :class="routineForm.tier === 'basic'
                    ? 'bg-surface-container-high text-white border-white/30'
                    : 'bg-surface-variant/20 text-on-surface-variant border-white/5 hover:border-white/15'">
                  Básico
                </button>
                <button type="button" @click="routineForm.tier = 'elite'"
                  class="flex-1 rounded-lg text-xs font-mono border transition-all"
                  :class="routineForm.tier === 'elite'
                    ? 'bg-primary-container text-on-primary border-primary-container shadow-[0_0_12px_rgba(57,255,20,0.3)]'
                    : 'bg-surface-variant/20 text-on-surface-variant border-white/5 hover:border-primary-container/30'">
                  Élite
                </button>
              </div>
            </div>
          </div>
          <div>
            <label class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Descripción</label>
            <textarea v-model="routineForm.description" rows="2" placeholder="Describe la rutina..."
              class="w-full px-4 py-3 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40 resize-none"></textarea>
          </div>
          <div>
            <label class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest block mb-2">Ejercicios</label>
            <div class="space-y-2 mb-3">
              <div v-for="(ex, i) in routineForm.exercises" :key="i" class="flex items-center gap-3 p-3 bg-surface-container rounded-lg border border-white/5">
                <select v-model="ex.id" class="flex-1 h-9 px-3 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-xs input-focus-ring">
                  <option value="">Seleccionar ejercicio</option>
                  <option v-for="e in allExercises" :key="e.id" :value="e.id">{{ e.name }} ({{ e.muscle_group }})</option>
                </select>
                <input v-model.number="ex.sets" type="number" min="1" max="10" placeholder="Series"
                  class="w-16 h-9 px-2 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-xs text-center input-focus-ring" />
                <span class="text-on-surface-variant text-xs">×</span>
                <input v-model.number="ex.reps" type="number" min="1" max="50" placeholder="Reps"
                  class="w-16 h-9 px-2 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-xs text-center input-focus-ring" />
                <input v-model="ex.notes" type="text" placeholder="Notas (opcional)"
                  class="flex-1 h-9 px-3 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-xs input-focus-ring placeholder-on-surface-variant/40" />
                <button @click="routineForm.exercises.splice(i,1)" class="text-error hover:opacity-80 transition-opacity">
                  <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
              </div>
            </div>
            <button @click="routineForm.exercises.push({id:'',sets:3,reps:12,notes:''})"
              class="text-xs font-mono text-primary-container border border-primary-container/30 px-3 py-1.5 rounded-lg hover:bg-neon-glow transition-all flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px]">add</span> Añadir Ejercicio
            </button>
          </div>
          <div v-if="routineError" class="text-sm text-error font-mono flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">warning</span>{{ routineError }}
          </div>
          <div class="flex gap-3">
            <button @click="createRoutine" :disabled="savingRoutine"
              class="px-6 py-2.5 bg-primary-container text-on-primary font-bold rounded-lg text-sm neon-glow-btn disabled:opacity-60">
              {{ savingRoutine ? 'Guardando...' : 'Crear Rutina' }}
            </button>
            <button @click="showRoutineForm = false; routineError = ''" class="px-6 py-2.5 text-on-surface-variant hover:text-on-surface text-sm font-mono">
              Cancelar
            </button>
          </div>
        </div>

        <!-- Existing global routines -->
        <div class="glass-card rounded-xl overflow-hidden border border-white/5">
          <div class="p-4 border-b border-white/5">
            <h3 class="font-bold text-white text-sm">Rutinas Globales</h3>
          </div>
          <div class="divide-y divide-white/5">
            <div v-for="r in globalRoutines" :key="r.id" class="flex items-center gap-4 px-6 py-4 hover:bg-surface-container/30 transition-colors">
              <div class="flex-1 min-w-0">
                <p class="font-bold text-white text-sm">{{ r.name }}</p>
                <p class="text-xs text-on-surface-variant font-mono mt-0.5">{{ r.exercises?.length || 0 }} ejercicios • {{ r.training_days }}d/sem</p>
              </div>
              <span class="text-[10px] font-mono px-2 py-1 rounded-full"
                :class="r.tier === 'elite' ? 'bg-primary-container/10 text-primary-container border border-primary-container/30' : 'bg-surface-container text-on-surface-variant border border-white/5'">
                {{ r.tier === 'elite' ? 'ÉLITE' : 'BÁSICO' }}
              </span>
              <button @click="deleteRoutine(r.id)" class="text-on-surface-variant hover:text-error transition-colors ml-2">
                <span class="material-symbols-outlined text-[18px]">delete</span>
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { fitnessService } from '../services/api'
import axios from 'axios'

const loading = ref(true)
const authError = ref(false)
const stats = ref({})
const recentUsers = ref([])
const upgradeRequests = ref([])
const globalRoutines = ref([])
const allExercises = ref([])
const activeTab = ref('users')
const showRoutineForm = ref(false)
const savingRoutine = ref(false)
const routineError = ref('')
const routineForm = ref({ name: '', tier: 'elite', description: '', training_days: 3, exercises: [] })

const tabs = [
  { key: 'users', label: 'Usuarios' },
  { key: 'upgrades', label: 'Solicitudes Élite' },
  { key: 'routines', label: 'Gestión de Rutinas' },
]

const pendingUpgrades = computed(() => upgradeRequests.value.filter(r => r.status === 'pending'))

const token = () => localStorage.getItem('auth_token')
const headers = () => ({ Authorization: `Bearer ${token()}` })

const loadAdminData = async () => {
  try {
    const [statsRes, upgradeRes, routinesRes, exercisesRes] = await Promise.allSettled([
      fitnessService.getAdminStats(),
      axios.get('/api/admin/upgrade-requests', { headers: headers() }),
      axios.get('/api/routines', { headers: headers() }),
      axios.get('/api/admin/exercises', { headers: headers() }),
    ])
    if (statsRes.status === 'fulfilled') {
      stats.value = statsRes.value.data.stats
      recentUsers.value = statsRes.value.data.recent_users
    }
    if (upgradeRes.status === 'fulfilled') upgradeRequests.value = upgradeRes.value.data
    if (routinesRes.status === 'fulfilled') globalRoutines.value = routinesRes.value.data.filter(r => !r.user_id)
    if (exercisesRes.status === 'fulfilled') allExercises.value = exercisesRes.value.data
  } catch (e) {
    if (e.response?.status === 403) authError.value = true
  } finally {
    loading.value = false
  }
}

const changeRole = async (userId, newRole) => {
  try {
    await fitnessService.updateUserRole(userId, newRole)
  } catch (e) { console.error(e) }
}

const approveUpgrade = async (id) => {
  try {
    await axios.post(`/api/admin/upgrade-requests/${id}/approve`, {}, { headers: headers() })
    await loadAdminData()
  } catch (e) { console.error(e) }
}

const rejectUpgrade = async (id) => {
  try {
    await axios.post(`/api/admin/upgrade-requests/${id}/reject`, {}, { headers: headers() })
    await loadAdminData()
  } catch (e) { console.error(e) }
}

const createRoutine = async () => {
  if (!routineForm.value.name) { routineError.value = 'El nombre es obligatorio.'; return }
  if (routineForm.value.exercises.some(e => !e.id)) { routineError.value = 'Selecciona un ejercicio en cada fila.'; return }
  savingRoutine.value = true
  routineError.value = ''
  try {
    const res = await axios.post('/api/routines', {
      ...routineForm.value,
      training_days: routineForm.value.exercises.length || 3,
    }, { headers: headers() })
    globalRoutines.value.unshift(res.data)
    routineForm.value = { name: '', tier: 'elite', description: '', training_days: 3, exercises: [] }
    showRoutineForm.value = false
  } catch (e) {
    routineError.value = e.response?.data?.message || 'Error al crear la rutina.'
  } finally {
    savingRoutine.value = false
  }
}

const deleteRoutine = async (id) => {
  try {
    await axios.delete(`/api/routines/${id}`, { headers: headers() })
    globalRoutines.value = globalRoutines.value.filter(r => r.id !== id)
  } catch (e) { console.error(e) }
}

onMounted(loadAdminData)
</script>
