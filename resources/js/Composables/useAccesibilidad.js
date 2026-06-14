import { reactive, computed, watch } from 'vue';
import { trackA11y } from '@/Composables/useAnalytics';

const SIZES = ['base', 'lg', 'xl'];
const estado = reactive({ size: 'base', contrast: false });

function aplicar() {
    if (typeof document === 'undefined') return;
    const html = document.documentElement;
    if (estado.size === 'base') html.removeAttribute('data-size');
    else html.setAttribute('data-size', estado.size);
    if (estado.contrast) html.setAttribute('data-contrast', 'hi');
    else html.removeAttribute('data-contrast');
}

watch(estado, aplicar, { deep: true });

export function useAccesibilidad() {
    const size = computed(() => estado.size);
    const contrast = computed(() => estado.contrast);

        function ciclarTexto() {
        const i = SIZES.indexOf(estado.size);
        estado.size = SIZES[(i + 1) % SIZES.length];
        trackA11y('aplus');
    }

    function alternarContraste() {
        estado.contrast = !estado.contrast;
        trackA11y('contrast');
    }

    return { size, contrast, ciclarTexto, alternarContraste };
}
