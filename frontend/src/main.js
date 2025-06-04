/**
 * Entry point for the Vue application.
 * - Loads global styles and mounts the root App component.
 */
import { createApp } from 'vue'
import 'vue-loading-overlay/dist/css/index.css'
import './style.css'
import App from './App.vue'

createApp(App).mount('#app')