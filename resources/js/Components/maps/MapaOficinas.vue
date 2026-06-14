<script setup>
import { onMounted, onBeforeUnmount, ref, watch } from 'vue';
import L from 'leaflet';

const props = defineProps({
    oficinas: { type: Array, default: () => [] },
    seleccionada: { type: Object, default: null },
});

const emit = defineEmits(['seleccionar']);

const contenedor = ref(null);
let mapa = null;
const marcadores = new Map();

const iconoNormal = L.divIcon({
    className: 'oficina-marker',
    html: '<div style="font-size:26px;line-height:1">📮</div>',
    iconSize: [26, 26],
    iconAnchor: [13, 26],
});
const iconoActivo = L.divIcon({
    className: 'oficina-marker-activo',
    html: '<div style="font-size:34px;line-height:1;filter:drop-shadow(0 2px 3px rgba(0,0,0,.4))">📍</div>',
    iconSize: [34, 34],
    iconAnchor: [17, 34],
});

function pintarMarcadores() {
    marcadores.forEach((m) => m.remove());
    marcadores.clear();

    props.oficinas.forEach((oficina) => {
        const marcador = L.marker([oficina.latitud, oficina.longitud], { icon: iconoNormal })
            .addTo(mapa)
            .bindTooltip(oficina.nombre, { direction: 'top', offset: [0, -22] });

        marcador.on('click', () => emit('seleccionar', oficina));
        marcadores.set(oficina.codigo, marcador);
    });

    actualizarSeleccion();
}

function actualizarSeleccion() {
    marcadores.forEach((marcador, codigo) => {
        const activo = props.seleccionada?.codigo === codigo;
        marcador.setIcon(activo ? iconoActivo : iconoNormal);
        if (activo) marcador.openTooltip();
    });
}

onMounted(() => {
    mapa = L.map(contenedor.value, { scrollWheelZoom: false }).setView([-9.19, -75.0152], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 18,
    }).addTo(mapa);
    pintarMarcadores();

    if (props.oficinas.length) {
        const limites = L.latLngBounds(props.oficinas.map((o) => [o.latitud, o.longitud]));
        mapa.fitBounds(limites, { padding: [30, 30] });
    }
});

watch(() => props.oficinas, pintarMarcadores, { deep: true });
watch(() => props.seleccionada, () => {
    actualizarSeleccion();
    if (props.seleccionada && mapa) {
        mapa.panTo([props.seleccionada.latitud, props.seleccionada.longitud]);
    }
});

onBeforeUnmount(() => mapa?.remove());
</script>

<template>
    <div ref="contenedor" class="h-56 w-full overflow-hidden rounded-input border border-borde"></div>
</template>
