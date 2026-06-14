<script setup>
import { computed } from 'vue';
import { useI18n } from '@/Composables/useI18n';
import { COLOR_VISUAL } from '@/Utils/semaforoNormativa';
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    veredicto: { type: Object, required: true },
});

const { t } = useI18n();

const visual = computed(() => COLOR_VISUAL[props.veredicto.color] ?? COLOR_VISUAL.ambar);

const esDesconocido = computed(() => props.veredicto.categoriaClave === 'desconocido');

const puedeSeguir = computed(() => props.veredicto.estado === 'restringido' || esDesconocido.value);

const encabezado = computed(() => {
    const v = props.veredicto;
    if (esDesconocido.value) return t('semaforo_desconocido');
    if (v.color === 'verde') return t('semaforo_verde');
    if (v.color === 'ambar') return v.entidad ? `${t('semaforo_permiso_de')} ${v.entidad}` : t('semaforo_permiso');
    return t('semaforo_rojo');
});

const linea = computed(() => (props.veredicto.color === 'verde' ? null : props.veredicto.que_necesitas));
</script>

<template>
    <div
        role="status"
        aria-live="polite"
        class="rounded-card border px-3 py-2.5"
        :class="visual.fondo"
    >
        <div class="flex items-start gap-2.5">
            <Icon :name="visual.icono" :size="20" class="mt-0.5 shrink-0" :class="visual.clase" />

            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <p class="text-body font-bold leading-snug" :class="visual.clase">{{ encabezado }}</p>
                    <span
                        v-if="puedeSeguir"
                        class="inline-flex items-center gap-1 rounded-xs bg-white/70 px-1.5 py-0.5 text-[11px] font-semibold text-texto-medio"
                    >
                        <Icon name="check" :size="12" class="text-go" />{{ t('puede_seguir') }}
                    </span>
                </div>

                <p v-if="linea" class="mt-1 text-caption text-texto-medio">{{ linea }}</p>

                <p v-if="veredicto.color === 'ambar' && !esDesconocido && veredicto.siguiente_paso" class="mt-0.5 text-[11px] text-texto-suave">
                    {{ veredicto.siguiente_paso }}
                </p>

                <p
                    v-if="veredicto.alternativa"
                    class="mt-1.5 flex items-center gap-1.5 text-caption font-semibold text-go"
                >
                    <Icon name="arrow-right" :size="14" class="shrink-0" />{{ veredicto.alternativa }}
                </p>

                <details v-if="veredicto.base_legal" class="mt-1.5">
                    <summary class="inline-flex cursor-pointer list-none items-center gap-1 text-[11px] font-medium text-texto-suave hover:text-texto-medio">
                        <Icon name="info" :size="12" />{{ t('veredicto_porque') }}
                    </summary>
                    <div class="mt-1.5 space-y-1 border-l-2 border-borde pl-2.5 text-[11px] leading-snug text-texto-suave">
                        <p>{{ veredicto.base_legal }}</p>
                        <p class="opacity-80">{{ t('veredicto_confianza') }}</p>
                    </div>
                </details>
            </div>
        </div>
    </div>
</template>
