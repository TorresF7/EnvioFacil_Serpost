<script setup>
import { computed } from 'vue';
import Icon from './Icon.vue';

const props = defineProps({
    tipo: { type: String, default: 'info' },
    titulo: String,
    icono: String,
});

const estilos = {
    info: { fondo: 'bg-serpost-tint border-serpost-line', color: 'text-serpost-dark', icono: 'info' },
    success: { fondo: 'bg-go-tint border-go/40', color: 'text-go', icono: 'circle-check' },
    warning: { fondo: 'bg-ambar-tint border-ambar/50', color: 'text-[#8a6400]', icono: 'triangle-alert' },
    error: { fondo: 'bg-stop-tint border-stop/50', color: 'text-stop', icono: 'octagon-x' },
};

const estilo = computed(() => estilos[props.tipo] ?? estilos.info);
</script>

<template>
    <div class="flex gap-2.5 rounded-input border px-3 py-2.5 text-body text-texto-fuerte" :class="estilo.fondo">
        <Icon :name="icono ?? estilo.icono" :size="20" class="mt-0.5" :class="estilo.color" />
        <div class="leading-snug">
            <p v-if="titulo" class="font-semibold" :class="estilo.color">{{ titulo }}</p>
            <slot />
        </div>
    </div>
</template>
