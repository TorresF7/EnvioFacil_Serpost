import { ref, watch } from 'vue';

const NOMINATIM = 'https://nominatim.openstreetmap.org/search';

function mapear(item) {
    const a = item.address ?? {};
    const calle = [a.road, a.house_number].filter(Boolean).join(' ');
    return {
        direccion: calle || item.display_name?.split(',')[0] || '',
        ciudad: a.city || a.town || a.village || a.county || '',
        estado: a.state || a.region || '',
        codigo_postal: a.postcode || '',
        pais: a.country || '',
        lat: Number(item.lat),
        lon: Number(item.lon),
        display: item.display_name,
    };
}

export function useBuscadorDireccion(opciones = {}) {
    const { paisCodigo = null, debounce = 450 } = opciones;

    const consulta = ref('');
    const resultados = ref([]);
    const cargando = ref(false);
    let temporizador = null;

    async function ejecutar(texto) {
        cargando.value = true;
        try {
            const params = new URLSearchParams({
                format: 'jsonv2',
                addressdetails: '1',
                limit: '6',
                q: texto,
            });
            const codigo = typeof paisCodigo === 'function' ? paisCodigo() : paisCodigo;
            if (codigo) params.set('countrycodes', codigo.toLowerCase());

            const respuesta = await fetch(`${NOMINATIM}?${params}`, {
                headers: { 'Accept-Language': 'es' },
            });
            const datos = await respuesta.json();
            resultados.value = Array.isArray(datos) ? datos.map(mapear) : [];
        } catch {
            resultados.value = [];
        } finally {
            cargando.value = false;
        }
    }

    watch(consulta, (texto) => {
        clearTimeout(temporizador);
        if (!texto || texto.trim().length < 4) {
            resultados.value = [];
            return;
        }
        temporizador = setTimeout(() => ejecutar(texto.trim()), debounce);
    });

    function limpiar() {
        resultados.value = [];
    }

    return { consulta, resultados, cargando, limpiar };
}
