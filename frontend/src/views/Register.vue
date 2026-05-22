<template>
  <div class="min-h-[calc(100vh-3.5rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-semibold tracking-tight text-white">Create your account</h2>
        <p class="mt-2 text-center text-sm text-[#a1a1aa]">
          Already have an account? <router-link to="/login" class="font-medium text-[#f4f4f5] hover:text-[#10b981] transition-colors">Log in</router-link>
        </p>
      </div>
      
      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
        <div v-if="error" class="bg-red-900/20 border border-red-900/50 text-red-400 px-4 py-3 rounded-md text-sm">
          {{ error }}
        </div>

        <div class="space-y-4">
          <div>
            <label for="name" class="block text-sm font-medium text-[#f4f4f5]">Full Name</label>
            <input id="name" v-model="form.name" type="text" required class="input-field mt-1" placeholder="John Doe" />
          </div>
          <div>
            <label for="email" class="block text-sm font-medium text-[#f4f4f5]">Email address</label>
            <input id="email" v-model="form.email" type="email" required class="input-field mt-1" placeholder="you@example.com" />
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-[#f4f4f5]">Password</label>
            <input id="password" v-model="form.password" type="password" required class="input-field mt-1" placeholder="••••••••" />
          </div>
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-[#f4f4f5]">Confirm Password</label>
            <input id="password_confirmation" v-model="form.password_confirmation" type="password" required class="input-field mt-1" placeholder="••••••••" />
          </div>
        </div>

        <div>
          <button type="submit" class="btn-primary" :disabled="loading">
            {{ loading ? 'Creating account...' : 'Create account' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})
const error = ref('')
const loading = ref(false)

const handleRegister = async () => {
  error.value = ''
  loading.value = true
  
  try {
    const res = await axios.post('/api/register', form.value)
    if(res.data.token) {
      localStorage.setItem('auth_token', res.data.token)
      router.push('/dashboard')
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Error creating account'
  } finally {
    loading.value = false
  }
}
</script>
