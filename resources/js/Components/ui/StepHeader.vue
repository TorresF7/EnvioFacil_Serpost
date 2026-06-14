<script setup>

import { computed } from 'vue';
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import { useI18n } from '@/Composables/useI18n';

const props = defineProps({
    tituloKey: String,
    subtituloKey: String,
    titulo: String,
    subtitulo: String,
    total: { type: Number, default: 6 },
    kicker: { type: Boolean, default: true },
});

const { t } = useI18n();
const store = useEnvioStore();
const { pasoActual } = storeToRefs(store);

const tituloTexto = computed(() => (props.tituloKey ? t(props.tituloKey) : props.titulo) || '');
const subtituloTexto = computed(() => (props.subtituloKey ? t(props.subtituloKey) : props.subtitulo) || '');
const numero = computed(() => Math.min(pasoActual.value + 1, props.total));
const etiquetaPaso = computed(() => `${t('enc_paso')} ${numero.value} ${t('enc_de')} ${props.total}`);
</script>

<template>
    <header class="mb-5">
        <p v-if="kicker" class="mb-1.5 font-mono text-[11px] font-bold uppercase tracking-[0.2em] text-serpost">
            {{ etiquetaPaso }}
        </p>
        <h1
            tabindex="-1"
            data-step-heading
            class="font-display text-display leading-[1.08] text-texto-fuerte outline-none"
        >
            {{ tituloTexto }}
        </h1>
        <p v-if="subtituloTexto" class="mt-2 max-w-[54ch] text-body-lg text-texto-medio">
            {{ subtituloTexto }}
        </p>
    </header>
</template>
