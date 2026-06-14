

import { onBeforeUnmount, onMounted, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import { track, setStepContext, flush, dispositivo } from '@/Composables/useAnalytics';

const STEP_KEYS = ['producto', 'destino', 'contenido', 'remitente', 'servicio', 'revisa', 'qr', 'seguimiento'];
const PRIMER_CIERRE = 6;

const CAMPOS = new Set(['INPUT', 'SELECT', 'TEXTAREA']);

function slug(texto) {
    return (texto || '')
        .toLowerCase()
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .replace(/[^a-z0-9]+/g, '_')
        .replace(/^_+|_+$/g, '')
        .slice(0, 48) || null;
}

function fieldIdDe(el) {
    if (!el || !CAMPOS.has(el.tagName)) return null;

    const conData = el.closest?.('[data-field]');
    if (conData?.dataset?.field) return conData.dataset.field;

    if (el.name) return slug(el.name);

    const etiqueta = el.closest?.('label') || el.closest?.('.mb-4')?.querySelector?.('label');
    if (etiqueta?.textContent) return slug(etiqueta.textContent);

    if (el.getAttribute?.('aria-label')) return slug(el.getAttribute('aria-label'));
    if (el.getAttribute?.('placeholder')) return slug(el.getAttribute('placeholder'));

    return null;
}

export function useTrackingFormulario(contenedorRef) {
    const store = useEnvioStore();
    const { pasoActual } = storeToRefs(store);

    let inicioPaso = Date.now();
    let inicioSesion = Date.now();
    let ultimoCampo = null;
    let foco = null;
    let completado = false;
    let abandonoEmitido = false;
    let quitarRouter = null;

    const stepIdDe = (i) => STEP_KEYS[i] ?? null;

    function emitirAbandono() {
        if (completado || abandonoEmitido) return;
        const i = pasoActual.value;
        if (i >= PRIMER_CIERRE) return;
        abandonoEmitido = true;
        track('step_abandon', { step_id: stepIdDe(i), field_id: ultimoCampo });
    }

    function alOcultar() {
        if (typeof document !== 'undefined' && document.visibilityState !== 'hidden') return;
        emitirAbandono();
        flush();
    }

    function alSalir() {
        emitirAbandono();
        flush();
    }

    function onFocusIn(e) {
        const fid = fieldIdDe(e.target);
        if (!fid) return;
        foco = { field: fid, ts: Date.now() };
        track('field_focus', { field_id: fid });
    }

    function onFocusOut(e) {
        const fid = fieldIdDe(e.target);
        if (!fid) return;
        const dur = foco && foco.field === fid ? Date.now() - foco.ts : undefined;
        ultimoCampo = fid;
        foco = null;
        track('field_blur', { field_id: fid, duration_ms: dur });

        const cont = e.target.closest?.('[data-error-code]');
        const code = cont?.getAttribute?.('data-error-code');
        if (code) track('field_error', { field_id: fid, meta: { error_code: code } });
    }

    watch(pasoActual, (nuevo, viejo) => {
        const reinicio = nuevo === 0 && viejo >= PRIMER_CIERRE;
        if (reinicio) {
            inicioSesion = Date.now();
            completado = false;
            abandonoEmitido = false;
        } else if (nuevo > viejo) {
            if (viejo < PRIMER_CIERRE) {
                track('step_complete', { step_id: stepIdDe(viejo), duration_ms: Date.now() - inicioPaso });
            }
        } else if (nuevo < viejo) {
            track('back_nav', { step_id: stepIdDe(nuevo) });
        }

        inicioPaso = Date.now();
        ultimoCampo = null;
        setStepContext(stepIdDe(nuevo));
        track('step_enter', { step_id: stepIdDe(nuevo) });

        if (nuevo === PRIMER_CIERRE && !completado) {
            completado = true;
            track('completion', { duration_ms: Date.now() - inicioSesion, meta: { device: dispositivo() } });
        }
    });

    onMounted(() => {
        inicioPaso = Date.now();
        inicioSesion = Date.now();
        setStepContext(stepIdDe(pasoActual.value));
        track('step_enter', { step_id: stepIdDe(pasoActual.value) });

        const raiz = contenedorRef?.value;
        if (raiz) {
            raiz.addEventListener('focusin', onFocusIn);
            raiz.addEventListener('focusout', onFocusOut);
        }
        window.addEventListener('pagehide', alSalir);
        document.addEventListener('visibilitychange', alOcultar);

        quitarRouter = router.on('before', alSalir);
    });

    onBeforeUnmount(() => {
        const raiz = contenedorRef?.value;
        if (raiz) {
            raiz.removeEventListener('focusin', onFocusIn);
            raiz.removeEventListener('focusout', onFocusOut);
        }
        window.removeEventListener('pagehide', alSalir);
        document.removeEventListener('visibilitychange', alOcultar);
        if (typeof quitarRouter === 'function') quitarRouter();
        flush();
    });
}
