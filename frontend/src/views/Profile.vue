<template>
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-heading font-bold text-white mb-8">Profile Settings</h1>

    <div class="glass-panel rounded-xl overflow-hidden border border-[#1f2e4d]">
      <div class="p-8 border-b border-[#1f2e4d]">
        <div class="flex items-center gap-6">
          <div class="w-24 h-24 rounded-full bg-slate-800 border-2 border-[#39ff14] flex items-center justify-center text-3xl font-bold text-[#39ff14]">
            {{ user?.name ? user.name.charAt(0).toUpperCase() : 'U' }}
          </div>
          <div>
            <h2 class="text-2xl font-bold text-white">{{ user?.name || 'User Name' }}</h2>
            <p class="text-slate-400">{{ user?.email || 'user@example.com' }}</p>
            <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#39ff14]/10 border border-[#39ff14]/30 text-xs font-medium text-[#39ff14]">
              {{ user?.role === 'premium' ? 'Premium Member' : (user?.role === 'admin' ? 'Administrator' : 'Standard Member') }}
            </div>
          </div>
        </div>
      </div>

      <div class="p-8">
        <form class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">Weight (kg)</label>
              <input type="number" step="0.1" :value="user?.weight" class="input-field" placeholder="e.g. 75.5">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">Height (cm)</label>
              <input type="number" :value="user?.height" class="input-field" placeholder="e.g. 180">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Fitness Goal</label>
            <select class="input-field appearance-none">
              <option value="Lose Weight" :selected="user?.goal === 'Lose Weight'">Lose Weight</option>
              <option value="Build Muscle" :selected="user?.goal === 'Build Muscle'">Build Muscle</option>
              <option value="Maintain" :selected="user?.goal === 'Maintain'">Maintain Fitness</option>
              <option value="Increase Strength" :selected="user?.goal === 'Increase Strength'">Increase Strength</option>
            </select>
          </div>

          <div class="pt-4 flex justify-end">
            <button type="button" class="bg-[#39ff14] text-black px-6 py-2 rounded-lg text-sm font-semibold hover:bg-[#32e612] transition-colors">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const user = ref(null)

onMounted(async () => {
  try {
    const token = localStorage.getItem('auth_token')
    if(token) {
      const res = await axios.get('/api/user', {
        headers: { Authorization: `Bearer ${token}` }
      })
      user.value = res.data
    }
  } catch (e) {}
})
</script>
