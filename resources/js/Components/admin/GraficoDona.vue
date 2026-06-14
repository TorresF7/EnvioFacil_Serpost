<script setup>
import { computed } from 'vue';
import { Doughnut } from 'vue-chartjs';
import '@/Components/admin/registroChart';

const props = defineProps({
    labels: { type: Array, default: () => [] },
    data: { type: Array, default: () => [] },
    colors: { type: Array, default: () => [] },
    alto: { type: Number, default: 240 },
});

const datos = computed(() => ({
    labels: props.labels,
    datasets: [{ data: props.data, backgroundColor: props.colors, borderWidth: 2, borderColor: '#ffffff' }],
}));

const opciones = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '62%',
    plugins: {
        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 14 } },
    },
};

const vacio = computed(() => props.data.reduce((s, n) => s + (Number(n) || 0), 0) === 0);
</script>

<template>
    <div :style="{ height: alto + 'px' }" class="relative">
        <Doughnut v-if="!vacio" :data="datos" :options="opciones" />
        <p v-else class="grid h-full place-items-center text-caption text-texto-suave">Sin datos en el rango.</p>
    </div>
</template>
