import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router'
import axios from 'axios'

// Configure Axios
axios.defaults.withCredentials = true
axios.defaults.headers.common['Accept'] = 'application/json'

const app = createApp(App)
app.use(router)
app.mount('#app')
