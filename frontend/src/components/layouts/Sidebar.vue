<template>
  <aside>
    <!-- Spinner shown while loading -->
    <Spinner :active="loading" />

    <!-- Error message display -->
    <div v-if="error" class="error-message">
      {{ error }}
    </div>

    <!-- Places section: list of top related places -->
    <section v-if="!error && places?.features?.length" class="places-section">
      <header class="places-header">
        <h1 class="section-title">Top Related Places</h1>
      </header>

      <div class="places-list">
        <article
          v-for="place in places.features"
          :key="place.properties.place_id"
          class="place-card"
        >
          <h6 class="place-title">
            {{ getEnglishName(place) }}
          </h6>
          <p class="place-location">
            <!-- place-icon --> {{ place.properties.formatted }}
          </p>

          <div class="place-details">
            <!-- Phone link if available -->
            <template v-if="place.properties.datasource?.raw?.phone">
              <!-- phone-icon -->
              <a
                :href="`tel:${place.properties.datasource.raw.phone}`"
                class="place-link"
              >
                {{ place.properties.datasource.raw.phone }}
              </a>
              <span class="separator">|</span>
            </template>

            <!-- Website link if available -->
            <template v-if="place.properties.datasource?.raw?.website">
              <!-- globe-icon -->
              <a
                :href="place.properties.datasource.raw.website"
                target="_blank"
                rel="noopener"
                class="place-link"
              >
                Website
              </a>
            </template>
          </div>
        </article>
      </div>
    </section>

    <!-- No places found message -->
    <div
      v-if="!loading && !error && !places?.features?.length"
      class="no-places-message"
    >
      No places found.
    </div>
  </aside>
</template>

<script setup>
/**
 * Sidebar.vue
 * - Sidebar component to display top related places for a city.
 * - Handles loading/error states.
 */

import { defineProps, ref, watchEffect } from 'vue'
import Spinner from './Spinner.vue'

/**
 * Props
 * @property {object} places - Places data object
 */
const props = defineProps({
  places: {
    type: Object,
    default: () => ({ features: [] })
  }
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
 * Watch for changes in places data and update loading/error states.
 */
watchEffect(() => {
  loading.value = true
  if (props.places?.features?.length) {
    loading.value = false
    error.value = ''
  } else {
    loading.value = false
    error.value = 'No places found.'
  }
})

/**
 * Get the English name of a place (fallback to default name).
 * @param {object} place
 * @returns {string}
 */
function getEnglishName(place) {
  const raw = place.properties.datasource?.raw
  return raw?.['name:en'] || place.properties.name
}
</script>

<style scoped>
/* Sidebar container */
aside {
  color: rgba(0, 0, 0, 0.85);
}

/* Section title */
.section-title {
  font-weight: bold;
  color: rgba(0, 0, 0, 0.85);
  margin-bottom: 0.8rem;
  color: #e50914;
}

/* Error and no places messages */
.error-message,
.no-places-message {
  text-align: center;
  color: rgba(0, 0, 0, 0.85);
  margin: 1rem 0;
}

/* List of places cards */
.places-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

/* Scrollable places list on medium+ screens */
@media (min-width: 768px) {
  .places-list {
    max-height: 75vh;
    overflow-y: auto;
  }
}

/* Individual place card styling */
.place-card {
  background-color: rgba(108, 117, 125, 0.5);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border: 1px solid rgba(108, 117, 125, 0.5);
  border-radius: 0.5rem;
  padding: 0.75rem;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
  color: #ffffff;
}

/* Place title styling */
.place-title {
  font-size: 0.9rem;
  margin: 0 0 0.25rem;
  font-weight: 600;
  color: #ffffff;
}

/* Place location styling */
.place-location {
  margin: 0 0 0.25rem;
  color: rgba(255, 255, 255, 0.85);
  font-size: 0.8rem;
}

/* Details row: phone, website, etc. */
.place-details {
  font-size: 0.75rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.25rem;
  align-items: center;
  color: #ffffff;
}

/* Links in place details */
.place-link {
  text-decoration: none;
  color:  #ffffff;
  font-size: 0.75rem;
}

/* Separator between phone and website */
.separator {
  color:  #ffffff;
  font-size: 0.75rem;
}

/**
 * Large screens (≥992px) layout adjustments.
 * - Sidebar and main content arranged side by side.
 */
@media (min-width: 992px) {
  /* List of places cards */
  .places-list {
    gap: 0.5rem;
  }
  /* Place title styling */
  .place-title {
    margin: 0 0 0.20rem;
    font-size: 0.75rem;
  }

  /* Place location styling */
  .place-location {
    margin: 0 0 0.20rem;
    font-size: 0.7rem;
  }
}
</style>
