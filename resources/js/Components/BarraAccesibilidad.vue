<script setup>
import { ref } from 'vue';
import { useAccesibilidad } from '@/Composables/useAccesibilidad';
import { useVoz } from '@/Composables/useVoz';
import { useI18n } from '@/Composables/useI18n';

const { size, contrast, ciclarTexto, alternarContraste } = useAccesibilidad();
const { hablando, leer, detener } = useVoz();
const { lang, t } = useI18n();

const aviso = ref('');

function alternarAudio() {
    if (hablando.value) {
        detener();
        return;
    }
    const texto = typeof document !== 'undefined'
        ? document.querySelector('main')?.innerText ?? ''
        : '';
    const ok = leer(texto, lang.value);
    if (!ok) {

        aviso.value = lang.value === 'qu'
            ? 'Manaraqmi runasimipi rimaynchu kanchu. Línea 1812-ta waqyay (qullqimanta).'
            : 'Aún no hay voz en quechua. Llama gratis a la Línea 1812 para un intérprete.';
        setTimeout(() => (aviso.value = ''), 6000);
    }
}
</script>

<template>
    <div class="flex flex-col gap-1">
        <div class="flex items-center gap-1.5" role="group" :aria-label="t('a11y_titulo')">
            <button
                type="button"
                class="flex items-center gap-1 rounded-full bg-white/15 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-white/25"
                :aria-label="`${t('a11y_texto')}: ${size}`"
                @click="ciclarTexto"
            >
                A+ <span class="text-[10px] uppercase opacity-80">{{ size }}</span>
            </button>
            <button
                type="button"
                class="rounded-full px-3 py-1.5 text-xs font-bold transition"
                :class="contrast ? 'bg-white text-serpost' : 'bg-white/15 text-white hover:bg-white/25'"
                :aria-pressed="contrast"
                :aria-label="t('a11y_contraste')"
                @click="alternarContraste"
            >
                ◐
            </button>
            <button
                type="button"
                class="rounded-full px-3 py-1.5 text-xs font-bold transition"
                :class="hablando ? 'bg-white text-serpost' : 'bg-white/15 text-white hover:bg-white/25'"
                :aria-label="hablando ? t('a11y_audio_detener') : t('a11y_audio')"
                @click="alternarAudio"
            >
                {{ hablando ? '⏹' : '🔊' }}
            </button>
        </div>
        <p v-if="aviso" role="alert" class="rounded bg-white/90 px-2 py-1 text-[11px] font-medium text-serpost-dark">
            {{ aviso }}
        </p>
    </div>
</template>
