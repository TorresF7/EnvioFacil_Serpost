<script setup>
import { computed, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PanelLayout from '@/Components/PanelLayout.vue';
import AppCard from '@/Components/ui/AppCard.vue';
import Icon from '@/Components/ui/Icon.vue';
import GraficoBarras from '@/Components/admin/GraficoBarras.vue';
import GraficoDona from '@/Components/admin/GraficoDona.vue';

const props = defineProps({
    rango: { type: Object, required: true },
    resumen: { type: Object, required: true },
    embudo: { type: Array, default: () => [] },
    abandonoCampos: { type: Array, default: () => [] },
    errores: { type: Array, default: () => [] },
    veredictos: { type: Object, required: true },
    topProductos: { type: Array, default: () => [] },
    topDesconocidos: { type: Array, default: () => [] },
    accesibilidad: { type: Object, required: true },
    dispositivos: { type: Object, required: true },
});

const C = { serpost: '#1b3c8c', azul: '#14306f', go: '#1f9d57', ambar: '#e0a106', stop: '#d11f2d', suave: '#a8b1c6' };

const desde = ref(props.rango.desde);
const hasta = ref(props.rango.hasta);

function aplicarRango() {
    router.get('/admin/metrics', { desde: desde.value, hasta: hasta.value }, {
        preserveScroll: true,
        preserveState: true,
    });
}

const kpis = computed(() => [
    { icono: 'user', label: 'Sesiones', valor: props.resumen.sesiones, nota: 'en el rango' },
    { icono: 'circle-check', label: 'Completitud', valor: props.resumen.tasa_completitud + '%', nota: props.resumen.completadas + ' envíos generados' },
    {
        icono: 'clock',
        label: 'Tiempo mediano',
        valor: props.resumen.tiempo_mediano_min ? props.resumen.tiempo_mediano_min + ' min' : '—',
        nota: 'reto presencial: ~15 min',
    },
    { icono: 'phone', label: 'Desde móvil', valor: props.resumen.pct_movil + '%', nota: props.dispositivos.mobile + ' móvil · ' + props.dispositivos.desktop + ' escritorio' },
]);

const embudoChart = computed(() => ({
    labels: props.embudo.map((p) => p.label),
    series: [
        { label: 'Entraron', data: props.embudo.map((p) => p.entraron), color: C.serpost },
        { label: 'Completaron', data: props.embudo.map((p) => p.completaron), color: C.go },
    ],
}));

const veredictoChart = computed(() => ({
    labels: ['Verde (permitido)', 'Ámbar (revisar)', 'Rojo (bloquea)', 'Desconocido'],
    data: [props.veredictos.verde, props.veredictos.ambar, props.veredictos.rojo, props.veredictos.desconocido],
    colors: [C.go, C.ambar, C.stop, C.suave],
}));

const a11yChart = computed(() => ({
    labels: ['A+ texto', 'Contraste', 'Audio', 'Voz', 'Quechua', 'English'],
    series: [{
        label: 'Usos',
        data: [
            props.accesibilidad.features.aplus,
            props.accesibilidad.features.contrast,
            props.accesibilidad.features.audio,
            props.accesibilidad.features.voice,
            props.accesibilidad.idiomas.qu,
            props.accesibilidad.idiomas.en,
        ],
        color: C.azul,
    }],
}));

const maxAbandono = computed(() => Math.max(1, ...props.abandonoCampos.map((c) => c.total)));
const maxError = computed(() => Math.max(1, ...props.errores.map((c) => c.total)));
const maxTop = computed(() => Math.max(1, ...props.topProductos.map((c) => c.total), ...props.topDesconocidos.map((c) => c.total)));
</script>

<template>
    <Head title="Métricas UX" />
    <PanelLayout titulo="Métricas de UX" subtitulo="Analítica anónima del formulario · solo administración">

        <div class="mb-5 flex flex-wrap items-end gap-3 rounded-card border border-borde bg-white p-4">
            <label class="text-xs font-semibold text-texto-medio">
                Desde
                <input v-model="desde" type="date" class="input-base mt-1 bg-white" />
            </label>
            <label class="text-xs font-semibold text-texto-medio">
                Hasta
                <input v-model="hasta" type="date" class="input-base mt-1 bg-white" />
            </label>
            <button
                type="button"
                class="inline-flex min-h-[48px] items-center gap-2 rounded-input bg-serpost px-5 py-2.5 text-sm font-bold text-white transition hover:bg-serpost-dark"
                @click="aplicarRango"
            >
                <Icon name="search" :size="16" /> Aplicar
            </button>
            <p class="ml-auto self-center text-[11px] text-texto-suave">
                Eventos anónimos y agregados (Ley 29733 · ISO 27001). Nunca se registran nombres, direcciones, DNI ni correos.
            </p>
        </div>

        <div class="mb-5 grid grid-cols-2 gap-3 lg:grid-cols-4">
            <div v-for="k in kpis" :key="k.label" class="rounded-card border border-borde bg-white p-4">
                <div class="flex items-center gap-2 text-texto-medio">
                    <span class="grid h-8 w-8 place-items-center rounded-xs bg-serpost-tint text-serpost">
                        <Icon :name="k.icono" :size="18" />
                    </span>
                    <span class="text-caption font-semibold uppercase tracking-wide">{{ k.label }}</span>
                </div>
                <p class="mt-2 text-display font-extrabold text-texto-fuerte">{{ k.valor }}</p>
                <p class="text-[11px] text-texto-suave">{{ k.nota }}</p>
            </div>
        </div>

        <AppCard titulo="Embudo de los 6 pasos" icono="target" subtitulo="Dónde se quedan los ciudadanos" class="mb-5">
            <GraficoBarras :labels="embudoChart.labels" :series="embudoChart.series" :alto="300" />
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-[11px] uppercase tracking-wide text-texto-suave">
                        <tr class="border-b border-borde">
                            <th class="py-2">Paso</th>
                            <th class="py-2 text-right">Entraron</th>
                            <th class="py-2 text-right">Completaron</th>
                            <th class="py-2 text-right">Abandonaron</th>
                            <th class="py-2 text-right">% caída</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in embudo" :key="p.step" class="border-b border-borde/60">
                            <td class="py-2 font-semibold text-texto-fuerte">{{ p.label }}</td>
                            <td class="py-2 text-right tabular-nums">{{ p.entraron }}</td>
                            <td class="py-2 text-right tabular-nums">{{ p.completaron }}</td>
                            <td class="py-2 text-right tabular-nums">{{ p.abandonaron }}</td>
                            <td class="py-2 text-right">
                                <span
                                    class="rounded-xs px-2 py-0.5 text-xs font-bold tabular-nums"
                                    :class="p.caida_pct >= 30 ? 'bg-stop-tint text-stop' : p.caida_pct >= 15 ? 'bg-ambar-tint text-[#8a6400]' : 'bg-go-tint text-go'"
                                >{{ p.caida_pct }}%</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </AppCard>

        <div class="mb-5 grid grid-cols-1 gap-5 lg:grid-cols-2">
            <AppCard titulo="Abandono por campo" icono="logout" subtitulo="Último campo tocado antes de salir">
                <p v-if="!abandonoCampos.length" class="text-caption text-texto-suave">Sin abandonos registrados en el rango.</p>
                <ul v-else class="space-y-2">
                    <li v-for="c in abandonoCampos" :key="c.field_id + c.step_id">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-semibold text-texto-fuerte">{{ c.field_id }}</span>
                            <span class="text-texto-suave">{{ c.step_id }} · <b class="text-texto-fuerte tabular-nums">{{ c.total }}</b></span>
                        </div>
                        <div class="mt-1 h-2 rounded-full bg-surface2">
                            <div class="h-2 rounded-full bg-stop" :style="{ width: (c.total / maxAbandono * 100) + '%' }" />
                        </div>
                    </li>
                </ul>
            </AppCard>

            <AppCard titulo="Campos con más errores" icono="triangle-alert" subtitulo="Validaciones fallidas (sin el valor)">
                <p v-if="!errores.length" class="text-caption text-texto-suave">Sin errores registrados en el rango.</p>
                <ul v-else class="space-y-2">
                    <li v-for="(c, i) in errores" :key="i">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-semibold text-texto-fuerte">{{ c.field_id }}</span>
                            <span class="text-texto-suave"><code class="text-[11px]">{{ c.error_code }}</code> · <b class="text-texto-fuerte tabular-nums">{{ c.total }}</b></span>
                        </div>
                        <div class="mt-1 h-2 rounded-full bg-surface2">
                            <div class="h-2 rounded-full bg-ambar" :style="{ width: (c.total / maxError * 100) + '%' }" />
                        </div>
                    </li>
                </ul>
            </AppCard>
        </div>

        <AppCard titulo="Verificador “¿Puedo enviarlo?”" icono="shield" subtitulo="Distribución de veredictos y productos consultados" class="mb-5">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="lg:col-span-1">
                    <GraficoDona :labels="veredictoChart.labels" :data="veredictoChart.data" :colors="veredictoChart.colors" :alto="260" />
                </div>
                <div class="lg:col-span-1">
                    <p class="mb-2 text-caption font-bold uppercase tracking-wide text-texto-suave">Top productos consultados</p>
                    <p v-if="!topProductos.length" class="text-caption text-texto-suave">Sin consultas en el rango.</p>
                    <ul v-else class="space-y-1.5">
                        <li v-for="p in topProductos" :key="p.term" class="flex items-center gap-2 text-sm">
                            <span class="w-28 truncate font-semibold text-texto-fuerte">{{ p.term }}</span>
                            <span class="h-2 flex-1 rounded-full bg-surface2">
                                <span class="block h-2 rounded-full bg-serpost" :style="{ width: (p.total / maxTop * 100) + '%' }" />
                            </span>
                            <span class="w-7 text-right text-xs tabular-nums text-texto-medio">{{ p.total }}</span>
                        </li>
                    </ul>
                </div>
                <div class="lg:col-span-1">
                    <p class="mb-2 text-caption font-bold uppercase tracking-wide text-texto-suave">Top “desconocidos” a revisar</p>
                    <p class="mb-2 text-[11px] text-texto-suave">Términos no reconocidos → enriquecer el dataset.</p>
                    <p v-if="!topDesconocidos.length" class="text-caption text-texto-suave">Sin desconocidos en el rango.</p>
                    <ul v-else class="space-y-1.5">
                        <li v-for="p in topDesconocidos" :key="p.term" class="flex items-center gap-2 text-sm">
                            <span class="w-28 truncate font-semibold text-texto-fuerte">{{ p.term }}</span>
                            <span class="h-2 flex-1 rounded-full bg-surface2">
                                <span class="block h-2 rounded-full bg-[#a8b1c6]" :style="{ width: (p.total / maxTop * 100) + '%' }" />
                            </span>
                            <span class="w-7 text-right text-xs tabular-nums text-texto-medio">{{ p.total }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </AppCard>

        <AppCard titulo="Uso de accesibilidad e idiomas" icono="languages" subtitulo="A+, contraste, audio, voz y cambios de idioma">
            <GraficoBarras :labels="a11yChart.labels" :series="a11yChart.series" :alto="260" />
        </AppCard>
    </PanelLayout>
</template>
