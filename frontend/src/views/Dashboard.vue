<template>
  <div class="relative min-h-screen bg-dark-base overflow-hidden -mt-20">
    
    <!-- Hero Inmersivo con Video -->
    <div class="relative w-full h-[85vh] flex items-center justify-center">
      <!-- Video de Fondo -->
      <video 
        autoplay 
        loop 
        muted 
        playsinline 
        class="absolute inset-0 w-full h-full object-cover opacity-50 mix-blend-luminosity"
      >
        <source src="https://cdn.coverr.co/videos/coverr-woman-doing-push-ups-in-the-gym-2661/1080p.mp4" type="video/mp4" />
      </video>
      
      <!-- Overlays para contraste y fusión con el fondo -->
      <div class="absolute inset-0 bg-dark-base/40"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-dark-base via-dark-base/50 to-transparent"></div>
      <div class="absolute inset-0 bg-gradient-to-r from-dark-base/80 to-transparent"></div>

      <!-- Texto Motivacional (Estilo Freeletics) -->
      <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left mt-20">
        <h2 class="text-neon font-black tracking-widest uppercase text-sm md:text-base mb-4 drop-shadow-lg">
          Bienvenido de nuevo, {{ user?.name || 'Atleta' }}
        </h2>
        <h1 class="text-6xl md:text-8xl lg:text-9xl font-heading font-black text-white uppercase tracking-tighter leading-none mb-6">
          SUDOR.<br/>
          <span class="text-slate-300">DISCIPLINA.</span><br/>
          RESULTADOS.
        </h1>
        <p class="text-lg md:text-xl text-slate-300 max-w-2xl font-medium mb-10 drop-shadow-md">
          Tu cuerpo es un reflejo de tus hábitos. No hay atajos, solo trabajo duro constante y la rutina correcta.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
          <button @click="scrollToDashboard" class="bg-neon text-dark-base font-black py-4 px-8 rounded-xl hover:scale-105 active:scale-95 transition-all shadow-[0_0_30px_rgba(57,255,20,0.3)] uppercase tracking-wider text-sm flex items-center justify-center gap-3">
            <span>Ver mi plan de hoy</span>
            <svg class="w-5 h-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Dashboard Principal (Estadísticas y Tareas) -->
    <div id="dashboard-content" class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 pb-24">
      
      <!-- Tarjetas de Métricas Rápidas -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="glass-panel p-8 rounded-2xl border border-dark-border shadow-2xl backdrop-blur-xl bg-dark-panel/80 hover:border-neon/50 transition-colors">
          <div class="flex justify-between items-start mb-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Peso Actual</h3>
            <svg class="w-5 h-5 text-neon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-5xl font-black text-white tracking-tighter">{{ user?.weight || '--' }}</span>
            <span class="text-sm font-bold text-neon">kg</span>
          </div>
        </div>

        <div class="glass-panel p-8 rounded-2xl border border-dark-border shadow-2xl backdrop-blur-xl bg-dark-panel/80 hover:border-neon/50 transition-colors">
          <div class="flex justify-between items-start mb-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Rutina Activa</h3>
            <svg class="w-5 h-5 text-neon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-2xl font-bold text-white line-clamp-1">{{ activeRoutine?.name || 'Ninguna' }}</span>
          </div>
          <p class="text-xs font-black text-neon mt-2 uppercase tracking-wider" v-if="activeRoutine">En progreso</p>
          <p class="text-xs font-bold text-rose-400 mt-2" v-else>Selecciona una rutina</p>
        </div>

        <div class="glass-panel p-8 rounded-2xl border border-dark-border shadow-2xl backdrop-blur-xl bg-dark-panel/80 hover:border-neon/50 transition-colors">
          <div class="flex justify-between items-start mb-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Frecuencia Semanal</h3>
            <svg class="w-5 h-5 text-neon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-5xl font-black text-white tracking-tighter">3</span>
            <span class="text-sm font-bold text-slate-500">/ 5 días</span>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Área Principal (Entrenamiento de Hoy) -->
        <div class="lg:col-span-2 space-y-6">
          <div class="glass-panel p-8 rounded-2xl border border-dark-border bg-dark-panel/50">
            <div class="flex justify-between items-center mb-8">
              <h2 class="text-xl font-bold text-white flex items-center gap-3">
                <svg class="w-6 h-6 text-neon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                Ejercicios para Hoy
              </h2>
              <button class="text-xs font-bold text-neon hover:text-white transition-colors uppercase tracking-wider border border-neon/30 px-3 py-1.5 rounded-lg bg-neon/10">Ver todo</button>
            </div>
            
            <div v-if="activeRoutine" class="space-y-4">
              <div v-for="exercise in exercises" :key="exercise.id" class="p-5 rounded-xl bg-dark-base/50 border border-dark-border flex flex-col sm:flex-row justify-between sm:items-center gap-4 hover:border-slate-600 transition-colors">
                <div>
                  <p class="text-base font-bold text-white">{{ exercise.name }}</p>
                  <p class="text-sm font-medium text-slate-400 mt-1 flex items-center gap-2">
                    <span class="bg-dark-panel px-2 py-0.5 rounded">{{ exercise.pivot.sets }} Series</span>
                    <span class="text-neon">×</span>
                    <span class="bg-dark-panel px-2 py-0.5 rounded">{{ exercise.pivot.reps }} Repeticiones</span>
                  </p>
                </div>
                <button class="px-6 py-2 bg-slate-800 rounded-lg text-sm font-bold text-white hover:bg-neon hover:text-dark-base transition-colors whitespace-nowrap border border-slate-700 hover:border-neon">
                  Completar
                </button>
              </div>
            </div>
            <div v-else class="text-center py-16 border-2 border-dashed border-dark-border rounded-xl">
              <svg class="w-16 h-16 mx-auto mb-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
              <p class="text-lg font-bold text-white mb-2">Sin rutina activa</p>
              <p class="text-sm text-slate-400 mb-6">Selecciona o genera una rutina para comenzar a sudar.</p>
              <router-link to="/routines" class="inline-block bg-white text-dark-base text-sm font-bold py-3 px-8 rounded-xl hover:bg-neon transition-colors">Explorar Catálogo</router-link>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <div class="glass-panel p-6 rounded-2xl border border-dark-border bg-dark-panel/50">
            <h2 class="text-sm font-bold text-white mb-6 uppercase tracking-wider flex items-center gap-2">
              <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
              Objetivos Nutricionales
            </h2>
            <div class="space-y-5">
              <div>
                <div class="flex justify-between text-xs font-bold mb-2">
                  <span class="text-slate-400">Calorías</span>
                  <span class="text-white">1850 <span class="text-slate-600">/ 2200 kcal</span></span>
                </div>
                <div class="w-full bg-dark-base h-2 rounded-full overflow-hidden border border-dark-border">
                  <div class="bg-white h-full rounded-full" style="width: 80%"></div>
                </div>
              </div>
              <div>
                <div class="flex justify-between text-xs font-bold mb-2">
                  <span class="text-slate-400">Proteína</span>
                  <span class="text-white">120g <span class="text-slate-600">/ 150g</span></span>
                </div>
                <div class="w-full bg-dark-base h-2 rounded-full overflow-hidden border border-dark-border">
                  <div class="bg-neon h-full rounded-full" style="width: 80%"></div>
                </div>
              </div>
            </div>
            <router-link to="/nutrition" class="block text-center mt-6 text-xs font-bold text-slate-400 hover:text-neon transition-colors">Ajustar Macros &rarr;</router-link>
          </div>

          <!-- Banner Premium (Oculto si ya es élite) -->
          <div v-if="user?.role === 'user'" class="glass-panel p-8 rounded-2xl border border-neon/20 bg-gradient-to-br from-dark-base to-dark-panel relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-neon/10 rounded-full blur-[50px] pointer-events-none group-hover:bg-neon/20 transition-all duration-700"></div>
            <h3 class="text-base font-black text-white mb-2 flex items-center gap-2">
              <svg class="w-5 h-5 text-neon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
              Desbloquea PulseFit Élite
            </h3>
            <p class="text-sm text-slate-400 mb-6 leading-relaxed font-medium">Obtén acceso a rutinas avanzadas generadas por Inteligencia Artificial y analíticas de rendimiento biomecánico.</p>
            <button class="w-full bg-transparent border-2 border-neon text-neon font-black py-3 px-4 rounded-xl hover:bg-neon hover:text-dark-base transition-colors uppercase tracking-wider text-xs">
              Ver Planes Élite
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const user = ref(null)
const activeRoutine = ref(null)
const exercises = ref([])

const scrollToDashboard = () => {
  document.getElementById('dashboard-content').scrollIntoView({ behavior: 'smooth' })
}

onMounted(async () => {
  try {
    const token = localStorage.getItem('auth_token')
    const res = await axios.get('/api/dashboard', {
      headers: { Authorization: `Bearer ${token}` }
    })
    user.value = res.data.user
    activeRoutine.value = res.data.routine
    if (res.data.routine) {
      exercises.value = res.data.routine.exercises
    }
  } catch (e) {
    console.error('Error fetching dashboard data', e)
  }
})
</script>
