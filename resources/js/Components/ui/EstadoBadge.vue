<script setup>
import { computed } from 'vue';

const props = defineProps({
    estado: { type: String, required: true },
    label: String,
});

const COLORES = {
    preadmision: 'bg-surface2 text-texto-medio border-borde',
    pendiente_deposito: 'bg-serpost-tint text-serpost-dark border-serpost-line',
    admitido: 'bg-serpost-tint text-serpost border-serpost-line',
    clasificacion: 'bg-serpost-tint text-serpost border-serpost-line',
    transito: 'bg-serpost-tint text-serpost-dark border-serpost-line',
    aduana: 'bg-serpost-tint text-serpost-dark border-serpost-line',
    distribucion: 'bg-serpost-tint text-serpost border-serpost-line',
    entregado: 'bg-go-tint text-go border-go/30',
};

const clase = computed(() => COLORES[props.estado] ?? 'bg-surface2 text-texto-medio border-borde');
</script>

<template>
    <span class="inline-flex items-center rounded-xs border px-2 py-0.5 text-caption font-semibold" :class="clase">
        {{ label ?? estado }}
    </span>
</template>
