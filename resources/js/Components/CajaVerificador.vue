<script setup>
import { useVerificadorProducto } from '@/Composables/useVerificadorProducto';
import { ARTICULOS_COMUNES, ESTADO_VISUAL } from '@/Utils/constantes';
import Icon from '@/Components/ui/Icon.vue';
import VeredictoSemaforo from '@/Components/VeredictoSemaforo.vue';

defineProps({
    mostrarComunes: { type: Boolean, default: true },
});

const { consulta, resultado } = useVerificadorProducto();
</script>

<template>
    <div>
        <div class="relative">
            <input
                v-model="consulta"
                type="text"
                placeholder="Ej: chompa de alpaca, paracetamol, café…"
                aria-label="Escribe qué quieres enviar"
                class="input-base pr-12 text-base"
            />
            <Icon name="search" :size="22" class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-texto-suave" />
        </div>

        <Transition name="fade">
            <div v-if="resultado" class="mt-3">
                <VeredictoSemaforo :veredicto="resultado" />
            </div>
        </Transition>

        <div v-if="mostrarComunes" class="mt-4">
            <p class="mb-2 text-caption font-bold uppercase tracking-wide text-texto-suave">O toca un ejemplo</p>
            <div class="flex flex-wrap gap-2">
                <button
                    v-for="art in ARTICULOS_COMUNES"
                    :key="art.nombre"
                    type="button"
                    class="inline-flex min-h-[56px] items-center gap-1.5 rounded-input border px-4 py-2 text-caption font-medium transition hover:brightness-95"
                    :class="ESTADO_VISUAL[art.estado].fondo"
                    @click="consulta = art.nombre"
                >
                    <Icon :name="ESTADO_VISUAL[art.estado].icono" :size="14" :class="ESTADO_VISUAL[art.estado].clase" />
                    {{ art.nombre }}
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
