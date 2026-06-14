<script setup>
import { computed } from 'vue';

const props = defineProps({
    estadoActual: { type: String, default: 'pendiente_deposito' },
    tiempoEstimado: String,
});

const ESTADOS = [
    { valor: 'preadmision', label: 'Pre-admisión digital', desc: 'Tu información fue registrada.' },
    { valor: 'pendiente_deposito', label: 'Pendiente de depósito', desc: 'Lleva tu paquete dentro de 48 h.' },
    { valor: 'admitido', label: 'Admitido en oficina', desc: 'Admitido en ventanilla.' },
    { valor: 'clasificacion', label: 'Centro de clasificación', desc: 'CCPL: rayos X y clasificación.' },
    { valor: 'transito', label: 'Tránsito internacional', desc: 'En viaje al país destino.' },
    { valor: 'aduana', label: 'Aduana de destino', desc: 'En revisión aduanera.' },
    { valor: 'distribucion', label: 'En distribución', desc: 'El operador local lo lleva.' },
    { valor: 'entregado', label: 'Entregado', desc: 'Llegó a su destino.' },
];

const indiceActual = computed(() => ESTADOS.findIndex((e) => e.valor === props.estadoActual));

function estado(i) {
    if (i < indiceActual.value) return 'completado';
    if (i === indiceActual.value) return 'actual';
    return 'pendiente';
}
</script>

<template>
    <div>
        <p v-if="tiempoEstimado" class="mb-3 rounded-input bg-fondo px-3 py-2 text-sm text-texto-medio">
            🕒 Tiempo estimado de entrega: <b>{{ tiempoEstimado }}</b>
        </p>
        <ol class="relative">
            <li v-for="(e, i) in ESTADOS" :key="e.valor" class="flex gap-3 pb-4 last:pb-0">
                <div class="flex flex-col items-center">
                    <span
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-bold"
                        :class="{
                            'bg-serpost-verde text-white': estado(i) === 'completado',
                            'bg-serpost text-white ring-4 ring-serpost/20': estado(i) === 'actual',
                            'border border-borde bg-white text-texto-suave': estado(i) === 'pendiente',
                        }"
                    >
                        <span v-if="estado(i) === 'completado'">✓</span>
                        <span v-else-if="estado(i) === 'actual'">●</span>
                        <span v-else>{{ i + 1 }}</span>
                    </span>
                    <span v-if="i < ESTADOS.length - 1" class="mt-1 w-px flex-1"
                        :class="estado(i) === 'completado' ? 'bg-serpost-verde' : 'bg-borde'" />
                </div>
                <div class="pt-0.5" :class="{ 'opacity-50': estado(i) === 'pendiente' }">
                    <p class="text-sm font-semibold text-texto-fuerte">{{ e.label }}</p>
                    <p class="text-xs text-texto-medio">{{ e.desc }}</p>
                </div>
            </li>
        </ol>
    </div>
</template>
