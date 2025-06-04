<template>
  <div>
    <!-- Spinner overlay -->
    <loading 
      :active="active" 
      :can-cancel="false"
      :is-full-page="false"
      :color="color"
      :loader="loader"
      :background-color="backgroundColor"
    />
  </div>
</template>

<script setup>
/**
 * Spinner.vue
 * - Reusable loading spinner component using vue-loading-overlay.
 * - Accepts customizable props for color, loader style, and background color.
 */

import { defineProps } from 'vue'
import Loading from 'vue-loading-overlay'

/**
 * Component props
 * @property {boolean} active - Whether the spinner is active.
 * @property {string} color - Color of the spinner.
 * @property {string} backgroundColor - Background color of the spinner overlay.
 * @property {string} loader - Loader style (e.g., dots, bars).
 */
const props = defineProps({
  active: {
    type: Boolean,
    default: false
  },
  color: {
    type: String,
    default: '#e50914'
  },
  backgroundColor: {
    type: String,
    default: 'rgba(255, 255, 255, 0.8)'
  },
  loader: {
    type: String,
    default: 'dots'
  }
})
</script>
