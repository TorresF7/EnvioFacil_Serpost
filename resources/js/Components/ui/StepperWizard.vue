<script setup>

import { computed } from 'vue';
import { storeToRefs } from 'pinia';
import {
    StepperRoot,
    StepperItem,
    StepperTrigger,
    StepperSeparator,
    StepperTitle,
    StepperDescription,
} from 'reka-ui';
import { useEnvioStore } from '@/Stores/envio';
import { useI18n } from '@/Composables/useI18n';
import Icon from '@/Components/ui/Icon.vue';

const PASOS = [
    { tituloKey: 'paso_producto', subKey: 'sub_producto' },
    { tituloKey: 'paso_destino', subKey: 'sub_destino' },
    { tituloKey: 'paso_contenido', subKey: 'sub_contenido' },
    { tituloKey: 'paso_remitente', subKey: 'sub_remitente' },
    { tituloKey: 'paso_servicio', subKey: 'sub_servicio' },
    { tituloKey: 'paso_revisa', subKey: 'sub_revisa' },
];

const store = useEnvioStore();
const { pasoActual } = storeToRefs(store);
const { t } = useI18n();

const actualUno = computed(() => Math.min(pasoActual.value + 1, PASOS.length));

function alNavegar(valor) {
    const destino = valor - 1;
    if (destino < pasoActual.value) store.irA(destino);
}
</script>

<template>
    <nav :aria-label="t('enc_pasos_aria')">
        <p class="mb-4 font-mono text-[11px] font-bold uppercase tracking-[0.18em] text-texto-suave">
            {{ t('enc_tu_envio') }}
        </p>
        <StepperRoot
            :model-value="actualUno"
            orientation="vertical"
            class="flex flex-col"
            @update:model-value="alNavegar"
        >
            <StepperItem
                v-for="(p, i) in PASOS"
                :key="p.tituloKey"
                v-slot="{ state }"
                :step="i + 1"
                class="flex gap-3"
            >
                <div class="flex flex-col items-center self-stretch">
                    <StepperTrigger
                        class="grid h-9 w-9 shrink-0 place-items-center rounded-full border-2 text-sm font-bold transition focus-visible:outline-none disabled:cursor-default"
                        :class="state === 'completed'
                            ? 'border-go bg-go text-white hover:brightness-95'
                            : state === 'active'
                                ? 'border-serpost bg-serpost text-white shadow-[0_0_0_4px_rgba(27,60,140,0.18)]'
                                : 'border-borde bg-surface text-texto-suave'"
                    >
                        <Icon v-if="state === 'completed'" name="check" :size="16" />
                        <span v-else>{{ i + 1 }}</span>
                    </StepperTrigger>
                    <StepperSeparator
                        v-if="i < PASOS.length - 1"
                        class="my-1 w-[2px] flex-1 rounded-full"
                        :class="state === 'completed' ? 'bg-go' : 'bg-borde'"
                    />
                </div>

                <div class="pb-5 pt-1.5">
                    <StepperTitle
                        class="block text-body font-bold leading-tight"
                        :class="state === 'active'
                            ? 'text-serpost-dark'
                            : state === 'completed'
                                ? 'text-texto-fuerte'
                                : 'text-texto-suave'"
                    >
                        {{ t(p.tituloKey) }}
                    </StepperTitle>
                    <StepperDescription class="mt-0.5 block text-caption text-texto-suave">
                        {{ t(p.subKey) }}
                    </StepperDescription>
                </div>
            </StepperItem>
        </StepperRoot>
    </nav>
</template>
