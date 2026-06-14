<script setup>
import { useI18n } from '@/Composables/useI18n';

const { lang, idiomas, setLang } = useI18n();
</script>

<template>
    <div class="flex items-center gap-1" role="group" aria-label="Idioma / Rimay / Language">
        <button
            v-for="idi in idiomas"
            :key="idi.codigo"
            type="button"
            class="rounded-full px-2.5 py-1.5 text-xs font-bold transition"
            :class="lang === idi.codigo ? 'bg-white text-serpost' : 'bg-white/15 text-white hover:bg-white/25'"
            :aria-pressed="lang === idi.codigo"
            :aria-label="idi.nombre"
            @click="setLang(idi.codigo)"
        >
            {{ idi.corto }}
        </button>
    </div>
</template>
