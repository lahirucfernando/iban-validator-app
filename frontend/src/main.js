/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Plugins
import { registerPlugins } from '@/plugins'

// Components
import App from './App.vue'

// Composables
import { createApp } from 'vue'
import { useAuthStore } from '@/stores/authStore'

const app = createApp(App)

registerPlugins(app)

const authStore = useAuthStore();
authStore.loadTokenAndUser();

app.mount('#app')
