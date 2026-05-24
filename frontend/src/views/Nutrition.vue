<template>
  <div class="p-4 md:p-12 max-w-[1280px] mx-auto space-y-12">

    <div v-if="loading" class="flex justify-center py-20">
      <div class="w-8 h-8 border-2 border-surface-container-high border-t-primary-container rounded-full animate-spin"></div>
    </div>

    <template v-else>
      <!-- Section 1: Macro Calculator -->
      <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Calorie Summary -->
        <div class="lg:col-span-2 glass-card rounded-xl p-8 flex flex-col justify-center">
          <div class="flex justify-between items-start mb-6">
            <div>
              <span class="text-xs text-primary-container tracking-widest font-mono uppercase">Calculadora Calórica</span>
              <h3 class="text-3xl font-black text-white mt-2 tracking-tight">Alimenta tu Rendimiento</h3>
            </div>
            <div class="p-3 bg-surface-container-highest rounded-lg border border-white/5">
              <span class="material-symbols-outlined text-primary-container">calculate</span>
            </div>
          </div>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 bg-surface-container-low rounded-lg border border-white/5">
              <p class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest">OBJETIVO</p>
              <p class="text-2xl font-bold text-primary-fixed-dim mt-1 font-mono">{{ goals.calories.toLocaleString() }}</p>
              <p class="text-xs text-on-surface-variant mt-1">kcal / día</p>
            </div>
            <div class="p-4 bg-surface-container-low rounded-lg border border-white/5">
              <p class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest">ACTUAL</p>
              <p class="text-2xl font-bold text-on-surface mt-1 font-mono">{{ totals.calories.toLocaleString() }}</p>
              <p class="text-xs text-on-surface-variant mt-1">kcal registradas</p>
            </div>
            <div class="p-4 bg-surface-container-low rounded-lg border border-white/5">
              <p class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest">RESTANTE</p>
              <p class="text-2xl font-bold font-mono mt-1" :class="(goals.calories - totals.calories) < 0 ? 'text-error' : 'text-on-surface-variant'">
                {{ Math.max(0, goals.calories - totals.calories).toLocaleString() }}
              </p>
              <p class="text-xs text-on-surface-variant mt-1">para el objetivo</p>
            </div>
            <div @click="showModal = true" class="p-4 bg-neon-glow rounded-lg border border-primary-container/20 flex items-center justify-center cursor-pointer hover:bg-primary-container/20 transition-all">
              <span class="material-symbols-outlined text-primary-container mr-2 text-[18px]">add_circle</span>
              <span class="text-xs font-mono font-bold text-primary-container uppercase">Registrar Comida</span>
            </div>
          </div>
        </div>

        <!-- Macro Rings -->
        <div class="glass-card rounded-xl p-8 flex flex-col items-center justify-center">
          <div class="relative w-44 h-44 mb-6">
            <svg class="w-full h-full -rotate-90">
              <circle class="text-surface-container-highest" cx="88" cy="88" fill="transparent" r="76" stroke="currentColor" stroke-width="10"/>
              <circle class="text-primary-container" cx="88" cy="88" fill="transparent" r="76" stroke="currentColor"
                :stroke-dasharray="478" :stroke-dashoffset="478 - (478 * Math.min(totals.proteins / goals.proteins, 1))" stroke-width="10"/>
              <circle class="text-surface-container-highest" cx="88" cy="88" fill="transparent" r="58" stroke="currentColor" stroke-width="10"/>
              <circle class="text-secondary-fixed" cx="88" cy="88" fill="transparent" r="58" stroke="currentColor"
                :stroke-dasharray="364" :stroke-dashoffset="364 - (364 * Math.min(totals.carbs / goals.carbs, 1))" stroke-width="10"/>
              <circle class="text-surface-container-highest" cx="88" cy="88" fill="transparent" r="40" stroke="currentColor" stroke-width="10"/>
              <circle class="text-error" cx="88" cy="88" fill="transparent" r="40" stroke="currentColor"
                :stroke-dasharray="251" :stroke-dashoffset="251 - (251 * Math.min(totals.fats / goals.fats, 1))" stroke-width="10"/>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
              <span class="text-[10px] text-on-surface-variant font-mono uppercase">MACROS</span>
              <span class="text-xs text-primary-container font-mono font-bold">ACTIVOS</span>
            </div>
          </div>
          <div class="w-full grid grid-cols-3 gap-2 text-center">
            <div>
              <p class="text-[10px] text-primary-container font-mono uppercase">Proteína</p>
              <p class="font-bold text-on-surface">{{ totals.proteins }}g</p>
              <p class="text-[10px] text-on-surface-variant font-mono">/ {{ goals.proteins }}g</p>
            </div>
            <div>
              <p class="text-[10px] text-secondary-fixed font-mono uppercase">Carbos</p>
              <p class="font-bold text-on-surface">{{ totals.carbs }}g</p>
              <p class="text-[10px] text-on-surface-variant font-mono">/ {{ goals.carbs }}g</p>
            </div>
            <div>
              <p class="text-[10px] text-error font-mono uppercase">Grasas</p>
              <p class="font-bold text-on-surface">{{ totals.fats }}g</p>
              <p class="text-[10px] text-on-surface-variant font-mono">/ {{ goals.fats }}g</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Section 2: Recipes -->
      <section class="space-y-6">
        <div class="flex justify-between items-end">
          <div>
            <h3 class="text-2xl font-black text-on-surface tracking-tight">Recetas Recomendadas</h3>
            <p class="text-on-surface-variant mt-1">Curadas según tu plan activo de recuperación.</p>
          </div>
          <button @click="showRecipeModal = true" class="flex items-center gap-2 px-4 py-2 bg-primary-container text-on-primary rounded-lg text-xs font-mono font-bold neon-glow-btn transition-all active:scale-95">
            <span class="material-symbols-outlined text-[16px]">add</span>
            NUEVA RECETA
          </button>
        </div>

        <!-- Search recipes -->
        <div class="relative">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant text-[18px]">search</span>
          <input v-model="recipeSearch" type="text" placeholder="Buscar receta..."
            class="w-full h-11 pl-11 pr-4 bg-surface-container border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
        </div>

        <div v-if="filteredRecipes.length === 0" class="text-center py-12 border-2 border-dashed border-surface-container-high rounded-xl">
          <span class="material-symbols-outlined text-5xl text-surface-container-highest block mb-4">restaurant</span>
          <p class="text-on-surface-variant">No hay recetas disponibles aún.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="recipe in filteredRecipes" :key="recipe.id" class="glass-card rounded-xl overflow-hidden group transition-all duration-300 hover:border-primary-container/20 flex flex-col">
            <div class="h-48 relative overflow-hidden bg-surface-container">
              <img :src="getRecipeImage(recipe)" :alt="recipe.name" class="w-full h-full object-cover transition-transform duration-500" />
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
              <div v-if="recipe.is_premium" class="absolute top-3 right-3 bg-primary-container text-on-primary px-3 py-1 rounded-full text-xs font-mono font-bold">ELITE</div>
            </div>
            <div class="p-6 flex flex-col flex-1 space-y-4">
              <h4 class="text-lg font-bold text-on-surface">{{ recipe.name }}</h4>
              <div class="flex justify-between border-b border-white/5 pb-4">
                <div class="text-center">
                  <p class="text-[10px] text-on-surface-variant font-mono uppercase">CALS</p>
                  <p class="font-mono text-sm font-bold text-on-surface">{{ recipe.calories }}</p>
                </div>
                <div class="text-center">
                  <p class="text-[10px] text-primary-container font-mono uppercase">P</p>
                  <p class="font-mono text-sm font-bold text-on-surface">{{ recipe.proteins }}g</p>
                </div>
                <div class="text-center">
                  <p class="text-[10px] text-secondary-fixed font-mono uppercase">C</p>
                  <p class="font-mono text-sm font-bold text-on-surface">{{ recipe.carbs }}g</p>
                </div>
                <div class="text-center">
                  <p class="text-[10px] text-error font-mono uppercase">F</p>
                  <p class="font-mono text-sm font-bold text-on-surface">{{ recipe.fats }}g</p>
                </div>
              </div>
              <div class="flex gap-2 mt-auto">
                <button @click="logRecipe(recipe)"
                  class="flex-1 py-3 bg-surface-container-high border border-white/10 rounded-lg text-xs font-mono hover:bg-primary-container hover:text-on-primary hover:border-primary-container transition-all flex items-center justify-center gap-2">
                  <span class="material-symbols-outlined text-[16px]">add</span>
                  REGISTRAR
                </button>
                <button @click="openEditRecipe(recipe)"
                  class="px-4 py-3 bg-surface-container-high border border-white/10 rounded-lg text-xs font-mono hover:bg-surface-container-highest transition-all flex items-center justify-center">
                  <span class="material-symbols-outlined text-[16px]">edit</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Section 3: Nutrition Log Stats -->
      <section class="glass-card rounded-xl p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
          <div>
            <h3 class="text-2xl font-black text-on-surface tracking-tight">Registro Nutricional Diario</h3>
            <p class="text-on-surface-variant mt-1">Ingesta vs objetivos de rendimiento diarios.</p>
          </div>
          <div class="flex gap-2">
            <button @click="view = 'weekly'" :class="view === 'weekly' ? 'bg-primary-container text-on-primary font-bold' : 'bg-surface-container-high text-on-surface-variant border border-white/5'"
              class="px-4 py-2 rounded-lg text-xs font-mono transition-all">VISTA SEMANAL</button>
            <button @click="view = 'today'" :class="view === 'today' ? 'bg-primary-container text-on-primary font-bold' : 'bg-surface-container-high text-on-surface-variant border border-white/5'"
              class="px-4 py-2 rounded-lg text-xs font-mono transition-all">HOY</button>
          </div>
        </div>

        <!-- Today view -->
        <div v-if="view === 'today'" class="space-y-6">
          <div v-for="stat in nutritionStats" :key="stat.label" class="space-y-2">
            <div class="flex justify-between text-xs font-mono">
              <span class="text-on-surface-variant uppercase">{{ stat.label }}</span>
              <span class="text-on-surface">{{ stat.current }} / {{ stat.target }}</span>
            </div>
            <div class="w-full h-3 bg-surface-container-lowest rounded-full overflow-hidden">
              <div class="h-full rounded-full transition-all duration-1000" :class="stat.color" :style="{ width: Math.min(stat.percent, 100) + '%' }"></div>
            </div>
          </div>
        </div>

        <!-- Weekly view: last 7 days -->
        <div v-else class="space-y-3">
          <div v-for="day in weeklyLog" :key="day.date" class="flex items-center gap-4 p-3 rounded-lg bg-surface-container border border-white/5">
            <span class="text-xs text-on-surface-variant font-mono w-24 shrink-0">{{ day.label }}</span>
            <div class="flex-1 h-2 bg-surface-container-lowest rounded-full overflow-hidden">
              <div class="h-full bg-primary-container rounded-full transition-all" :style="{ width: Math.min((day.calories / goals.calories) * 100, 100) + '%' }"></div>
            </div>
            <span class="text-xs font-mono text-on-surface w-20 text-right shrink-0">{{ day.calories }} kcal</span>
          </div>
        </div>

        <!-- Today's log entries -->
        <div v-if="view === 'today' && foodLogs.length" class="mt-8 pt-6 border-t border-white/5">
          <h4 class="text-sm font-bold text-white mb-4 font-mono uppercase tracking-wider">Alimentos Registrados Hoy</h4>
          <div class="space-y-2">
            <div v-for="log in foodLogs" :key="log.id" class="flex items-center justify-between p-3 bg-surface-container rounded-lg border border-white/5">
              <div>
                <p class="text-sm font-semibold text-white">{{ log.name }}</p>
                <p class="text-xs text-on-surface-variant font-mono">{{ log.calories }} kcal • P:{{ log.proteins }}g C:{{ log.carbs }}g G:{{ log.fats }}g</p>
              </div>
              <button @click="deleteLog(log.id)" class="text-on-surface-variant hover:text-error transition-colors ml-4">
                <span class="material-symbols-outlined text-[18px]">delete</span>
              </button>
            </div>
          </div>
        </div>
      </section>
    </template>

    <!-- Add Recipe Modal -->
    <Teleport to="body">
      <div v-if="showRecipeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="showRecipeModal = false; editingRecipe = null">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div class="relative glass-card rounded-xl w-full max-w-lg border border-white/10 shadow-2xl">
          <div class="p-6 border-b border-white/5 flex items-center justify-between">
            <h3 class="font-bold text-white">{{ editingRecipe ? 'Editar Receta' : 'Añadir Receta' }}</h3>
            <button @click="showRecipeModal = false; editingRecipe = null" class="text-on-surface-variant hover:text-white transition-colors">
              <span class="material-symbols-outlined">close</span>
            </button>
          </div>
          <div class="p-6 space-y-4">
            <div>
              <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Nombre de la Receta</label>
              <input v-model="recipeForm.name" type="text" placeholder="Ej: Pollo con arroz integral"
                class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
            </div>
            <div>
              <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">URL de la Foto</label>
              <input v-model="recipeForm.image_url" type="url" placeholder="https://..."
                class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Calorías</label>
                <input v-model.number="recipeForm.calories" type="number" min="0" placeholder="450"
                  class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
              </div>
              <div>
                <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Proteína (g)</label>
                <input v-model.number="recipeForm.proteins" type="number" min="0" placeholder="35"
                  class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
              </div>
              <div>
                <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Carbos (g)</label>
                <input v-model.number="recipeForm.carbs" type="number" min="0" placeholder="40"
                  class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
              </div>
              <div>
                <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Grasas (g)</label>
                <input v-model.number="recipeForm.fats" type="number" min="0" placeholder="15"
                  class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
              </div>
            </div>
            <!-- Preview -->
            <div v-if="recipeForm.image_url" class="h-32 rounded-lg overflow-hidden border border-white/10">
              <img :src="recipeForm.image_url" class="w-full h-full object-cover" @error="recipeForm.image_url = ''" />
            </div>
            <div v-if="recipeError" class="text-sm text-error font-mono flex items-center gap-2">
              <span class="material-symbols-outlined text-[16px]">warning</span>{{ recipeError }}
            </div>
            <button @click="submitRecipe" :disabled="savingRecipe"
              class="w-full h-11 bg-primary-container text-on-primary font-bold rounded-lg text-sm neon-glow-btn transition-all active:scale-95 disabled:opacity-60">
              {{ savingRecipe ? 'Guardando...' : editingRecipe ? 'Actualizar Receta' : 'Guardar Receta' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Food Log Modal -->
    <Teleport to="body">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="showModal = false">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div class="relative glass-card rounded-xl w-full max-w-md border border-white/10 shadow-2xl">
          <div class="p-6 border-b border-white/5 flex items-center justify-between">
            <h3 class="font-bold text-white">Registrar Comida</h3>
            <button @click="showModal = false" class="text-on-surface-variant hover:text-white transition-colors">
              <span class="material-symbols-outlined">close</span>
            </button>
          </div>
          <div class="p-6 space-y-4">
            <div>
              <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Nombre</label>
              <input v-model="logForm.name" type="text" placeholder="Ej: Pollo con arroz"
                class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Calorías</label>
                <input v-model.number="logForm.calories" type="number" min="0" placeholder="450"
                  class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
              </div>
              <div>
                <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Proteína (g)</label>
                <input v-model.number="logForm.proteins" type="number" min="0" placeholder="35"
                  class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
              </div>
              <div>
                <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Carbos (g)</label>
                <input v-model.number="logForm.carbs" type="number" min="0" placeholder="40"
                  class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
              </div>
              <div>
                <label class="text-xs text-on-surface-variant font-mono uppercase tracking-widest block mb-1.5">Grasas (g)</label>
                <input v-model.number="logForm.fats" type="number" min="0" placeholder="15"
                  class="w-full h-11 px-4 bg-surface-variant/30 border border-white/5 rounded-lg text-white text-sm input-focus-ring placeholder-on-surface-variant/40" />
              </div>
            </div>
            <div v-if="logError" class="text-sm text-error font-mono flex items-center gap-2">
              <span class="material-symbols-outlined text-[16px]">warning</span>{{ logError }}
            </div>
            <button @click="submitLog" :disabled="logging"
              class="w-full h-11 bg-primary-container text-on-primary font-bold rounded-lg text-sm neon-glow-btn transition-all active:scale-95 disabled:opacity-60">
              {{ logging ? 'Guardando...' : 'Guardar' }}
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
import healthyBowl from '../assets/healthy_bowl.png'

const recipeImagePool = [
  healthyBowl,
  'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=600&q=80',
  'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&q=80',
  'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&q=80',
  'https://images.unsplash.com/photo-1532636875304-0fcc6e1c8f98?w=600&q=80',
  'https://images.unsplash.com/photo-1547592180-85f173990554?w=600&q=80',
]
const getRecipeImage = (recipe) => recipe.image_url || recipeImagePool[recipe.id % recipeImagePool.length]

const loading = ref(true)
const recipes = ref([])
const recipeSearch = ref('')

const filteredRecipes = computed(() => {
  if (!recipeSearch.value.trim()) return recipes.value
  const q = recipeSearch.value.toLowerCase()
  return recipes.value.filter(r => r.name?.toLowerCase().includes(q))
})
const goals = ref({ calories: 2200, proteins: 150, carbs: 350, fats: 85 })
const totals = ref({ calories: 0, proteins: 0, carbs: 0, fats: 0 })
const foodLogs = ref([])
const view = ref('today')
const showModal = ref(false)
const logging = ref(false)
const logError = ref('')
const logForm = ref({ name: '', calories: '', proteins: '', carbs: '', fats: '' })
const showRecipeModal = ref(false)
const savingRecipe = ref(false)
const recipeError = ref('')
const recipeForm = ref({ name: '', image_url: '', calories: '', proteins: '', carbs: '', fats: '' })
const editingRecipe = ref(null)

const weeklyLog = computed(() => {
  const days = []
  for (let i = 6; i >= 0; i--) {
    const d = new Date()
    d.setDate(d.getDate() - i)
    const dateStr = d.toISOString().split('T')[0]
    const label = d.toLocaleDateString('es-ES', { weekday: 'short', day: 'numeric' })
    days.push({ date: dateStr, label, calories: i === 0 ? totals.value.calories : 0 })
  }
  return days
})

const nutritionStats = computed(() => [
  { label: 'Calorías', current: totals.value.calories, target: `${goals.value.calories} kcal`, percent: (totals.value.calories / goals.value.calories) * 100, color: 'bg-primary-container' },
  { label: 'Proteína', current: `${totals.value.proteins}g`, target: `${goals.value.proteins}g`, percent: (totals.value.proteins / goals.value.proteins) * 100, color: 'bg-secondary-fixed' },
  { label: 'Carbohidratos', current: `${totals.value.carbs}g`, target: `${goals.value.carbs}g`, percent: (totals.value.carbs / goals.value.carbs) * 100, color: 'bg-primary-container/40' },
  { label: 'Grasas', current: `${totals.value.fats}g`, target: `${goals.value.fats}g`, percent: (totals.value.fats / goals.value.fats) * 100, color: 'bg-error' },
])

const fetchFoodLog = async (token) => {
  const res = await axios.get('/api/food-log', { headers: { Authorization: `Bearer ${token}` } })
  foodLogs.value = res.data.logs
  totals.value = res.data.totals
}

onMounted(async () => {
  const token = localStorage.getItem('auth_token')
  await Promise.allSettled([
    fitnessService.getRecipes().then(r => { recipes.value = r.data }),
    axios.get('/api/dashboard', { headers: { Authorization: `Bearer ${token}` } })
      .then(r => { goals.value = r.data.goals }),
    fetchFoodLog(token),
  ])
  loading.value = false
})

const logRecipe = async (recipe) => {
  const token = localStorage.getItem('auth_token')
  try {
    await axios.post('/api/food-log', {
      name: recipe.name,
      calories: recipe.calories,
      proteins: recipe.proteins,
      carbs: recipe.carbs,
      fats: recipe.fats,
    }, { headers: { Authorization: `Bearer ${token}` } })
    await fetchFoodLog(token)
  } catch (e) {
    console.error('Log recipe error', e)
  }
}

const submitLog = async () => {
  if (!logForm.value.name || !logForm.value.calories) {
    logError.value = 'Nombre y calorías son obligatorios.'
    return
  }
  logging.value = true
  logError.value = ''
  try {
    const token = localStorage.getItem('auth_token')
    await axios.post('/api/food-log', logForm.value, { headers: { Authorization: `Bearer ${token}` } })
    await fetchFoodLog(token)
    logForm.value = { name: '', calories: '', proteins: '', carbs: '', fats: '' }
    showModal.value = false
  } catch (e) {
    logError.value = e.response?.data?.message || 'Error al guardar.'
  } finally {
    logging.value = false
  }
}

const openEditRecipe = (recipe) => {
  editingRecipe.value = recipe
  recipeForm.value = { name: recipe.name, image_url: recipe.image_url || '', calories: recipe.calories, proteins: recipe.proteins, carbs: recipe.carbs, fats: recipe.fats }
  recipeError.value = ''
  showRecipeModal.value = true
}

const submitRecipe = async () => {
  if (!recipeForm.value.name || !recipeForm.value.calories) {
    recipeError.value = 'Nombre y calorías son obligatorios.'
    return
  }
  savingRecipe.value = true
  recipeError.value = ''
  try {
    const token = localStorage.getItem('auth_token')
    if (editingRecipe.value) {
      const res = await axios.put(`/api/recipes/${editingRecipe.value.id}`, recipeForm.value, { headers: { Authorization: `Bearer ${token}` } })
      const idx = recipes.value.findIndex(r => r.id === editingRecipe.value.id)
      if (idx !== -1) recipes.value[idx] = res.data
    } else {
      const res = await axios.post('/api/recipes', recipeForm.value, { headers: { Authorization: `Bearer ${token}` } })
      recipes.value.push(res.data)
    }
    recipeForm.value = { name: '', image_url: '', calories: '', proteins: '', carbs: '', fats: '' }
    editingRecipe.value = null
    showRecipeModal.value = false
  } catch (e) {
    recipeError.value = e.response?.data?.message || 'Error al guardar la receta.'
  } finally {
    savingRecipe.value = false
  }
}

const deleteLog = async (id) => {
  const token = localStorage.getItem('auth_token')
  try {
    await axios.delete(`/api/food-log/${id}`, { headers: { Authorization: `Bearer ${token}` } })
    await fetchFoodLog(token)
  } catch (e) {
    console.error('Delete error', e)
  }
}
</script>
