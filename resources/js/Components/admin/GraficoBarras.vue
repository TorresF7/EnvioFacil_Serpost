<script setup>
import { computed } from 'vue';
import { Bar } from 'vue-chartjs';
import '@/Components/admin/registroChart';

const props = defineProps({
    labels: { type: Array, default: () => [] },

    series: { type: Array, default: () => [] },
    horizontal: { type: Boolean, default: false },
    alto: { type: Number, default: 280 },
});

const data = computed(() => ({
    labels: props.labels,
    datasets: props.series.map((s) => ({
        label: s.label,
        data: s.data,
        backgroundColor: s.color,
        borderRadius: 6,
        maxBarThickness: 40,
    })),
}));

const opciones = computed(() => ({
    indexAxis: props.horizontal ? 'y' : 'x',
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: props.series.length > 1, position: 'top', labels: { usePointStyle: true, padding: 14 } },
    },
    scales: {
        x: {
            beginAtZero: true,
            grid: { display: !props.horizontal, color: '#e1ded4' },
            ticks: { precision: 0 },
        },
        y: {
            beginAtZero: true,
            grid: { display: props.horizontal, color: '#e1ded4' },
            ticks: { precision: 0 },
        },
    },
}));
</script>

<template>
    <div :style="{ height: alto + 'px' }">
        <Bar :data="data" :options="opciones" />
    </div>
</template>
