<script setup>
import { computed } from 'vue';
import Icon from './Icon.vue';
import { ICONS } from './iconRegistry';

const props = defineProps({
    titulo: String,
    icono: String,
    subtitulo: String,
    enfasis: Boolean,
});

const esIcono = computed(() => !!props.icono && props.icono in ICONS);
</script>

<template>
    <section :class="enfasis ? 'surface-raised p-5 sm:p-6' : 'card-base'">
        <header v-if="titulo || $slots.acciones" class="mb-3 flex items-start justify-between gap-2">
            <div class="flex items-start gap-2.5">
                <span
                    v-if="esIcono"
                    class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-xs bg-serpost-tint text-serpost"
                >
                    <Icon :name="icono" :size="18" />
                </span>
                <div>
                    <h3 v-if="titulo" class="text-body-lg font-bold text-texto-fuerte">
                        <span v-if="icono && !esIcono" class="mr-1">{{ icono }}</span>{{ titulo }}
                    </h3>
                    <p v-if="subtitulo" class="mt-0.5 text-caption text-texto-medio">{{ subtitulo }}</p>
                </div>
            </div>
            <slot name="acciones" />
        </header>
        <slot />
    </section>
</template>
