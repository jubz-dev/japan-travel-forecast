<template>
  <div>
    <!-- Main weather forecast layout container -->
    <div id="weatherForecast">
      <div class="custom-row">
        <div class="main-content-container">
          <!-- Navbar: handles city selection -->
          <Navbar @city-changed="onCityChanged" />

          <!-- Weather carousel: shows current weather -->
          <WeatherCarousel :weather="weather" :city="selectedCity" />

          <!-- Weather card: additional weather details -->
          <WeatherCard :weather="weather" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { API_BASE_URL } from './helpers/config'

import Navbar from './components/layouts/Navbar.vue'
import WeatherCard from './components/partials/WeatherCard.vue'
import WeatherCarousel from './components/partials/WeatherCarousel.vue'

const weather = ref({})
const selectedCity = ref('Tokyo')

async function fetchData(city = 'Tokyo') {
  selectedCity.value = city
  try {
    const weatherRes = await axios.get(`${API_BASE_URL}/weather/${city}`)
    weather.value = weatherRes.data
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

onMounted(() => fetchData())

function onCityChanged(city) {
  fetchData(city)
}
</script>

<style scoped>
#weatherForecast {
  margin-top: 1.5rem;
}

.main-content-container {
  width: 100%;
}

@media (min-width: 992px) {
  .main-content-container {
    width: 66.666666%;
  }
}
</style>