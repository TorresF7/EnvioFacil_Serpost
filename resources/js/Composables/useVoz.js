import { ref } from 'vue';
import { trackA11y } from '@/Composables/useAnalytics';

const LOCALE = { es: 'es-PE', en: 'en-US' };

const REGIONES_PREFERIDAS = {
    es: ['es-pe', 'es-419', 'es-mx', 'es-us', 'es-co', 'es-ar', 'es-cl', 'es-es'],
    en: ['en-us', 'en-gb', 'en-au'],
};

let vocesCache = [];
const vozElegidaPorLocale = {};

function cargarVoces() {
    if (typeof window === 'undefined' || !('speechSynthesis' in window)) return;
    const voces = window.speechSynthesis.getVoices();
    if (voces && voces.length) {
        vocesCache = voces;

        for (const k of Object.keys(vozElegidaPorLocale)) delete vozElegidaPorLocale[k];
    }
}

if (typeof window !== 'undefined' && 'speechSynthesis' in window) {
    cargarVoces();

    window.speechSynthesis.onvoiceschanged = cargarVoces;
}

function elegirVoz(locale) {
    if (locale in vozElegidaPorLocale) return vozElegidaPorLocale[locale];
    if (!vocesCache.length) cargarVoces();

    const base = locale.slice(0, 2).toLowerCase();
    const localeLc = locale.toLowerCase();
    const regiones = REGIONES_PREFERIDAS[base] || [localeLc];

    const candidatas = vocesCache.filter((v) => (v.lang || '').toLowerCase().startsWith(base));
    if (!candidatas.length) {
        vozElegidaPorLocale[locale] = null;
        return null;
    }

    const score = (v) => {
        const nombre = (v.name || '').toLowerCase();
        const vlang = (v.lang || '').toLowerCase().replace('_', '-');
        let s = 0;

        if (/natural|neural/.test(nombre)) s += 100;
        if (/google/.test(nombre)) s += 60;
        if (v.localService === false) s += 15;

        if (vlang === localeLc) s += 50;
        const idx = regiones.indexOf(vlang);
        if (idx !== -1) s += 40 - idx * 3;

        return s;
    };

    const mejor = candidatas.reduce((a, b) => (score(b) > score(a) ? b : a));
    vozElegidaPorLocale[locale] = mejor;
    return mejor;
}

export function useVoz() {
    const hablando = ref(false);
    const escuchando = ref(false);
    const soportaSintesis = typeof window !== 'undefined' && 'speechSynthesis' in window;
    const soportaDictado =
        typeof window !== 'undefined' &&
        ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window);

    let keepAlive = null;

    function pararKeepAlive() {
        if (keepAlive) {
            clearInterval(keepAlive);
            keepAlive = null;
        }
    }

        function leer(texto, lang = 'es') {
        if (!soportaSintesis || !texto) return false;
        const locale = LOCALE[lang];
        if (!locale) return false;

        window.speechSynthesis.cancel();
        const u = new SpeechSynthesisUtterance(texto);
        u.lang = locale;
        const voz = elegirVoz(locale);
        if (voz) u.voice = voz;
        u.rate = 1.0;
        u.pitch = 1.0;
        u.onstart = () => {
            hablando.value = true;

            pararKeepAlive();
            keepAlive = setInterval(() => {
                if (!window.speechSynthesis.speaking) {
                    pararKeepAlive();
                    return;
                }
                window.speechSynthesis.pause();
                window.speechSynthesis.resume();
            }, 10000);
        };
        u.onend = () => {
            hablando.value = false;
            pararKeepAlive();
        };
        u.onerror = () => {
            hablando.value = false;
            pararKeepAlive();
        };
        window.speechSynthesis.speak(u);
        trackA11y('audio');
        return true;
    }

    function detener() {
        if (soportaSintesis) window.speechSynthesis.cancel();
        hablando.value = false;
        pararKeepAlive();
    }

        function dictar(lang = 'es') {
        return new Promise((resolve) => {
            const locale = LOCALE[lang];
            if (!soportaDictado || !locale) return resolve(null);

            const Reco = window.SpeechRecognition || window.webkitSpeechRecognition;
            const reco = new Reco();
            reco.lang = locale;
            reco.interimResults = false;
            reco.maxAlternatives = 1;
            escuchando.value = true;
            trackA11y('voice');
            reco.onresult = (e) => resolve(e.results[0][0].transcript);
            reco.onerror = () => resolve(null);
            reco.onend = () => (escuchando.value = false);
            reco.start();
        });
    }

    return { hablando, escuchando, soportaSintesis, soportaDictado, leer, detener, dictar };
}
