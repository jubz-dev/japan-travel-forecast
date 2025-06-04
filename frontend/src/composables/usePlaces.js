/**
 * Composable to manage the fetching of places for a city.
 * @module usePlaces
 */
import { ref } from 'vue'
import axios from 'axios'
import { API_BASE_URL } from '../helpers/config'

/**
 * Provides reactive state and a method to fetch places data.
 *
 * @returns {{
 *   places: import('vue').Ref<null|object[]>,
 *   loading: import('vue').Ref<boolean>,
 *   error: import('vue').Ref<string|null>,
 *   fetchPlaces: (city: string) => Promise<void>
 * }}
 */
export function usePlaces() {
  const places = ref(null)
  const loading = ref(false)
  const error = ref(null)

  /**
   * Fetches places data for a given city.
   * @param {string} city - City name to fetch places for.
   */
  async function fetchPlaces(city) {
    loading.value = true
    error.value = null

    try {
      const response = await axios.get(`${API_BASE_URL}/places/${city}`)
      places.value = response.data
    } catch (err) {
      error.value = err.message || 'Failed to fetch places'
      places.value = null
    } finally {
      loading.value = false
    }
  }

  return { places, loading, error, fetchPlaces }
}
