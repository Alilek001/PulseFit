<template>
  <div class="relative min-h-screen pb-12">
    <!-- Hero Header with Image -->
    <div class="relative w-full h-64 md:h-80 overflow-hidden mb-12 flex items-center">
      <img src="../assets/gym_hero.png" alt="Gym Background" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-screen" />
      <div class="absolute inset-0 bg-gradient-to-t from-dark-base via-dark-base/80 to-transparent"></div>
      <div class="absolute inset-0 bg-gradient-to-r from-dark-base via-dark-base/50 to-transparent"></div>
      
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex flex-col md:flex-row md:justify-between md:items-end gap-6">
        <div>
          <h1 class="text-4xl md:text-5xl font-heading font-black tracking-tight text-white mb-2">Entrenamientos</h1>
          <p class="text-slate-400 max-w-xl">Explora tu catálogo o permite que nuestro algoritmo avanzado genere una rutina con especificidad biomecánica.</p>
        </div>
        <button 
          @click="openGenerator"
          class="bg-neon text-dark-base text-sm font-black py-3 px-6 rounded-xl hover:scale-105 active:scale-95 transition-all shadow-[0_0_20px_rgba(57,255,20,0.3)] flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
          <span>Generar Rutina IA</span>
        </button>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Generador Modal Simulado -->
      <div v-if="showGenerator" class="mb-10 p-8 glass-panel rounded-2xl border border-neon/30 animate-[fadeIn_0.3s_ease-out] shadow-[0_10px_40px_rgba(57,255,20,0.05)] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-neon/10 rounded-full blur-[80px] pointer-events-none"></div>
        
        <h3 class="text-xl font-bold text-white mb-2 flex items-center gap-2">
          <svg class="w-6 h-6 text-neon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
          Motor de Inteligencia Artificial
        </h3>
        <p class="text-sm text-slate-400 mb-6 max-w-2xl">Selecciona el grupo muscular objetivo. El algoritmo ensamblará ejercicios óptimos con series y repeticiones para maximizar la hipertrofia celular.</p>
        
        <div v-if="genError" class="mb-6 text-sm font-bold text-rose-400 bg-rose-500/10 p-4 rounded-lg border border-rose-500/30 flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          {{ genError }}
        </div>

        <div class="flex flex-col sm:flex-row gap-4">
          <select v-model="selectedMuscle" class="bg-slate-900 border border-dark-border rounded-xl px-4 py-3 text-white font-medium outline-none focus:border-neon text-sm flex-1 cursor-pointer">
            <option value="Pecho" class="bg-slate-900 text-white">Pectorales (Pecho)</option>
            <option value="Espalda" class="bg-slate-900 text-white">Dorsales (Espalda)</option>
            <option value="Pierna" class="bg-slate-900 text-white">Tren Inferior (Piernas)</option>
            <option value="Hombro" class="bg-slate-900 text-white">Deltoides (Hombros)</option>
            <option value="Brazo" class="bg-slate-900 text-white">Brazos (Bíceps/Tríceps)</option>
          </select>
          <button 
            @click="generateRoutine"
            :disabled="isGenerating"
            class="bg-dark-panel hover:bg-slate-800 text-white font-bold py-3 px-8 rounded-xl transition-colors border border-dark-border disabled:opacity-50 flex items-center justify-center gap-2 min-w-[150px]"
          >
            <svg v-if="isGenerating" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span>{{ isGenerating ? 'Procesando...' : 'Ensamblar' }}</span>
          </button>
          <button @click="showGenerator = false" class="text-slate-400 hover:text-white px-4 font-medium">Cancelar</button>
        </div>
      </div>

      <!-- Tabs -->
      <div class="flex gap-2 mb-8 border-b border-dark-border pb-4 overflow-x-auto hide-scrollbar">
        <button class="px-5 py-2 text-sm font-bold text-dark-base bg-white rounded-lg whitespace-nowrap shadow-sm">Todas las Rutinas</button>
        <button class="px-5 py-2 text-sm font-bold text-slate-400 hover:text-white transition-colors whitespace-nowrap">Hipertrofia</button>
        <button class="px-5 py-2 text-sm font-bold text-slate-400 hover:text-white transition-colors whitespace-nowrap">Fuerza</button>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center py-12">
        <div class="w-8 h-8 border-4 border-dark-border border-t-neon rounded-full animate-spin"></div>
      </div>

      <!-- Rutinas Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-if="routines.length === 0" class="col-span-full text-center py-16 text-slate-500 glass-panel rounded-2xl border-dashed">
          <svg class="w-12 h-12 mx-auto mb-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
          <p class="text-lg font-medium text-white mb-1">Catálogo Vacío</p>
          <p class="text-sm">No tienes rutinas asignadas aún. ¡Genera una nueva!</p>
        </div>
        
        <div 
          v-for="routine in routines" 
          :key="routine.id" 
          class="glass-panel overflow-hidden group cursor-pointer hover:border-neon/50 hover:shadow-[0_0_25px_rgba(57,255,20,0.08)] transition-all duration-300 rounded-2xl flex flex-col"
        >
          <div class="p-6 border-b border-dark-border flex-1 bg-gradient-to-b from-dark-panel/30 to-transparent">
            <div class="flex justify-between items-start mb-3">
              <h3 class="text-lg font-bold text-white group-hover:text-neon transition-colors">{{ routine.name }}</h3>
              <span v-if="routine.is_premium" class="text-[9px] font-black uppercase tracking-widest text-amber-400 bg-amber-400/10 border border-amber-400/20 px-2.5 py-1 rounded-md shadow-sm">VIP</span>
            </div>
            <p class="text-sm text-slate-400 line-clamp-3 leading-relaxed">Plan de entrenamiento compuesto por {{ routine.exercises ? routine.exercises.length : 0 }} ejercicios seleccionados con alta especificidad. Basado en sobrecarga progresiva.</p>
          </div>
          <div class="px-6 py-4 bg-dark-base/80 flex justify-between items-center text-xs font-bold text-slate-500">
            <span class="flex items-center gap-1.5">
              <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
              {{ routine.training_days || 1 }} días / sem
            </span>
            <span class="flex items-center gap-1.5 text-slate-300">
              <svg class="w-4 h-4" :class="routine.is_premium ? 'text-amber-400' : 'text-neon'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
              Nivel {{ routine.is_premium ? 'Avanzado' : 'Intermedio' }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { fitnessService } from '../services/api'

const routines = ref([])
const loading = ref(true)
const showGenerator = ref(false)
const selectedMuscle = ref('Pecho')
const isGenerating = ref(false)
const genError = ref('')

const fetchRoutines = async () => {
  loading.value = true
  try {
    const res = await fitnessService.getRoutines()
    routines.value = res.data
  } catch (error) {
    console.error("Error cargando rutinas", error)
  } finally {
    loading.value = false
  }
}

const openGenerator = () => {
  showGenerator.value = !showGenerator.value
  genError.value = ''
}

const generateRoutine = async () => {
  isGenerating.value = true
  genError.value = ''
  try {
    const res = await fitnessService.generateRoutine({ muscle_group: selectedMuscle.value })
    // Add the new routine to the list automatically
    routines.value.unshift(res.data)
    showGenerator.value = false
  } catch (error) {
    if(error.response?.status === 403) {
      genError.value = error.response.data.message || 'Necesitas ser usuario Élite para usar la IA.'
    } else {
      genError.value = 'Ocurrió un error o no hay ejercicios para este grupo.'
    }
  } finally {
    isGenerating.value = false
  }
}

onMounted(() => {
  fetchRoutines()
})
</script>
