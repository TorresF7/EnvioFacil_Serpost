

const ENDPOINT = '/api/analytics/events';
const MAX_LOTE = 15;
const DEBOUNCE_MS = 2500;

const enNavegador = typeof window !== 'undefined';

let sessionId = null;
let cola = [];
let timer = null;
let contextoStep = null;
let listenersListos = false;

function generarId() {
    if (enNavegador && window.crypto?.randomUUID) return window.crypto.randomUUID();
    return 'sx-' + Math.random().toString(36).slice(2) + Date.now().toString(36);
}

function obtenerSessionId() {
    if (sessionId) return sessionId;
    if (!enNavegador) return (sessionId = generarId());
    try {
        sessionId = window.sessionStorage.getItem('ux_sid');
        if (!sessionId) {
            sessionId = generarId();
            window.sessionStorage.setItem('ux_sid', sessionId);
        }
    } catch {
        sessionId = generarId();
    }
    return sessionId;
}

export function dispositivo() {
    if (!enNavegador) return 'desktop';
    const coarse = window.matchMedia?.('(pointer: coarse)')?.matches;
    return coarse || window.innerWidth < 768 ? 'mobile' : 'desktop';
}

export function setStepContext(stepId) {
    contextoStep = stepId ?? null;
}

function asegurarListeners() {
    if (listenersListos || !enNavegador) return;
    listenersListos = true;
    const flushBeacon = () => enviar(true);
    window.addEventListener('pagehide', flushBeacon);
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden') flushBeacon();
    });
}

export function track(type, payload = {}) {
    if (!type) return;
    asegurarListeners();
    const { step_id, field_id, duration_ms, meta } = payload;
    cola.push({
        session_id: obtenerSessionId(),
        type,
        step_id: step_id ?? contextoStep ?? null,
        field_id: field_id ?? null,
        duration_ms: Number.isFinite(duration_ms) ? Math.round(duration_ms) : null,
        ts: Date.now(),
        meta: meta ?? null,
    });
    if (cola.length >= MAX_LOTE) enviar();
    else programarEnvio();
}

export function flush() {
    enviar(true);
}

function programarEnvio() {
    if (timer) return;
    timer = setTimeout(() => enviar(), DEBOUNCE_MS);
}

function enviar(usarBeacon = false) {
    if (timer) {
        clearTimeout(timer);
        timer = null;
    }
    if (!cola.length || !enNavegador) return;

    const lote = cola;
    cola = [];
    const cuerpo = JSON.stringify({ events: lote });

    if (usarBeacon && navigator.sendBeacon) {
        const blob = new Blob([cuerpo], { type: 'application/json' });
        const ok = navigator.sendBeacon(ENDPOINT, blob);
        if (!ok) cola = lote.concat(cola);
        return;
    }

    fetch(ENDPOINT, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: cuerpo,
        keepalive: true,
        credentials: 'same-origin',
    }).catch(() => {
            });
}

function terminoProducto(texto) {
    const limpio = (texto || '')
        .toLowerCase()
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .replace(/[^a-z0-9\s]/g, ' ')
        .replace(/\s+/g, ' ')
        .trim();
    if (!limpio) return null;
    const palabras = limpio.split(' ').filter((w) => w.length > 2).slice(0, 3);
    return palabras.length ? palabras.join(' ').slice(0, 40) : null;
}

let verifTimer = null;
let verifPendiente = null;

export function trackVerificador(veredicto, descripcion = '') {
    if (!veredicto || veredicto.categoriaClave === 'vacio') return;
    verifPendiente = { veredicto, descripcion };
    if (verifTimer) clearTimeout(verifTimer);
    verifTimer = setTimeout(() => {
        const { veredicto: v, descripcion: d } = verifPendiente || {};
        verifPendiente = null;
        if (!v) return;
        const esDesconocido = v.categoriaClave === 'desconocido';
        track('verifier_query', {
            meta: {
                resolved_category: v.categoriaClave,
                verdict: esDesconocido ? 'desconocido' : v.color || 'ambar',

                term: esDesconocido ? terminoProducto(d) : v.palabra ?? null,
                needs_review: esDesconocido,
            },
        });
    }, 700);
}

export function trackA11y(feature) {
    track('a11y_toggle', { meta: { feature } });
}

export function trackIdioma(lang) {
    track('lang_change', { meta: { lang } });
}
