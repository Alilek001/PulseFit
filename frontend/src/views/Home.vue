<template>
  <div>
    <!-- Hero Section -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-20 text-center">
      <h1 class="text-4xl sm:text-6xl font-bold tracking-tight text-white mb-6">
        A simpler way to track your fitness.
      </h1>
      <p class="mx-auto max-w-2xl text-lg text-[#a1a1aa] mb-10">
        PulseFit provides a clean, distraction-free environment to manage your workouts, track your macros, and monitor your progress over time.
      </p>
      <div class="flex justify-center gap-4">
        <router-link to="/register" class="bg-white text-black text-sm font-medium py-2.5 px-6 rounded-md hover:bg-gray-100 transition-colors shadow-sm">
          Get Started
        </router-link>
        <router-link to="/login" class="bg-[#27272a] text-white text-sm font-medium py-2.5 px-6 rounded-md hover:bg-[#3f3f46] transition-colors border border-[#3f3f46]">
          Sign In
        </router-link>
      </div>
    </div>

    <!-- Minimalist Features -->
    <div class="border-t border-[#27272a] bg-[#18181b] py-20">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
          <div>
            <h3 class="text-lg font-medium text-white mb-3">Tailored Routines</h3>
            <p class="text-sm text-[#a1a1aa] leading-relaxed">
              Stop guessing at the gym. Access high-quality training plans or build your own from our extensive exercise library.
            </p>
          </div>
          <div>
            <h3 class="text-lg font-medium text-white mb-3">Macro Tracking</h3>
            <p class="text-sm text-[#a1a1aa] leading-relaxed">
              Calculate exactly what your body needs to reach its goals. Log your meals and stay accountable every single day.
            </p>
          </div>
          <div>
            <h3 class="text-lg font-medium text-white mb-3">Clean Interface</h3>
            <p class="text-sm text-[#a1a1aa] leading-relaxed">
              No ads, no clutter. Just the metrics and tools that matter most to your physical transformation.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
