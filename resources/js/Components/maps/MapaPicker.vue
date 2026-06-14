<script setup>
import { onMounted, onBeforeUnmount, ref, watch } from 'vue';
import L from 'leaflet';

const props = defineProps({
    lat: { type: Number, default: -12.0464 },
    lon: { type: Number, default: -77.0428 },
    zoom: { type: Number, default: 14 },
    editable: { type: Boolean, default: false },
});

const emit = defineEmits(['mover']);

const contenedor = ref(null);
let mapa = null;
let marcador = null;

function colocar(lat, lon) {
    if (!marcador) {
        marcador = L.marker([lat, lon]).addTo(mapa);
    } else {
        marcador.setLatLng([lat, lon]);
    }
}

onMounted(() => {
    mapa = L.map(contenedor.value, { scrollWheelZoom: false }).setView([props.lat, props.lon], props.zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 18,
    }).addTo(mapa);
    colocar(props.lat, props.lon);

    if (props.editable) {
        mapa.on('click', (e) => {
            colocar(e.latlng.lat, e.latlng.lng);
            emit('mover', { lat: e.latlng.lat, lon: e.latlng.lng });
        });
    }
});

watch(() => [props.lat, props.lon], ([lat, lon]) => {
    if (mapa) {
        colocar(lat, lon);
        mapa.panTo([lat, lon]);
    }
});

onBeforeUnmount(() => mapa?.remove());
</script>

<template>
    <div ref="contenedor" class="h-48 w-full overflow-hidden rounded-input border border-borde"></div>
</template>
