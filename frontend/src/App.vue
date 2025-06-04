<template>
  <div>
    <!-- Main weather forecast layout container -->
    <div id="weatherForecast">
      <div class="custom-row">
        <!-- Main content section: navbar, carousel, weather card -->
        <div class="main-content-container">
          <!-- Navbar: handles city selection -->
          <Navbar @city-changed="onCityChanged" />

          <!-- Weather carousel: shows current weather -->
          <WeatherCarousel :weather="weather" :city="selectedCity" />

          <!-- Weather card: additional weather details -->
          <WeatherCard :weather="weather" :city="selectedCity" />
        </div>

        <!-- Sidebar: top places to visit -->
        <div class="sidebar-top-places">
          <PlaceCard :places="places" :city="selectedCity" class="custom-mt-4" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
/**
 * App.vue
 * - Main application layout and data loading logic.
 * - Displays weather forecast and places information.
 */

import { ref, onMounted } from 'vue'
import axios from 'axios'
import { API_BASE_URL } from './helpers/config'

// Component imports
import Navbar from './components/layouts/Navbar.vue'
import WeatherCard from './components/partials/WeatherCard.vue'
import PlaceCard from './components/layouts/Sidebar.vue'
import WeatherCarousel from './components/partials/WeatherCarousel.vue'

// Composable for managing places data
import { usePlaces } from './composables/usePlaces'

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
 * Places data and state (loading, error).
 */
const { places, loading: placesLoading, error: placesError, fetchPlaces } = usePlaces()

/**
 * Fetch weather and places data for a city.
 * @param {string} city - City name.
 */
async function fetchData(city = 'Tokyo') {
  selectedCity.value = city
  try {
    const weatherRes = await axios.get(`${API_BASE_URL}/weather/${city}`)
    weather.value = weatherRes.data
    fetchPlaces(city)
  } catch (error) {
    console.error('Error fetching data:', error)
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
 * Sidebar (top places): full width by default, margin top for spacing.
 */
.sidebar-top-places {
  width: 100%;
  margin-top: 1.5rem;
}

/**
 * Large screens (≥992px) layout adjustments.
 * - Sidebar and main content arranged side by side.
 */
@media (min-width: 992px) {
  .main-content-container {
    width: 66.666666%;
  }

  .sidebar-top-places {
    width: 33.333333%;
    margin-top: 0.5rem;
  }
}
</style>
