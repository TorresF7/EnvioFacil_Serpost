<script setup>

import { ref, watch } from 'vue';
import {
    ComboboxRoot,
    ComboboxAnchor,
    ComboboxInput,
    ComboboxContent,
    ComboboxViewport,
    ComboboxItem,
    ComboboxEmpty,
} from 'reka-ui';
import { useBuscadorDireccion } from '@/Composables/useBuscadorDireccion';
import AppField from '@/Components/ui/AppField.vue';
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    label: { type: String, default: 'Buscar dirección' },
    paisCodigo: { type: String, default: null },
    placeholder: { type: String, default: 'Escribe una calle, distrito o ciudad…' },
    help: String,
});

const emit = defineEmits(['seleccionar']);

const { consulta, resultados, cargando, limpiar } = useBuscadorDireccion({ paisCodigo: () => props.paisCodigo });

const abierto = ref(false);
watch(resultados, (r) => { abierto.value = r.length > 0; });

function elegir(item) {
    if (!item) return;
    emit('seleccionar', item);
    consulta.value = '';
    limpiar();
    abierto.value = false;
}

function cerrar() {
    limpiar();
    abierto.value = false;
}
</script>

<template>
    <AppField :label="label" :help="help">
        <ComboboxRoot
            v-model:open="abierto"
            :ignore-filter="true"
            class="relative block"
            @update:model-value="elegir"
        >
            <ComboboxAnchor class="relative block">
                <ComboboxInput
                    v-model="consulta"
                    :placeholder="placeholder"
                    class="input-base pr-11"
                    autocomplete="off"
                    @keydown.escape="cerrar()"
                />
                <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-texto-suave">
                    <span v-if="cargando" class="block h-4 w-4 animate-spin rounded-full border-2 border-borde border-t-serpost" />
                    <Icon v-else name="search" :size="18" />
                </span>
            </ComboboxAnchor>

            <ComboboxContent
                position="inline"
                class="absolute inset-x-0 top-full z-30 mt-1.5 max-h-72 overflow-auto rounded-card border border-borde bg-surface shadow-card"
            >
                <ComboboxViewport class="p-1">
                    <ComboboxItem
                        v-for="(item, i) in resultados"
                        :key="i"
                        :value="item"
                        class="flex cursor-pointer flex-col gap-0.5 rounded-input px-3 py-2.5 transition-colors data-[highlighted]:bg-serpost-tint data-[highlighted]:outline-none"
                    >
                        <span class="text-body font-semibold text-texto-fuerte">
                            {{ item.direccion || item.display.split(',')[0] }}
                        </span>
                        <span class="line-clamp-1 text-caption text-texto-medio">{{ item.display }}</span>
                    </ComboboxItem>

                    <ComboboxEmpty
                        v-if="!cargando && consulta.trim().length >= 4 && !resultados.length"
                        class="px-3 py-2.5 text-caption text-texto-suave"
                    >
                        Sin resultados. Prueba con la calle y el distrito.
                    </ComboboxEmpty>
                </ComboboxViewport>
            </ComboboxContent>
        </ComboboxRoot>
    </AppField>
</template>
