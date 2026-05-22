<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
      <div>
        <h1 class="text-3xl font-heading font-black text-white flex items-center gap-3">
          <svg class="w-8 h-8 text-neon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
          Nutrición Avanzada
        </h1>
        <p class="text-slate-400 mt-1">Alimenta tu cuerpo con los macronutrientes correctos.</p>
      </div>
      <div class="flex bg-dark-panel rounded-lg p-1 border border-dark-border">
        <button class="px-4 py-1.5 rounded-md text-sm font-bold bg-neon text-dark-base shadow-sm">Calculadora</button>
        <button class="px-4 py-1.5 rounded-md text-sm font-bold text-slate-400 hover:text-white transition-colors">Recetario</button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-4 border-dark-border border-t-neon rounded-full animate-spin"></div>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      
      <!-- Calculator / Perfil -->
      <div class="glass-panel rounded-2xl p-8 border border-dark-border shadow-lg relative overflow-hidden">
        <h2 class="text-xl font-heading font-bold text-white mb-6 flex items-center gap-2">
          <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
          Tu Perfil Metabólico
        </h2>
        
        <form class="space-y-6">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Peso Actual (kg)</label>
              <div class="bg-slate-900 border border-dark-border rounded-xl px-4 py-3 text-white font-bold text-center">
                {{ user.weight }} kg
              </div>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Estatura (cm)</label>
              <div class="bg-slate-900 border border-dark-border rounded-xl px-4 py-3 text-white font-bold text-center">
                {{ user.height }} cm
              </div>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Objetivo Físico</label>
            <div class="grid grid-cols-3 gap-3">
              <div 
                @click="changeGoal('lose_weight')"
                :class="['border rounded-xl p-3 text-center cursor-pointer transition-all font-bold', user.goal === 'lose_weight' ? 'border-neon bg-neon/10 text-neon' : 'border-dark-border bg-slate-900 text-slate-400 hover:border-slate-500']"
              >
                <span class="text-sm">Definición</span>
              </div>
              <div 
                @click="changeGoal('maintain')"
                :class="['border rounded-xl p-3 text-center cursor-pointer transition-all font-bold', user.goal === 'maintain' ? 'border-neon bg-neon/10 text-neon' : 'border-dark-border bg-slate-900 text-slate-400 hover:border-slate-500']"
              >
                <span class="text-sm">Mantenimiento</span>
              </div>
              <div 
                @click="changeGoal('gain_muscle')"
                :class="['border rounded-xl p-3 text-center cursor-pointer transition-all font-bold', user.goal === 'gain_muscle' ? 'border-neon bg-neon/10 text-neon' : 'border-dark-border bg-slate-900 text-slate-400 hover:border-slate-500']"
              >
                <span class="text-sm">Volumen</span>
              </div>
            </div>
            <p v-if="updatingGoal" class="text-xs text-neon mt-2 animate-pulse">Recalculando macronutrientes...</p>
          </div>
          
          <p class="text-xs text-slate-500 mt-4 leading-relaxed">
            * El metabolismo basal se calcula con la ecuación revisada de Mifflin-St Jeor, adaptada con multiplicadores para actividad deportiva moderada-alta.
          </p>
        </form>
      </div>

      <!-- Results Preview -->
      <div class="space-y-6">
        <div class="glass-panel rounded-2xl p-8 border border-neon/30 relative overflow-hidden bg-gradient-to-br from-dark-panel to-dark-base shadow-[0_8px_32px_rgba(57,255,20,0.05)]">
          <div class="absolute top-0 right-0 w-48 h-48 bg-neon/10 rounded-full blur-[80px] pointer-events-none"></div>
          
          <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Presupuesto Calórico Diario</h3>
          <p class="text-5xl font-heading font-black text-white mb-8 tracking-tighter">{{ goals.calories }} <span class="text-xl text-neon font-bold tracking-normal">kcal</span></p>

          <div class="space-y-6">
            <div>
              <div class="flex justify-between text-sm mb-2">
                <span class="text-white font-bold">Proteínas</span>
                <span class="text-neon font-black">{{ goals.proteins }}g</span>
              </div>
              <div class="w-full bg-slate-900 rounded-full h-2 overflow-hidden border border-dark-border/50">
                <div class="bg-neon h-full rounded-full transition-all duration-1000 ease-out" style="width: 30%"></div>
              </div>
            </div>

            <div>
              <div class="flex justify-between text-sm mb-2">
                <span class="text-white font-bold">Carbohidratos</span>
                <span class="text-blue-400 font-black">{{ goals.carbs }}g</span>
              </div>
              <div class="w-full bg-slate-900 rounded-full h-2 overflow-hidden border border-dark-border/50">
                <div class="bg-blue-400 h-full rounded-full transition-all duration-1000 ease-out" style="width: 45%"></div>
              </div>
            </div>

            <div>
              <div class="flex justify-between text-sm mb-2">
                <span class="text-white font-bold">Grasas</span>
                <span class="text-red-400 font-black">{{ goals.fats }}g</span>
              </div>
              <div class="w-full bg-slate-900 rounded-full h-2 overflow-hidden border border-dark-border/50">
                <div class="bg-red-400 h-full rounded-full transition-all duration-1000 ease-out" style="width: 25%"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recipe Suggestion with Image -->
        <div class="glass-panel rounded-2xl p-6 border border-dark-border cursor-pointer hover:border-neon/50 transition-all duration-300 group overflow-hidden relative">
          <img src="../assets/healthy_bowl.png" class="absolute inset-0 w-full h-full object-cover opacity-10 group-hover:opacity-20 transition-opacity duration-500 mix-blend-luminosity" />
          <div class="absolute inset-0 bg-gradient-to-r from-dark-panel via-dark-panel/90 to-transparent"></div>
          
          <div class="relative z-10">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-heading font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-neon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                Sugerencia del Chef
              </h3>
              <span class="text-[10px] font-black uppercase tracking-wider bg-dark-base border border-dark-border text-slate-300 px-2 py-1 rounded">Post-Entreno</span>
            </div>
            <div class="flex gap-4 items-center">
              <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 border border-dark-border shadow-lg">
                <img src="../assets/healthy_bowl.png" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Healthy Bowl" />
              </div>
              <div>
                <h4 class="font-bold text-white mb-1 group-hover:text-neon transition-colors">Bol de Pollo y Boniato</h4>
                <p class="text-xs text-slate-400 mb-2 max-w-[200px]">Combinación perfecta de carbohidratos complejos y proteína magra.</p>
                <div class="flex gap-3 text-xs font-black">
                  <span class="text-neon bg-neon/10 px-2 py-0.5 rounded">550 kcal</span>
                  <span class="text-slate-300 bg-slate-900 px-2 py-0.5 rounded border border-dark-border">50g Proteína</span>
                </div>
              </div>
            </div>
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
const updatingGoal = ref(false)
const user = ref({})
const goals = ref({})

const loadData = async () => {
  try {
    const res = await fitnessService.getDashboard()
    user.value = res.data.user
    goals.value = res.data.goals
  } catch (error) {
    console.error("Error cargando perfil", error)
  } finally {
    loading.value = false
  }
}

const changeGoal = async (newGoal) => {
  if (user.value.goal === newGoal) return
  
  updatingGoal.value = true
  user.value.goal = newGoal // Optimistic UI update
  
  try {
    await fitnessService.updateProfile({ goal: newGoal })
    // Recargar macros
    await loadData()
  } catch (error) {
    console.error("Error actualizando objetivo", error)
  } finally {
    updatingGoal.value = false
  }
}

onMounted(() => {
  loadData()
})
</script>
