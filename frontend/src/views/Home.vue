<template>
  <div class="min-h-screen bg-surface-container-lowest text-on-surface">

    <!-- Navbar -->
    <header class="fixed top-0 w-full z-50 bg-surface-glass backdrop-blur-md border-b border-white/5 h-16 flex items-center px-6 md:px-12 justify-between">
      <span class="text-xl font-black text-primary-container tracking-tighter">PulseFit</span>
      <nav class="flex items-center gap-6">
        <router-link to="/login" class="text-sm text-on-surface-variant hover:text-on-surface transition-colors font-mono">Iniciar Sesión</router-link>
        <router-link to="/register" class="h-9 px-5 bg-primary-container text-on-primary text-sm font-bold rounded-lg flex items-center neon-glow-btn transition-all">
          Empezar
        </router-link>
      </nav>
    </header>

    <!-- Hero -->
    <section class="relative flex items-center overflow-hidden" style="min-height: calc(100vh - 64px); margin-top: 64px;">
      <!-- Background image -->
      <img :src="gymHero" alt="" class="absolute inset-0 w-full h-full object-cover object-center opacity-70 pointer-events-none select-none" />
      <div class="absolute inset-0 bg-gradient-to-r from-surface-container-lowest via-surface-container-lowest/70 to-surface-container-lowest/20 pointer-events-none"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-surface-container-lowest via-transparent to-transparent pointer-events-none"></div>

      <div class="relative z-10 px-6 md:px-12 max-w-[1280px] mx-auto w-full py-20">
        <div class="max-w-2xl">
          <div class="flex items-center gap-3 mb-6">
            <span class="text-[10px] text-primary-container tracking-widest font-mono uppercase border border-primary-container/30 px-3 py-1 rounded-full bg-primary-container/5">Plataforma de Rendimiento Élite</span>
            <span class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest">Establecido 2026</span>
          </div>
          <h1 class="text-6xl md:text-8xl font-black text-white tracking-tighter leading-none">
            Redefine<br/>
            <span class="text-primary-container" style="text-shadow: 0 0 40px rgba(57,255,20,0.4)">Tus Límites</span>
          </h1>
          <p class="text-on-surface-variant text-lg mt-8 max-w-xl leading-relaxed">
            El ecosistema de rendimiento élite para atletas que exigen progreso basado en datos y herramientas de entrenamiento profesionales.
          </p>
          <div class="flex flex-wrap items-center gap-4 mt-10">
            <router-link to="/register" class="h-14 px-8 bg-primary-container text-on-primary font-bold text-base rounded-lg flex items-center gap-2 neon-glow-btn transition-all active:scale-95">
              <span class="material-symbols-outlined text-[20px]">bolt</span>
              Empieza Gratis
            </router-link>
            <router-link to="/login" class="h-14 px-8 bg-surface-glass backdrop-blur-md text-on-surface font-semibold text-base rounded-lg border border-white/10 hover:border-white/20 flex items-center transition-all">
              Ya soy atleta
            </router-link>
          </div>
          <!-- Live stats -->
          <div class="flex flex-wrap gap-6 mt-12 pt-8 border-t border-white/5">
            <div>
              <p class="text-2xl font-black text-white font-mono">100%</p>
              <p class="text-[10px] text-on-surface-variant font-mono uppercase tracking-wider mt-0.5">Gratuito</p>
            </div>
            <div class="w-px bg-white/5 hidden sm:block"></div>
            <div>
              <p class="text-2xl font-black text-primary-container font-mono">18+</p>
              <p class="text-[10px] text-on-surface-variant font-mono uppercase tracking-wider mt-0.5">Ejercicios</p>
            </div>
            <div class="w-px bg-white/5 hidden sm:block"></div>
            <div>
              <p class="text-2xl font-black text-white font-mono">3</p>
              <p class="text-[10px] text-on-surface-variant font-mono uppercase tracking-wider mt-0.5">Niveles de acceso</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features -->
    <section class="border-t border-white/5 bg-surface-dim py-24 px-6 md:px-12">
      <div class="max-w-[1280px] mx-auto">
        <div class="text-center mb-16">
          <span class="text-xs text-primary-container tracking-widest font-mono uppercase">Funcionalidades</span>
          <h2 class="text-4xl font-black text-white mt-4 tracking-tight">Diseñado para la Excelencia</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div v-for="feature in features" :key="feature.title" class="glass-card rounded-xl p-8 border border-white/5 hover:border-primary-container/20 transition-colors group flex flex-col">
            <div class="w-12 h-12 rounded-lg bg-neon-glow border border-primary-container/20 flex items-center justify-center mb-6 group-hover:bg-primary-container/20 transition-colors">
              <span class="material-symbols-outlined text-primary-container">{{ feature.icon }}</span>
            </div>
            <h3 class="text-lg font-bold text-white mb-3">{{ feature.title }}</h3>
            <p class="text-on-surface-variant leading-relaxed text-sm flex-1">{{ feature.desc }}</p>
            <div v-if="feature.metric" class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between">
              <span class="text-[10px] text-on-surface-variant font-mono uppercase">{{ feature.metric.label }}</span>
              <span class="text-sm font-bold font-mono" :class="feature.metric.color">{{ feature.metric.value }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- How it works -->
    <section class="border-t border-white/5 py-24 px-6 md:px-12">
      <div class="max-w-[1280px] mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
          <div>
            <span class="text-xs text-primary-container tracking-widest font-mono uppercase">Cómo funciona</span>
            <h2 class="text-4xl font-black text-white mt-4 tracking-tight leading-tight">Todo lo que necesitas<br/>en un solo lugar</h2>
            <p class="text-on-surface-variant mt-6 leading-relaxed">PulseFit centraliza entrenamiento, nutrición y progreso físico. Sin apps externas, sin datos dispersos.</p>
            <div class="mt-8 space-y-4">
              <div class="flex items-center gap-4 p-4 glass-card rounded-xl border border-white/5">
                <div class="w-10 h-10 rounded-lg bg-primary-container/10 border border-primary-container/30 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-primary-container text-[18px]">calendar_today</span>
                </div>
                <div>
                  <p class="font-bold text-white text-sm">Plan semanal personalizado</p>
                  <p class="text-xs text-on-surface-variant font-mono">Asigna rutinas a cada día y el dashboard las muestra automáticamente</p>
                </div>
              </div>
              <div class="flex items-center gap-4 p-4 glass-card rounded-xl border border-white/5">
                <div class="w-10 h-10 rounded-lg bg-secondary-fixed/10 border border-secondary-fixed/30 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-secondary-fixed text-[18px]">local_fire_department</span>
                </div>
                <div>
                  <p class="font-bold text-white text-sm">Macros calculados a tu medida</p>
                  <p class="text-xs text-on-surface-variant font-mono">Calorías y proteínas ajustadas según tu peso, altura y objetivo</p>
                </div>
              </div>
              <div class="flex items-center gap-4 p-4 glass-card rounded-xl border border-white/5">
                <div class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-white text-[18px]">trending_up</span>
                </div>
                <div>
                  <p class="font-bold text-white text-sm">Evolución de peso en gráfica</p>
                  <p class="text-xs text-on-surface-variant font-mono">Registra tu peso y visualiza tu progreso a lo largo del tiempo</p>
                </div>
              </div>
            </div>
          </div>
          <div class="glass-card rounded-xl p-8 border border-white/5 flex flex-col justify-between">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider font-mono mb-6">Niveles de Acceso</h3>
            <div class="space-y-4">
              <div class="p-4 rounded-xl border border-white/10 bg-surface-container">
                <div class="flex items-center justify-between mb-2">
                  <span class="text-xs font-mono font-bold text-on-surface uppercase">Básico</span>
                  <span class="text-xs font-mono text-primary-container">Gratuito</span>
                </div>
                <p class="text-xs text-on-surface-variant">Rutinas de introducción, plan semanal, registro nutricional y seguimiento de peso.</p>
              </div>
              <div class="p-4 rounded-xl border border-primary-container/30 bg-primary-container/5">
                <div class="flex items-center justify-between mb-2">
                  <span class="text-xs font-mono font-bold text-primary-container uppercase">Élite</span>
                  <span class="text-xs font-mono text-primary-container">Solicitable</span>
                </div>
                <p class="text-xs text-on-surface-variant">Todo lo anterior más rutinas avanzadas con detalle completo de ejercicios, series y técnica.</p>
              </div>
            </div>
            <div class="mt-6 pt-6 border-t border-white/5 grid grid-cols-3 gap-4 text-center">
              <div>
                <p class="text-2xl font-black text-primary-container font-mono">18+</p>
                <p class="text-[10px] text-on-surface-variant font-mono uppercase mt-1">Ejercicios</p>
              </div>
              <div>
                <p class="text-2xl font-black text-white font-mono">7</p>
                <p class="text-[10px] text-on-surface-variant font-mono uppercase mt-1">Grupos musc.</p>
              </div>
              <div>
                <p class="text-2xl font-black text-secondary-fixed font-mono">3</p>
                <p class="text-[10px] text-on-surface-variant font-mono uppercase mt-1">Roles</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="py-24 px-6 md:px-12 text-center">
      <div class="max-w-[600px] mx-auto">
        <span class="text-xs text-primary-container tracking-widest font-mono uppercase">Únete a la Élite</span>
        <h2 class="text-5xl font-black text-white tracking-tight mt-4 mb-6">¿Listo para la élite?</h2>
        <p class="text-on-surface-variant text-lg mb-10">Crea tu cuenta gratis y empieza a gestionar tu entrenamiento, nutrición y progreso desde un solo lugar.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
          <router-link to="/register" class="inline-flex items-center gap-2 h-14 px-10 bg-primary-container text-on-primary font-bold text-lg rounded-lg neon-glow-btn transition-all active:scale-95">
            <span class="material-symbols-outlined text-[20px]">bolt</span>
            Crear Cuenta Gratis
          </router-link>
          <router-link to="/login" class="inline-flex items-center gap-2 h-14 px-8 bg-surface-container text-on-surface font-semibold text-lg rounded-lg border border-white/5 hover:border-white/20 transition-all">
            Iniciar Sesión
          </router-link>
        </div>
        <p class="text-xs text-on-surface-variant font-mono mt-4">Acceso básico completamente gratuito</p>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-surface-container-lowest border-t border-white/5 py-12 px-6 md:px-12">
      <div class="max-w-[1280px] mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
          <span class="text-xl font-black text-primary-container tracking-tighter">PulseFit</span>
          <p class="text-xs text-on-surface-variant font-mono mt-1">© 2026 PulseFit Ecosystem. All rights reserved.</p>
        </div>
        <div class="flex gap-6">
          <a class="text-xs text-on-surface-variant hover:text-primary-container transition-colors font-mono" href="#">Privacidad</a>
          <a class="text-xs text-on-surface-variant hover:text-primary-container transition-colors font-mono" href="#">Términos</a>
          <a class="text-xs text-on-surface-variant hover:text-primary-container transition-colors font-mono" href="#">Soporte</a>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import gymHero from '../assets/gym_hero.png'

const features = [
  {
    icon: 'fitness_center',
    title: 'Rutinas Estructuradas',
    desc: 'Catálogo de rutinas básicas y élite creadas por expertos. Asigna cada rutina a un día de tu plan semanal y accede a ella desde el dashboard.',
    metric: { label: 'Grupos musculares', value: '7 categorías', color: 'text-primary-container' },
  },
  {
    icon: 'restaurant',
    title: 'Control Nutricional',
    desc: 'Registra tus comidas diarias y consulta tus macros en tiempo real. Calculadora calórica personalizada basada en tu peso, altura y objetivo.',
    metric: { label: 'Macros calculados', value: 'Mifflin-St Jeor', color: 'text-secondary-fixed' },
  },
  {
    icon: 'trending_up',
    title: 'Seguimiento de Progreso',
    desc: 'Registra tu peso periódicamente y visualiza tu evolución en una gráfica. Historial completo de entrenamientos realizados con duración y notas.',
    metric: { label: 'Historial', value: 'Ilimitado', color: 'text-white' },
  },
]
</script>
