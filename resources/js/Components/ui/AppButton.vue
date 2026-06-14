<script setup>
import { computed } from 'vue';

const props = defineProps({
    variante: { type: String, default: 'primary' },
    tipo: { type: String, default: 'button' },
    bloque: Boolean,
    cargando: Boolean,
    disabled: Boolean,
});

const variantes = {
    primary: 'bg-serpost text-white hover:bg-serpost-dark disabled:bg-serpost/40',
    secondary: 'bg-surface text-texto-fuerte border border-borde hover:bg-surface2',
    ghost: 'bg-transparent text-serpost hover:bg-serpost/10',
    danger: 'bg-rojo text-white hover:bg-rojo-dark',
    azul: 'bg-serpost-azul text-white hover:opacity-90',
    verde: 'bg-go text-white hover:opacity-90',
};

const clases = computed(() => variantes[props.variante] ?? variantes.primary);
</script>

<template>
    <button
        :type="tipo"
        :disabled="disabled || cargando"
        class="inline-flex min-h-[56px] items-center justify-center gap-2 rounded-input px-5 py-3 text-base font-bold transition disabled:cursor-not-allowed lg:min-h-[48px]"
        :class="[clases, { 'w-full': bloque, 'opacity-70': cargando }]"
    >
        <span v-if="cargando" class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white" />
        <slot />
    </button>
</template>
