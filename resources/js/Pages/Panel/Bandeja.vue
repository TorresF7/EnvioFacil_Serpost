<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PanelLayout from '@/Components/PanelLayout.vue';
import EstadoBadge from '@/Components/ui/EstadoBadge.vue';

const props = defineProps({
    envios: { type: Object, default: () => ({ data: [] }) },
    filtros: { type: Object, default: () => ({}) },
    estados: { type: Array, default: () => [] },
    metricas: { type: Object, default: () => ({}) },
});

const q = ref(props.filtros.q ?? '');
const estado = ref(props.filtros.estado ?? '');

const lista = computed(() => props.envios.data ?? []);

function filtrar() {
    router.get('/panel', { q: q.value, estado: estado.value }, { preserveState: true, replace: true });
}

function limpiar() {
    q.value = '';
    estado.value = '';
    filtrar();
}

const tarjetas = computed(() => [
    { label: 'Total envíos', valor: props.metricas.total ?? 0, icono: '📦' },
    { label: 'Pendientes de depósito', valor: props.metricas.pendientes ?? 0, icono: '⏳' },
    { label: 'Admitidos', valor: props.metricas.admitidos ?? 0, icono: '✅' },
    { label: 'Registrados hoy', valor: props.metricas.hoy ?? 0, icono: '📅' },
]);
</script>

<template>
    <Head title="Bandeja de solicitudes" />
    <PanelLayout titulo="Bandeja de solicitudes" subtitulo="Pre-admisiones digitales de los clientes">
        <div class="mb-5 grid grid-cols-2 gap-3 lg:grid-cols-4">
            <div v-for="t in tarjetas" :key="t.label" class="rounded-card border border-borde bg-white p-4 shadow-card">
                <p class="text-2xl">{{ t.icono }}</p>
                <p class="mt-1 text-2xl font-extrabold text-texto-fuerte">{{ t.valor }}</p>
                <p class="text-xs text-texto-medio">{{ t.label }}</p>
            </div>
        </div>

        <div class="mb-4 flex flex-wrap items-center gap-2">
            <input
                v-model="q"
                type="text"
                placeholder="Buscar por código, destinatario o país..."
                class="input-base max-w-xs"
                @keyup.enter="filtrar"
            />
            <select v-model="estado" class="input-base max-w-xs" @change="filtrar">
                <option value="">Todos los estados</option>
                <option v-for="e in estados" :key="e.valor" :value="e.valor">{{ e.label }}</option>
            </select>
            <button class="rounded-input bg-serpost-azul px-4 py-2.5 text-sm font-semibold text-white" @click="filtrar">Buscar</button>
            <button class="rounded-input border border-borde px-4 py-2.5 text-sm font-semibold text-texto-medio" @click="limpiar">Limpiar</button>
        </div>

        <div class="overflow-hidden rounded-card border border-borde bg-white shadow-card">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-borde bg-fondo text-left text-xs uppercase text-texto-medio">
                        <th class="px-4 py-3">Código</th>
                        <th class="px-4 py-3">Cliente / Destinatario</th>
                        <th class="px-4 py-3">Destino</th>
                        <th class="px-4 py-3">Formulario</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Registrado</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="e in lista" :key="e.id" class="border-b border-borde/60 hover:bg-fondo">
                        <td class="px-4 py-3 font-bold text-serpost-azul">{{ e.codigo }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-texto-fuerte">{{ e.cliente?.name ?? '—' }}</p>
                            <p class="text-xs text-texto-medio">{{ e.destinatario?.nombre_completo }}</p>
                        </td>
                        <td class="px-4 py-3">{{ e.pais_destino }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded bg-fondo px-2 py-0.5 text-xs font-semibold">{{ e.formulario }}</span>
                            <span class="ml-1 text-xs text-texto-medio">{{ e.servicio_label }}</span>
                        </td>
                        <td class="px-4 py-3"><EstadoBadge :estado="e.estado" :label="e.estado_label" /></td>
                        <td class="px-4 py-3 text-xs text-texto-medio">{{ e.creado }}</td>
                        <td class="px-4 py-3 text-right">
                            <Link :href="`/panel/solicitud/${e.codigo}`" class="font-semibold text-serpost-azul hover:underline">Ver →</Link>
                        </td>
                    </tr>
                    <tr v-if="!lista.length">
                        <td colspan="7" class="px-4 py-10 text-center text-texto-suave">No hay solicitudes que coincidan.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </PanelLayout>
</template>
