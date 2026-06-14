<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';
import EstadoBadge from '@/Components/ui/EstadoBadge.vue';
import Icon from '@/Components/ui/Icon.vue';
import GestorDocumentos from '@/Components/forms/GestorDocumentos.vue';

const props = defineProps({
    envios: { type: Object, default: () => ({ data: [] }) },
});

const lista = computed(() => props.envios.data ?? []);
</script>

<template>
    <Head title="Mis envíos" />
    <div class="app-shell flex min-h-screen flex-col">
        <AppHeader titulo="Mis envíos" subtitulo="Historial y seguimiento" />

        <main class="content-col flex-1 space-y-section px-4 py-4 sm:px-6 lg:py-8">
            <Link href="/app/nuevo" class="flex min-h-[52px] items-center justify-center gap-2 rounded-input bg-serpost text-base font-bold text-white transition hover:bg-serpost-dark lg:min-h-[48px]">
                <Icon name="plus" :size="18" /> Nuevo envío
            </Link>

            <p v-if="!lista.length" class="surface-flat p-6 text-center text-body text-texto-suave">
                Todavía no registras ningún envío.
            </p>

            <div v-else class="grid gap-3 sm:grid-cols-2">
                <div v-for="e in lista" :key="e.id" class="surface-flat p-3.5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-mono font-bold tracking-wide text-serpost">{{ e.codigo }}</p>
                            <p class="text-caption text-texto-medio">{{ e.creado }}</p>
                        </div>
                        <EstadoBadge :estado="e.estado" :label="e.estado_label" />
                    </div>
                    <div class="mt-2 grid grid-cols-2 gap-y-1 text-caption text-texto-medio">
                        <span>Servicio</span><span class="text-right font-medium text-texto-fuerte">{{ e.servicio_label }} ({{ e.formulario }})</span>
                        <span>Destino</span><span class="text-right font-medium text-texto-fuerte">{{ e.pais_destino }}</span>
                        <span>Oficina</span><span class="text-right font-medium text-texto-fuerte">{{ e.oficina_deposito }}</span>
                    </div>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <a :href="e.descargar_url" class="flex items-center justify-center gap-1.5 rounded-input border border-borde py-2 text-caption font-semibold text-serpost transition hover:bg-surface2">
                            <Icon name="download" :size="15" /> Formulario {{ e.formulario }}
                        </a>
                        <a :href="e.rotulado_url" class="flex items-center justify-center gap-1.5 rounded-input border border-borde py-2 text-caption font-semibold text-serpost transition hover:bg-surface2">
                            <Icon name="mappin" :size="15" /> Rótulo (PDF)
                        </a>
                    </div>

                    <details class="mt-2 rounded-input border border-borde bg-surface2">
                        <summary class="flex cursor-pointer list-none items-center gap-2 px-3 py-2 text-caption font-semibold text-texto-fuerte">
                            <Icon name="file" :size="15" class="text-serpost" />
                            Documentos y certificados
                            <span v-if="e.documentos?.length" class="ml-1 rounded-full bg-serpost/10 px-1.5 text-[10px] text-serpost">{{ e.documentos.length }}</span>
                            <Icon name="chevron-down" :size="15" class="ml-auto text-texto-suave" />
                        </summary>
                        <div class="border-t border-borde p-3">
                            <GestorDocumentos :codigo="e.codigo" :documentos="e.documentos ?? []" />
                        </div>
                    </details>
                </div>
            </div>
        </main>
    </div>
</template>
