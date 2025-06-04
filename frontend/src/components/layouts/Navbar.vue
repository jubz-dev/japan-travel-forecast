<template>
  <header>
    <!-- Desktop layout -->
    <div v-if="isDesktop" class="city-selector-desktop">
      <a href="/" class="navbar-brand">
        <img src="/src/assets/images/logo.png" alt="Logo" height="33" />
      </a>
      <nav class="city-nav">
        <span
          v-for="(city, index) in cities"
          :key="city"
          class="city-link"
          :class="{ active: city === selectedCity }"
          @click.prevent="selectCity(city)"
        >
          {{ city }}
          <span v-if="index < cities.length - 1" class="separator">|</span>
        </span>
      </nav>
    </div>

    <!-- Mobile layout -->
    <div v-else class="city-selector-mobile">
      <a href="/" class="navbar-brand">
        <img src="/src/assets/images/logo.png" alt="Logo" height="33" />
      </a>

      <button class="toggle-btn" @click="isOpen = !isOpen">
        {{ isOpen ? 'Hide Cities' : 'Select a City' }}
        <span :class="isOpen ? 'icon-chevron-up' : 'icon-chevron-down'"></span>
      </button>

      <transition name="fade">
        <ul v-show="isOpen" class="city-list">
          <li
            v-for="city in cities"
            :key="city"
            class="city-item"
            :class="{ active: city === selectedCity }"
            @click.prevent="selectCity(city)"
          >
            {{ city }}
          </li>
        </ul>
      </transition>
    </div>
  </header>
</template>

<script setup>
/**
 * Navbar.vue
 * - Responsive header with city selection logic embedded directly.
 */

import { ref, onMounted, onUnmounted } from 'vue'

/**
 * Emits 'city-changed' event when the selected city changes.
 */
const emit = defineEmits(['city-changed'])

/**
 * Inline city selection logic (replaces useCities composable).
 */
const cities = ['Tokyo', 'Yokohama', 'Kyoto', 'Osaka', 'Sapporo', 'Nagoya']
const selectedCity = ref(cities[0])

/**
 * Selects a city and emits the 'city-changed' event.
 * @param {string} city - The city to select.
 */
function selectCity(city) {
  selectedCity.value = city
  emit('city-changed', city)
}

/**
 * State for toggling mobile dropdown.
 * @type {import('vue').Ref<boolean>}
 */
const isOpen = ref(false)

/**
 * Reactive state for desktop layout.
 * @type {import('vue').Ref<boolean>}
 */
const isDesktop = ref(window.innerWidth >= 768)

/**
 * Updates layout based on window size.
 * If desktop, show city list by default.
 */
function updateLayout() {
  isDesktop.value = window.innerWidth >= 768
  if (isDesktop.value) {
    isOpen.value = true
  }
}

onMounted(() => {
  window.addEventListener('resize', updateLayout)
  updateLayout()
})

onUnmounted(() => {
  window.removeEventListener('resize', updateLayout)
})
</script>

<style scoped>
/* Common header styling */
header {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1rem 0;
}

.navbar-brand {
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
}

/* Desktop layout styling */
.city-selector-desktop {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.city-nav {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  align-items: center;
}

.city-link {
  cursor: pointer;
  font-weight: 600;
  color: #231a15;
  transition: color 0.3s ease;
}
.city-link:hover,
.city-link.active {
  color: #e50914;
}

.separator {
  margin: 0 0.3rem;
  color: #231a15;
}

/* Mobile layout styling */
.city-selector-mobile {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
}

.toggle-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
  border: 2px solid #e50914;
  background-color: transparent;
  color: #e50914;
  font-weight: 600;
  cursor: pointer;
  width: 100%;
  justify-content: center;
  padding: 0.5rem;
  border-radius: 4px;
  transition: background-color 0.3s ease, color 0.3s ease;
}
.toggle-btn:hover {
  background-color: #e50914;
  color: #fff;
}

.city-list {
  list-style: none;
  padding: 0;
  width: 100%;
}
.city-item {
  cursor: pointer;
  font-weight: 600;
  color: #231a15;
  border-radius: 4px;
  margin-bottom: 0.5rem;
  padding: 0.5rem 1rem;
  text-align: left;
  transition: background-color 0.3s ease, color 0.3s ease;
}
.city-item:hover,
.city-item.active {
  background-color: #e50914;
  color: #fff;
  box-shadow: 0 0 10px rgba(229, 9, 20, 0.6);
}

/* Fade transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Icon arrows */
.icon-chevron-down::before {
  content: '▼';
}
.icon-chevron-up::before {
  content: '▲';
}

/**
 * Large screens (≥992px) layout adjustments.
 * - Sidebar and main content arranged side by side.
 */
@media (min-width: 992px) {
  header {
    padding: 1rem;
  }
}
</style>
