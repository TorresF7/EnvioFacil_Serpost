import { reactive, computed } from 'vue';
import { STRINGS, IDIOMAS } from '@/Utils/strings';
import { trackIdioma } from '@/Composables/useAnalytics';

const estado = reactive({ lang: 'es' });

export function t(clave, respaldo) {
    const dicc = STRINGS[estado.lang] || STRINGS.es;
    return dicc[clave] ?? STRINGS.es[clave] ?? respaldo ?? clave;
}

export function useI18n() {
    const lang = computed(() => estado.lang);

    function setLang(codigo) {
        if (STRINGS[codigo]) {
            const cambio = estado.lang !== codigo;
            estado.lang = codigo;
            if (typeof document !== 'undefined') {
                document.documentElement.lang = codigo;
            }
            if (cambio) trackIdioma(codigo);
        }
    }

    return { lang, idiomas: IDIOMAS, setLang, t };
}
