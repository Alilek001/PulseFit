<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-3xl font-heading font-black text-white flex items-center gap-3">
          <svg class="w-8 h-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
          Panel de Administración
        </h1>
        <p class="text-slate-400 mt-1">Gestiona usuarios, suscripciones élite y estadísticas de la plataforma.</p>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-4 border-dark-border border-t-neon rounded-full animate-spin"></div>
    </div>

    <!-- Auth Error State -->
    <div v-else-if="authError" class="p-8 text-center glass-panel rounded-2xl border-rose-500/30">
      <h2 class="text-xl font-bold text-rose-400 mb-2">Acceso Denegado</h2>
      <p class="text-slate-400">Esta sección es exclusiva para administradores del sistema PulseFit.</p>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Sidebar -->
      <div class="space-y-2">
        <button class="w-full text-left px-4 py-3 rounded-lg bg-neon/10 text-neon border border-neon/30 font-bold transition-all shadow-[0_0_15px_rgba(57,255,20,0.1)]">Vista General</button>
        <button class="w-full text-left px-4 py-3 rounded-lg text-slate-400 hover:bg-dark-base hover:text-white transition-colors font-bold">Gestión de Usuarios</button>
        <button class="w-full text-left px-4 py-3 rounded-lg text-slate-400 hover:bg-dark-base hover:text-white transition-colors font-bold">Base de Ejercicios</button>
        <button class="w-full text-left px-4 py-3 rounded-lg text-slate-400 hover:bg-dark-base hover:text-white transition-colors font-bold">Métricas de Rendimiento</button>
      </div>

      <!-- Content Area -->
      <div class="lg:col-span-3 space-y-6">
        
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="glass-panel p-6 rounded-2xl border border-dark-border">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Usuarios Totales</p>
            <p class="text-4xl font-heading font-black text-white mt-2">{{ stats.total_users || 0 }}</p>
            <p class="text-xs text-neon mt-2 font-bold">+ Registrados</p>
          </div>
          <div class="glass-panel p-6 rounded-2xl border border-dark-border">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Suscripciones Élite</p>
            <p class="text-4xl font-heading font-black text-amber-400 mt-2">{{ stats.premium_subs || 0 }}</p>
            <p class="text-xs text-neon mt-2 font-bold">Ingresos Activos</p>
          </div>
          <div class="glass-panel p-6 rounded-2xl border border-dark-border">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rutinas Generadas</p>
            <p class="text-4xl font-heading font-black text-white mt-2">{{ stats.active_workouts || 0 }}</p>
            <p class="text-xs text-slate-500 mt-2 font-bold">Histórico</p>
          </div>
        </div>

        <!-- Recent Users Table -->
        <div class="glass-panel rounded-2xl overflow-hidden border border-dark-border">
          <div class="p-6 border-b border-dark-border flex justify-between items-center">
            <h2 class="text-lg font-bold text-white">Últimos Registros</h2>
            <button class="text-sm font-bold text-neon hover:underline">Ver Todos</button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-dark-base/50 text-[10px] uppercase font-black tracking-widest text-slate-500 border-b border-dark-border">
                  <th class="px-6 py-4">Nombre</th>
                  <th class="px-6 py-4">Correo</th>
                  <th class="px-6 py-4">Suscripción</th>
                  <th class="px-6 py-4 text-right">Rango (Editar)</th>
                </tr>
              </thead>
              <tbody class="text-sm font-medium text-slate-300">
                <tr class="border-b border-dark-border hover:bg-dark-base/50 transition-colors" v-for="user in recentUsers" :key="user.id">
                  <td class="px-6 py-4 text-white font-bold">{{ user.name }}</td>
                  <td class="px-6 py-4 text-slate-400">{{ user.email }}</td>
                  <td class="px-6 py-4">
                    <span v-if="user.role === 'premium' || user.role === 'admin'" class="px-2 py-1 bg-amber-400/10 text-amber-400 font-bold rounded text-xs border border-amber-400/20">Élite / VIP</span>
                    <span v-else class="px-2 py-1 bg-slate-800 text-slate-400 font-bold rounded text-xs border border-dark-border">Básico</span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <select 
                      v-model="user.role" 
                      @change="changeRole(user.id, user.role)"
                      class="bg-dark-panel border border-dark-border rounded-lg px-2 py-1 text-xs text-white outline-none focus:border-neon cursor-pointer"
                    >
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

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { fitnessService } from '../services/api'

const loading = ref(true)
const authError = ref(false)
const stats = ref({})
const recentUsers = ref([])

const loadAdminData = async () => {
  try {
    const res = await fitnessService.getAdminStats()
    stats.value = res.data.stats
    recentUsers.value = res.data.recent_users
  } catch (error) {
    if (error.response?.status === 403) {
      authError.value = true
    }
    console.error("Error cargando panel de admin", error)
  } finally {
    loading.value = false
  }
}

const changeRole = async (userId, newRole) => {
  try {
    await fitnessService.updateUserRole(userId, newRole)
    // Refrescar estadísticas para mostrar el cambio en Premium Subs
    await loadAdminData()
  } catch (error) {
    console.error("Error cambiando rol", error)
    alert("No se pudo actualizar el rol.")
  }
}

onMounted(() => {
  loadAdminData()
})
</script>
