<template>
  <div>
    <!-- Main weather forecast layout container -->
    <div id="weatherForecast">
      <div class="custom-row">
        <!-- Main content section: navbar and weather carousel -->
        <div class="main-content-container">
          <!-- Navbar: handles city selection -->
          <Navbar @city-changed="onCityChanged" />

          <!-- Weather carousel: shows current weather -->
          <WeatherCarousel :weather="weather" :city="selectedCity" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
/**
 * App.vue
 * - Main application layout and data loading logic.
 * - Displays weather forecast with Navbar and WeatherCarousel.
 */

import { ref, onMounted } from 'vue'
import axios from 'axios'
import { API_BASE_URL } from './helpers/config'

// Component imports
import Navbar from './components/layouts/Navbar.vue'
import WeatherCarousel from './components/partials/WeatherCarousel.vue'

/**
 * Weather data for selected city.
 * @type {import('vue').Ref<object>}
 */
const weather = ref({})

/**
 * Currently selected city.
 * @type {import('vue').Ref<string>}
 */
const selectedCity = ref('Tokyo')

/**
 * Fetch weather data for a city.
 * @param {string} city - City name.
 */
async function fetchData(city = 'Tokyo') {
  selectedCity.value = city
  try {
    const weatherRes = await axios.get(`${API_BASE_URL}/weather/${city}`)
    weather.value = weatherRes.data
  } catch (error) {
    console.error('Error fetching weather data:', error)
  }
}

/**
 * Initial data fetch on mount.
 */
onMounted(() => fetchData())

/**
 * Handler for city change event from Navbar.
 * @param {string} city - Selected city name.
 */
function onCityChanged(city) {
  fetchData(city)
}
</script>

<style scoped>
/**
 * Weather forecast container styles.
 */
#weatherForecast {
  margin-top: 0 !important;
  padding-top: 0 !important;
  margin-top: 1.5rem;
}

/**
 * Main content container: full width by default.
 */
.main-content-container {
  width: 100%;
}

/**
 * Large screens (≥992px) layout adjustments.
 */
@media (min-width: 992px) {
  .main-content-container {
    width: 66.666666%;
  }
}
</style>
