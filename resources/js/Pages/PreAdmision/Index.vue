<script setup>
import { computed, onMounted, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { Toaster, toast } from 'vue-sonner';
import { useEnvioStore } from '@/Stores/envio';
import { useWizard } from '@/Composables/useWizard';
import { useI18n } from '@/Composables/useI18n';
import { useTrackingFormulario } from '@/Composables/useTrackingFormulario';
import { validarPaso } from '@/Composables/useValidacionPaso';
import { OFICINAS_FALLBACK } from '@/Utils/constantes';
import AppHeader from '@/Components/AppHeader.vue';
import ProgresoPasos from '@/Components/ui/ProgresoPasos.vue';
import StepperWizard from '@/Components/ui/StepperWizard.vue';
import Icon from '@/Components/ui/Icon.vue';

import Producto from './Producto.vue';
import Destinatario from './Destinatario.vue';
import Contenido from './Contenido.vue';
import Remitente from './Remitente.vue';
import ServicioPago from './ServicioPago.vue';
import Verificacion from './Verificacion.vue';
import CodigoQr from './CodigoQr.vue';
import Seguimiento from './Seguimiento.vue';

const props = defineProps({
    oficinas: { type: Array, default: () => [] },
    precarga: { type: Object, default: null },
});

const store = useEnvioStore();

const raiz = ref(null);
useTrackingFormulario(raiz);

onMounted(() => {
    store.setOficinas(props.oficinas.length ? props.oficinas : OFICINAS_FALLBACK);

    if (props.precarga) store.cargarDesdeReciente(props.precarga);

    const usuario = usePage().props.auth?.user;
    if (usuario) {
        const r = store.remitente;
        if (!r.nombre_completo) r.nombre_completo = usuario.name ?? '';
        if (!r.numero_documento) r.numero_documento = usuario.numero_documento ?? '';
        if (!r.telefono) r.telefono = usuario.telefono ?? '';
        if (!r.email) r.email = usuario.email ?? '';
    }
});

const { pasoActual, guardando, hayArticulosBloqueados } = storeToRefs(store);
const { pasoInfo, pasosVisibles, pasoVisible, enAsistente } = useWizard();
const { t } = useI18n();

const PASOS_COMPONENTES = [
    Producto, Destinatario, Contenido, Remitente,
    ServicioPago, Verificacion, CodigoQr, Seguimiento,
];

const REVISA = 5;
const QR = 6;
const SEGUIMIENTO = 7;

const contenedorPaso = ref(null);
const componenteActual = computed(() => PASOS_COMPONENTES[pasoActual.value]);
const subtitulo = computed(() => t(pasoInfo.value.tituloKey));

const etiquetaSiguiente = computed(() => {
    switch (pasoActual.value) {
        case 4: return t('btn_revisar');
        case REVISA: return t('btn_generar_qr');
        case QR: return t('btn_ver_seguimiento');
        case SEGUIMIENTO: return t('btn_otro_envio');
        default: return t('btn_siguiente');
    }
});

const mostrarAtras = computed(() => pasoActual.value > 0 && pasoActual.value < QR);

function reducirMovimiento() {
    return typeof window !== 'undefined'
        && typeof window.matchMedia === 'function'
        && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function alEntrarPaso() {
    window.scrollTo({ top: 0, behavior: reducirMovimiento() ? 'auto' : 'smooth' });
    const encabezado = document.querySelector('[data-step-heading]');
    if (encabezado) encabezado.focus({ preventScroll: true });
}

function enfocarPrimerCampo() {
    const cont = contenedorPaso.value;
    if (!cont) return;
    const campos = cont.querySelectorAll('input:not([type=hidden]):not([disabled]), select:not([disabled]), textarea:not([disabled])');
    let objetivo = null;
    for (const c of campos) {
        if (!(c.value ?? '').toString().trim()) { objetivo = c; break; }
    }
    objetivo = objetivo || campos[0];
    if (objetivo) {
        objetivo.scrollIntoView({ behavior: reducirMovimiento() ? 'auto' : 'smooth', block: 'center' });
        objetivo.focus({ preventScroll: true });
    }
}

async function alSiguiente() {

    if (pasoActual.value <= 4) {
        const { valido, mensaje } = validarPaso(pasoActual.value, store);
        if (!valido) {
            toast.error(mensaje || t('toast_revisa'));
            enfocarPrimerCampo();
            return;
        }
        if (pasoActual.value === 0) store.derivarAranceles();
        store.avanzar();
        return;
    }
    if (pasoActual.value === REVISA) {
        if (hayArticulosBloqueados.value) {
            toast.error(t('toast_bloqueado'));
            return;
        }
        try {
            await store.guardar();
            toast.success(t('toast_guardado'));
            store.avanzar();
        } catch {
            toast.error(store.errorGuardado || t('toast_error_guardar'));
        }
        return;
    }
    if (pasoActual.value === SEGUIMIENTO) {
        store.reiniciar();
        return;
    }
    store.avanzar();
}
</script>

<template>
    <Head title="Pre-admisión" />
    <div ref="raiz" class="app-shell flex min-h-screen flex-col">
        <AppHeader :titulo="t('brand')" :subtitulo="subtitulo" ancho="wide">

            <ProgresoPasos v-if="enAsistente" :total="pasosVisibles" :actual="pasoVisible" class="md:hidden" />
        </AppHeader>

        <main class="flex-1 px-4 py-4 pb-28 sm:px-6 lg:py-8 lg:pb-10">
            <div
                :class="enAsistente
                    ? 'content-col-wide md:grid md:grid-cols-[220px_minmax(0,700px)] md:justify-center md:gap-8 lg:gap-10'
                    : 'content-col'"
            >

                <aside v-if="enAsistente" class="hidden md:sticky md:top-[72px] md:block md:self-start">
                    <StepperWizard />
                </aside>

                <div ref="contenedorPaso" class="mx-auto w-full" :class="enAsistente ? 'md:max-w-[700px]' : 'max-w-[680px]'">
                    <Transition name="fade" mode="out-in" @after-enter="alEntrarPaso">
                        <component :is="componenteActual" />
                    </Transition>

                    <div
                        class="fixed inset-x-0 bottom-0 z-30 border-t border-borde bg-white lg:static lg:mt-6 lg:border-0 lg:bg-transparent"
                    >
                        <div class="content-col flex gap-3 px-4 py-3 sm:px-6 lg:px-0 lg:py-0">
                            <button
                                v-if="mostrarAtras"
                                type="button"
                                class="inline-flex min-h-[56px] items-center gap-2 rounded-input border border-borde px-5 py-3 text-base font-semibold text-texto-medio transition hover:bg-surface2 active:scale-[.99] lg:min-h-[48px]"
                                @click="store.retroceder()"
                            >
                                <Icon name="arrow-left" :size="18" /> {{ t('btn_atras') }}
                            </button>
                            <button
                                type="button"
                                class="flex min-h-[56px] flex-1 items-center justify-center gap-2 rounded-input bg-serpost px-5 py-3 text-base font-bold text-white transition hover:bg-serpost-dark active:scale-[.99] disabled:cursor-not-allowed disabled:bg-serpost/40 lg:min-h-[48px]"
                                :disabled="guardando"
                                @click="alSiguiente"
                            >
                                <span v-if="guardando" class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white" />
                                {{ guardando ? t('btn_generando') : etiquetaSiguiente }}
                                <Icon v-if="!guardando && pasoActual < REVISA" name="arrow-right" :size="18" />
                            </button>
                        </div>
                    </div>

                    <footer class="mt-6 space-y-1 border-t border-borde pt-3 text-center">
                        <p class="font-mono text-[11px] uppercase tracking-wide text-texto-suave">{{ t('linea_1812') }}</p>
                        <p class="text-[11px] leading-snug text-texto-suave">{{ t('footer_legal') }}</p>
                        <p class="text-[11px] italic leading-snug text-texto-suave">{{ t('footer_qu_nota') }}</p>
                    </footer>
                </div>
            </div>
        </main>

        <Toaster position="top-center" :rich-colors="true" :duration="4500" :offset="16" close-button />
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.18s ease, transform 0.18s ease;
}
.fade-enter-from {
    opacity: 0;
    transform: translateX(8px);
}
.fade-leave-to {
    opacity: 0;
    transform: translateX(-8px);
}

@media (prefers-reduced-motion: reduce) {
    .fade-enter-active,
    .fade-leave-active {
        transition: none;
    }
    .fade-enter-from,
    .fade-leave-to {
        transform: none;
    }
}
</style>
