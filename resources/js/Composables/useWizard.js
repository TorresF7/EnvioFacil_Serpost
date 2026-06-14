import { computed } from 'vue';
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';

const PASOS = [
    { indice: 0, clave: 'producto', tituloKey: 'paso_producto' },
    { indice: 1, clave: 'destino', tituloKey: 'paso_destino' },
    { indice: 2, clave: 'contenido', tituloKey: 'paso_contenido' },
    { indice: 3, clave: 'remitente', tituloKey: 'paso_remitente' },
    { indice: 4, clave: 'servicio', tituloKey: 'paso_servicio' },
    { indice: 5, clave: 'revisa', tituloKey: 'paso_revisa' },
    { indice: 6, clave: 'qr', tituloKey: 'paso_qr' },
    { indice: 7, clave: 'seguimiento', tituloKey: 'paso_seguimiento' },
];

const PASOS_VISIBLES = 6;

export function useWizard() {
    const store = useEnvioStore();
    const { pasoActual, puedeAvanzar } = storeToRefs(store);

    const pasos = PASOS;
    const pasoInfo = computed(() => PASOS[pasoActual.value]);
    const esPrimero = computed(() => pasoActual.value === 0);
    const esUltimo = computed(() => pasoActual.value === PASOS.length - 1);

    const enAsistente = computed(() => pasoActual.value < PASOS_VISIBLES);
    const pasoVisible = computed(() => Math.min(pasoActual.value, PASOS_VISIBLES - 1));
    const progreso = computed(() => Math.round(((pasoVisible.value + 1) / PASOS_VISIBLES) * 100));

    return {
        pasos,
        pasosVisibles: PASOS_VISIBLES,
        pasoActual,
        pasoInfo,
        puedeAvanzar,
        esPrimero,
        esUltimo,
        enAsistente,
        pasoVisible,
        progreso,
        avanzar: () => store.avanzar(),
        retroceder: () => store.retroceder(),
        irA: (i) => store.irA(i),
    };
}
