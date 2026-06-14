import { computed, unref } from 'vue';

const DIVISOR_VOLUMETRICO = 6000;

export function usePesoCalculador(paquete) {
    const datos = () => unref(paquete);

    const pesoVolumetrico = computed(() => {
        const { largo_cm: l, ancho_cm: a, alto_cm: h } = datos();
        if (!l || !a || !h) return 0;
        return Math.round(((l * a * h) / DIVISOR_VOLUMETRICO) * 1000) / 1000;
    });

    const pesoFacturable = computed(() => {
        const real = Number(datos().peso_bruto) || 0;
        return Math.max(real, pesoVolumetrico.value);
    });

    const cobraVolumetrico = computed(() => pesoVolumetrico.value > (Number(datos().peso_bruto) || 0));

    return { pesoVolumetrico, pesoFacturable, cobraVolumetrico };
}
