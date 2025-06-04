<template>
  <div>
    <!-- Spinner for loading state -->
    <Spinner :active="loading" />

    <!-- Error message -->
    <div v-if="error" class="custom-text-centered custom-text-black custom-my-3">
      {{ error }}
    </div>

    <!-- Weather carousel -->
    <div v-if="!loading && !error" id="weatherCarousel" class="carousel-container">
      <div class="custom-carousel-inner">
        <div>
          <div class="flex-between-center carousel-slide-bg">
            <div>
              <h2 class="custom-display-2">
                <strong>{{ currentTemp }}</strong>
              </h2>
              <ul class="custom-list-unstyled custom-small">
                <li v-if="currentCity">
                  <strong>Today in {{ currentCity }}, Japan</strong>
                </li>
                <li>{{ formatDate(currentForecast?.dt_txt) }}</li>
                <li v-if="currentForecast">
                  Wind: {{ currentForecast.wind.speed }} m/s |
                  Humidity: {{ currentForecast.main.humidity }}%
                </li>
              </ul>
            </div>
            <div>
              <img
                v-if="currentForecast"
                :src="getIconUrl(currentForecast.weather[0].icon)"
                :alt="currentForecast.weather[0].description"
                class="custom-mx-auto"
                width="150"
                height="150"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
/**
 * WeatherCarousel.vue
 * - Weather display component with spinner, error handling, and carousel-like presentation.
 * - All styling converted to custom vanilla CSS classes.
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
 * Currently selected city
 * @type {import('vue').ComputedRef<string|null>}
 */
const currentCity = computed(() => {
  return props.weather?.city?.name || props.city
})

/**
 * Loading state
 * @type {import('vue').Ref<boolean>}
 */
const loading = ref(true)

/**
 * Error message
 * @type {import('vue').Ref<string>}
 */
const error = ref('')

/**
 * Watch for weather data updates to manage loading/error states
 */
watchEffect(() => {
  loading.value = true
  if (props.weather?.list?.length) {
    loading.value = false
    error.value = ''
  } else {
    loading.value = false
    error.value = 'No weather update found for today.'
  }
})

/**
 * Current temperature display
 * @type {import('vue').ComputedRef<string>}
 */
const currentTemp = computed(() => {
  return props.weather?.list?.length ? `${props.weather.list[0].main.temp}°C` : '--'
})

/**
 * Current forecast data
 * @type {import('vue').ComputedRef<object|null>}
 */
const currentForecast = computed(() => {
  return props.weather?.list?.[0] || null
})

/**
 * Get icon URL for weather
 * @param {string} iconCode
 * @returns {string}
 */
function getIconUrl(iconCode) {
  return `https://openweathermap.org/img/wn/${iconCode}@2x.png`
}

/**
 * Format date for display
 * @param {string} dateString
 * @returns {string}
 */
function formatDate(dateString) {
  const date = new Date(dateString)
  const day = date.getDate()
  const weekday = date.toLocaleDateString(undefined, { weekday: 'long' })
  const month = date.toLocaleDateString(undefined, { month: 'short' })
  return `${day} ${month} , ${weekday}`
}
</script>

<style scoped>
/* Carousel container styling */
.carousel-container {
  position: relative;
  max-width: 100%;
  margin: 0.4rem auto;
  overflow: hidden;
  border-radius: 0.5rem;
}

/* Carousel inner wrapper */
.custom-carousel-inner {
  position: relative;
  width: 100%;
  overflow: hidden;
  border-radius: 1.5rem;
}

/* Slide background styling (glassy effect) */
.carousel-slide-bg {
  background-color: rgba(108, 117, 125, 0.5);
  height: 200px;
  -webkit-backdrop-filter: blur(10px);
  backdrop-filter: blur(10px);
  color: #fff;
}

/* Flex layout utilities */
.flex-between-center {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-left: 1.2rem;
  padding-right: 1.2rem;
}

.flex-around-center {
  display: flex;
  justify-content: space-around;
  align-items: center;
  padding-left: 3rem;
  padding-right: 3rem;
}

/* Large screens (≥992px) */
@media (min-width: 992px) {
  .carousel-container {
    width: 96%;
    margin: 0 1rem 0 1rem;
  }

  .flex-between-center {
    padding-left: 2.2rem;
    padding-right: 2.2rem;
  }

  .custom-display-2 {
    font-size: 4.5rem;
    font-weight: 300;
    line-height: 1.2;
    margin-bottom: 9rem;
    margin-top: -0.5rem;
  }
}
</style>
