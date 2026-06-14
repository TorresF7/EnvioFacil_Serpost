<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import PanelLayout from '@/Components/PanelLayout.vue';
import EstadoBadge from '@/Components/ui/EstadoBadge.vue';
import AppCard from '@/Components/ui/AppCard.vue';
import AppAlert from '@/Components/ui/AppAlert.vue';
import AppButton from '@/Components/ui/AppButton.vue';
import ComparadorFormulario from '@/Components/forms/ComparadorFormulario.vue';
import GestorDocumentos from '@/Components/forms/GestorDocumentos.vue';

const props = defineProps({
    envio: { type: Object, required: true },
    seguimientos: { type: Array, default: () => [] },
    estados: { type: Array, default: () => [] },
    servicios: { type: Array, default: () => [] },
    comparador: { type: Object, default: () => ({}) },
});

const page = usePage();
const flash = computed(() => page.props.flash?.success);
const base = computed(() => `/panel/solicitud/${props.envio.codigo}`);

const nuevoEstado = ref(props.envio.estado);

function cambiarServicio(servicio) {
    router.patch(`${base.value}/servicio`, { tipo_servicio: servicio }, { preserveScroll: true });
}
function actualizarEstado() {
    router.patch(`${base.value}/estado`, { estado: nuevoEstado.value }, { preserveScroll: true });
}
function procesar() {
    router.patch(`${base.value}/procesar`, {}, { preserveScroll: true });
}
</script>

<template>
    <Head :title="`Solicitud ${envio.codigo}`" />
    <PanelLayout :titulo="`Solicitud ${envio.codigo}`" subtitulo="Detalle y gestión del envío">
        <div class="mb-4 flex items-center justify-between">
            <Link href="/panel" class="text-sm font-semibold text-serpost-azul">← Volver a la bandeja</Link>
            <Link :href="`/panel/solicitud/${envio.codigo}/editar`" class="rounded-input bg-serpost-azul px-4 py-2 text-sm font-semibold text-white">
                ✏️ Editar datos
            </Link>
        </div>

        <AppAlert v-if="flash" tipo="success" class="mb-4">{{ flash }}</AppAlert>

        <div class="grid gap-5 lg:grid-cols-3">

            <div class="space-y-4 lg:col-span-2">
                <AppCard>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-2xl font-extrabold text-serpost-azul">{{ envio.codigo }}</p>
                            <p class="text-sm text-texto-medio">{{ envio.servicio_label }} · {{ envio.formulario }} · {{ envio.pais_destino }}</p>
                            <p class="text-xs text-texto-suave">Registrado: {{ envio.creado }}<span v-if="envio.cliente"> · Cliente: {{ envio.cliente.name }}</span></p>
                        </div>
                        <EstadoBadge :estado="envio.estado" :label="envio.estado_label" />
                    </div>
                </AppCard>

                <div class="grid gap-4 md:grid-cols-2">
                    <AppCard titulo="Remitente" icono="👤">
                        <p class="text-sm font-semibold">{{ envio.remitente?.nombre_completo }}</p>
                        <p class="text-xs text-texto-medio">
                            {{ envio.remitente?.tipo_documento?.toUpperCase?.() }}: {{ envio.remitente?.numero_documento }}<br />
                            {{ envio.remitente?.direccion }}, {{ envio.remitente?.ciudad }}<br />
                            📞 {{ envio.remitente?.telefono }}
                        </p>
                    </AppCard>
                    <AppCard titulo="Destinatario" icono="🎯">
                        <p class="text-sm font-semibold">{{ envio.destinatario?.nombre_completo }}</p>
                        <p class="text-xs text-texto-medio">
                            {{ envio.destinatario?.direccion }}, {{ envio.destinatario?.ciudad }}<br />
                            {{ envio.destinatario?.codigo_postal }} · {{ envio.destinatario?.pais }}
                        </p>
                    </AppCard>
                </div>

                <AppCard titulo="Artículos" icono="📦">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-borde text-left text-xs text-texto-medio">
                                <th class="py-1">Descripción</th><th>Cant.</th><th>Peso(g)</th><th>Valor</th><th>HS</th><th>Categoría</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="a in envio.articulos" :key="a.id" class="border-b border-borde/60">
                                <td class="py-1.5">{{ a.descripcion }}</td>
                                <td>{{ a.cantidad }}</td>
                                <td>{{ a.peso_neto_gramos }}</td>
                                <td>{{ a.valor }} {{ envio.divisa }}</td>
                                <td>{{ a.codigo_hs || '—' }}</td>
                                <td>{{ a.categoria_icono }} {{ a.categoria_label }}</td>
                            </tr>
                        </tbody>
                    </table>
                </AppCard>

                <AppCard titulo="Documentos y certificados" icono="📎">
                    <GestorDocumentos :codigo="envio.codigo" :documentos="envio.documentos ?? []" />
                </AppCard>

                <AppCard titulo="Trazabilidad" icono="🧭">
                    <ol class="space-y-2">
                        <li v-for="(s, i) in seguimientos" :key="i" class="flex gap-3 text-sm">
                            <span class="font-mono text-xs text-texto-suave">{{ s.fecha }}</span>
                            <span class="font-semibold text-texto-fuerte">{{ s.label }}</span>
                        </li>
                    </ol>
                </AppCard>
            </div>

            <div class="space-y-4">
                <AppCard titulo="Procesar en ventanilla" icono="✅">
                    <p class="mb-2 text-xs text-texto-medio">Confirma la admisión del envío tras verificar contenido y embalaje.</p>
                    <AppButton variante="verde" bloque @click="procesar">Admitir y procesar</AppButton>
                    <a :href="envio.descargar_url" target="_blank" class="mt-2 block rounded-input border border-borde py-2 text-center text-xs font-semibold text-serpost-azul">
                        ⬇️ Descargar {{ envio.formulario }}
                    </a>
                    <a :href="envio.rotulado_url" target="_blank" class="mt-2 block rounded-input border border-borde py-2 text-center text-xs font-semibold text-serpost-azul">
                        🏷️ Descargar rótulo (PDF)
                    </a>
                </AppCard>

                <AppCard titulo="Reclasificar formulario" icono="🔄" subtitulo="Ej: esto no es paquete pequeño, es encomienda">
                    <ComparadorFormulario
                        :servicio-actual="envio.tipo_servicio"
                        :datos="comparador"
                        interactivo
                        @cambiar="cambiarServicio"
                    />
                </AppCard>

                <AppCard titulo="Actualizar estado" icono="📍">
                    <select v-model="nuevoEstado" class="input-base mb-2">
                        <option v-for="e in estados" :key="e.valor" :value="e.valor">{{ e.label }}</option>
                    </select>
                    <AppButton variante="azul" bloque @click="actualizarEstado">Guardar estado</AppButton>
                </AppCard>
            </div>
        </div>
    </PanelLayout>
</template>
