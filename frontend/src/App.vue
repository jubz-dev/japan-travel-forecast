<template>
  <div>
    <div class="main-content-container">
      <Navbar @city-changed="onCityChanged" />
    </div>
  </div>
</template>

<script setup>
/**
 * App.vue
 * - Setup for Navbar.
 */
import Navbar from './components/layouts/Navbar.vue'

/**
 * Handle city change event from Navbar.
 * @param {string} city - The city name.
 */
function onCityChanged(city) {
  console.log('Selected city:', city)
}
</script>

<style scoped>
.main-content-container {
  width: 100%;
}
</style>
