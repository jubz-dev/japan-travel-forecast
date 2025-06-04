<template>
  <div id="cityMap">
    <!-- Map container where Leaflet will render the map -->
    <div ref="mapRef" class="map-container"></div>
  </div>
</template>

<script setup>
/**
 * PlacesMap.vue
 * - Map display using Leaflet for showing related places on a map.
 * - Uses custom marker icon and tooltip styling.
 */

import { onMounted, watch, nextTick, ref } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import { API_BASE_URL } from '../../helpers/config'
import markerIconUrl from '@/assets/images/marker-icon.png'

/**
 * Props
 * @property {object} places - Places data to display on the map
 */
const props = defineProps({
  places: Object
})

/**
 * Map container reference
 * @type {import('vue').Ref<HTMLDivElement|null>}
 */
const mapRef = ref(null)

/**
 * State to track the Leaflet map instance
 * @type {L.Map|undefined}
 */
let map

/**
 * Get the English name of a place, falling back to default name.
 * @param {object} place
 * @returns {string}
 */
const getEnglishName = (place) => {
  const raw = place.properties.datasource?.raw
  return raw?.['name:en'] || place.properties.name
}

/**
 * Initialize the Leaflet map and add markers for places.
 */
const initMap = () => {
  if (!props.places?.features?.length || !mapRef.value) return

  // Center the map on the first place's coordinates
  const center = props.places.features[0].geometry?.coordinates
  if (!center) return

  map = L.map(mapRef.value).setView([center[1], center[0]], 13)

  // Add OpenStreetMap tile layer
  L.tileLayer(`${API_BASE_URL}/tiles/{z}/{x}/{y}.png`, {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    maxZoom: 20
  }).addTo(map)

  // Add markers for each place
  props.places.features.forEach((place) => {
    const coords = place.geometry?.coordinates
    if (!coords) return

    const [lon, lat] = coords
    const name = getEnglishName(place)
    let address = place.properties?.formatted || ''

    // Shorten long addresses for tooltip
    if (address.length > 22) {
      address = address.substring(0, 22) + '...'
    }

    const marker = L.marker([lat, lon], {
      icon: L.icon({
        iconUrl: markerIconUrl,
        shadowUrl: new URL('leaflet/dist/images/marker-shadow.png', import.meta.url).href,
        iconRetinaUrl: markerIconUrl
      })
    }).addTo(map)

    marker.bindTooltip(`<strong>${name}</strong><br>${address}`, {
      permanent: true,
      direction: 'top',
      className: 'custom-tooltip'
    }).openTooltip()
  })
}

/**
 * Initialize the map after component is mounted.
 */
onMounted(() => {
  const loadMap = async () => {
    await nextTick()
    initMap()
  }
  loadMap()
})

/**
 * Watch for changes in places prop and reload map accordingly.
 */
watch(() => props.places, () => {
  const reloadMap = async () => {
    // Remove existing map instance
    if (map) {
      map.remove()
      map = null
    }
    await nextTick()
    initMap()
  }
  reloadMap()
})
</script>

<style scoped>
/* Map container styling */
.map-container {
  width: 100%;
  height: 500px;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

/* Custom tooltip styling for markers */
.custom-tooltip {
  background-color: white;
  color: black;
  border: 1px solid #ccc;
  padding: 4px 6px;
  border-radius: 4px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
  font-size: 0.85rem;
}
</style>
