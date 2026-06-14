import { ref, watch } from 'vue';

export function useConsultaDni(dniRef, opciones = {}) {
    const { onNombre, debounce = 500 } = opciones;

    const cargando = ref(false);
    const verificado = ref(false);
    let temporizador = null;

    async function consultar(dni) {
        cargando.value = true;
        verificado.value = false;
        try {
            const { data } = await window.axios.get(`/api/reniec/${dni}`);
            if (data?.nombres) {
                verificado.value = true;
                onNombre?.(data.nombres);
            }
        } catch {
            verificado.value = false;
        } finally {
            cargando.value = false;
        }
    }

    watch(dniRef, (dni) => {
        clearTimeout(temporizador);
        verificado.value = false;
        if (!/^\d{8}$/.test(dni || '')) return;
        temporizador = setTimeout(() => consultar(dni), debounce);
    });

    return { cargando, verificado };
}
