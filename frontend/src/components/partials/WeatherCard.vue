<template>
  <div>
    <!-- Spinner shown while loading -->
    <Spinner :active="loading" />

    <!-- Error message display -->
    <div v-if="error" class="error-message">{{ error }}</div>

    <!-- 5-day forecast display -->
    <div v-if="!error && dailyWeather.length" class="forecast-container">
      <h1 v-if="currentCity" class="forecast-title">
        5-Day Forecast in {{ currentCity }}, Japan
      </h1>

      <div class="weather-list">
        <div
          v-for="item in dailyWeather"
          :key="item.dt"
          class="weather-card glassy-card"
        >
          <div class="weather-card-content">
            <div class="date">{{ formatDate(item.dt_txt) }}</div>
            <img
              :src="getIconUrl(item.weather[0].icon)"
              :alt="item.weather[0].description"
              width="90"
              height="90"
              class="weather-icon"
            />
            <div class="description">{{ item.weather[0].description }}</div>
          </div>
          <div class="weather-details">
            <div><strong>Temp:</strong> {{ item.main.temp }}°C</div>
            <div><strong>Wind:</strong> {{ item.wind.speed }} m/s</div>
            <div><strong>Humidity:</strong> {{ item.main.humidity }}%</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
/**
 * App.vue
 * - Main component displaying a 5-day weather forecast.
 * - Uses vanilla CSS classes, no Bootstrap.
 */

import { computed, ref, watchEffect } from 'vue'
import Spinner from '../layouts/Spinner.vue'

/**
 * Props
 * @property {object} weather - Weather data object
 */
const props = defineProps({
  weather: Object
})

/**
 * Computed property for current city name.
 * @type {import('vue').ComputedRef<string|undefined>}
 */
const currentCity = computed(() => props.weather?.city?.name)

/**
 * Loading state.
 * @type {import('vue').Ref<boolean>}
 */
const loading = ref(true)

/**
 * Error message to display.
 * @type {import('vue').Ref<string>}
 */
const error = ref('')

/**
 * Watch for updates to weather data and update loading and error states.
 */
watchEffect(() => {
  loading.value = true
  if (props.weather?.list?.length) {
    loading.value = false
    error.value = ''
  } else {
    loading.value = false
    error.value = 'No weather data found.'
  }
})

/**
 * Format a date string to a user-friendly format.
 * @param {string} dateString
 * @returns {string}
 */
function formatDate(dateString) {
  const date = new Date(dateString)
  const day = date.getDate()
  const weekday = date.toLocaleDateString(undefined, { weekday: 'short' })
  const month = date.toLocaleDateString(undefined, { month: 'short' })
  return `${day} ${month} (${weekday})`
}

/**
 * Get the icon URL for a weather condition.
 * @param {string} iconCode
 * @returns {string}
 */
function getIconUrl(iconCode) {
  return `https://openweathermap.org/img/wn/${iconCode}@2x.png`
}

/**
 * Computed property for daily weather forecasts (next 5 days).
 * - Filters for one forecast per day (preferably around noon).
 * @type {import('vue').ComputedRef<object[]>}
 */
const dailyWeather = computed(() => {
  if (!props.weather?.list) return []
  const today = new Date().toISOString().split('T')[0]
  const map = new Map()
  for (const item of props.weather.list) {
    const [date, time] = item.dt_txt.split(' ')
    if (date === today) continue
    if (!map.has(date) || time === '12:00:00') {
      map.set(date, item)
    }
  }
  return Array.from(map.values())
})
</script>

<style scoped>
/* Error message styling */
.error-message {
  text-align: center;
  color: rgba(0, 0, 0, 0.85);
  margin: 1rem 0;
}

/* Forecast container styling */
.forecast-container {
  padding-top: 1rem;
  margin-left: -0.9rem;
}

/* Forecast title styling */
.forecast-title {
  text-align: left;
  margin: 0 0 0 1rem;
  color: rgba(0, 0, 0, 0.85);
  padding-bottom: 0.5rem;
}

/* Weather list container (flex row) */
.weather-list {
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  padding-left: 1rem;
  box-sizing: border-box;
}

/* Individual weather card styling */
.weather-card {
  flex: 1 1 calc(50% - 1rem);
  max-width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding-top: 0.8rem;
  padding-bottom: 0.8rem;
  text-align: center;
  min-height: 220px;
}

/* Responsive card sizes */
@media (min-width: 576px) {
  .weather-card {
    flex: 1 1 calc(33.33% - 1rem);
    max-width: 100%;
  }
  .forecast-container {
    margin-left: -1.0rem;
  }
}

@media (min-width: 768px) {
  .weather-card {
    flex: 1 1 calc(25% - 1rem);
    max-width: 100%;
  }
  .forecast-container {
    margin-left: -1.0rem;
  }
}

@media (min-width: 992px) {
  .forecast-title {
    padding-bottom: 0;
  }
  .weather-list {
    padding: 0.5rem 1.2rem 1.2rem 1.2rem;
  }
  .weather-card {
    flex: 1 1 calc(15% - 1rem);
    padding: 0.75rem;
  }
  .forecast-container {
    margin-left: -0.1rem;
  }
}

/* Weather card content styling */
.weather-card-content .date {
  font-weight: 600;
  color: rgba(0, 0, 0, 0.85);
}

.weather-icon {
  margin: 0.5rem 0;
}

.description {
  text-transform: capitalize;
  font-size: 0.85rem;
  color: rgba(0, 0, 0, 0.7);
}

.weather-details {
  margin-top: 0.5rem;
  font-size: 0.8rem;
  color: rgba(0, 0, 0, 0.85);
}
</style>
