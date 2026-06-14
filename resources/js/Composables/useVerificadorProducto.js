import { ref, computed } from 'vue';
import { DESCRIPCIONES_VAGAS } from '@/Utils/constantes';
import { distanciaLevenshtein } from '@/Utils/strings';
import { CATEGORIAS, DICCIONARIO, SEVERIDAD, COLOR_POR_ESTADO } from '@/Utils/semaforoNormativa';

const CATEGORIAS_POR_SEVERIDAD = [...CATEGORIAS].sort((a, b) => SEVERIDAD[a.estado] - SEVERIDAD[b.estado]);

const DICCIONARIO_FUZZY = DICCIONARIO.filter((d) => !d.termino.includes(' ') && d.termino.length >= 4);

const VEREDICTO_DESCONOCIDO = {
    categoriaClave: 'desconocido',
    estado: 'permitido',
    color: 'ambar',
    titulo: 'Lo confirman en la oficina',
    entidad: null,
    documento: null,
    que_necesitas: 'En la oficina revisan el detalle de este artículo. Puedes seguir con tu registro.',
    siguiente_paso: 'Verifícalo por subpartida en SUNAT (Mercancías Restringidas y Prohibidas) o en la oficina.',
    base_legal: 'SUNAT – Mercancías Restringidas y Prohibidas.',
    alternativa: null,
    confianza: 'baja',
    capa: 'fallback',
    palabra: null,
};

function normalizar(texto) {
    return (texto || '')
        .toLowerCase()
        .normalize('NFD')
        .replace(/\p{Diacritic}/gu, '')
        .replace(/[^\p{L}\p{N}\s]/gu, ' ')
        .replace(/\s+/g, ' ')
        .trim();
}

function buscarSubstring(texto, lista) {
    return lista.find((palabra) => texto.includes(palabra)) ?? null;
}

const ARTICULOS_INICIALES = /^(un|una|unos|unas|el|la|los|las|mi|mis)\s+/;

export function esDescripcionVaga(descripcion) {
    const texto = normalizar(descripcion).replace(ARTICULOS_INICIALES, '').trim();
    return texto ? DESCRIPCIONES_VAGAS.includes(texto) : false;
}

function veredictoDe(categoria, palabra, confianza, capa) {
    return {
        categoriaClave: categoria.clave,
        estado: categoria.estado,
        color: COLOR_POR_ESTADO[categoria.estado],
        titulo: categoria.titulo,
        entidad: categoria.entidad,
        documento: categoria.documento ?? null,
        que_necesitas: categoria.que_necesitas,
        siguiente_paso: categoria.siguiente_paso,
        base_legal: categoria.base_legal,
        alternativa: categoria.alternativa,
        confianza,
        capa,
        palabra,
    };
}

export function clasificar(descripcion) {
    const texto = normalizar(descripcion);

    if (!texto || texto.length < 2) {

        return { ...VEREDICTO_DESCONOCIDO, categoriaClave: 'vacio', color: 'verde', estado: 'permitido', confianza: 'baja' };
    }

    for (const categoria of CATEGORIAS_POR_SEVERIDAD) {
        const palabra = buscarSubstring(texto, categoria.sinonimos);
        if (palabra) return veredictoDe(categoria, palabra, 'alta', 'local');
    }

    let mejor = null;
    for (const token of texto.split(' ')) {
        if (token.length < 4) continue;
        const umbral = token.length <= 6 ? 1 : 2;
        for (const entrada of DICCIONARIO_FUZZY) {
            const dist = distanciaLevenshtein(token, entrada.termino);
            if (dist > umbral) continue;
            if (
                !mejor ||
                dist < mejor.dist ||
                (dist === mejor.dist && SEVERIDAD[entrada.categoria.estado] < SEVERIDAD[mejor.entrada.categoria.estado])
            ) {
                mejor = { dist, entrada, token };
            }
        }
    }
    if (mejor) return veredictoDe(mejor.entrada.categoria, mejor.entrada.termino, 'media', 'local');

    return { ...VEREDICTO_DESCONOCIDO };
}

export function verificarProducto(descripcion) {
    const v = clasificar(descripcion);
    return { estado: v.estado, palabra: v.palabra, entidad: v.entidad, documento: v.documento };
}

export function requisitoArticulo(descripcion) {
    const v = clasificar(descripcion);
    if (v.estado !== 'restringido') return null;
    return { entidad: v.entidad, documento: v.documento };
}

export function documentosRequeridos(articulos = []) {
    const vistos = new Set();
    const lista = [];
    for (const a of articulos) {
        const req = requisitoArticulo(a?.descripcion ?? '');
        if (!req) continue;
        const clave = req.documento ?? req.entidad ?? '';
        if (vistos.has(clave)) continue;
        vistos.add(clave);
        lista.push(req);
    }
    return lista;
}

export function useVerificadorProducto() {
    const consulta = ref('');

    const resultado = computed(() => {
        if (consulta.value.trim().length < 3) return null;
        return clasificar(consulta.value);
    });

    return { consulta, resultado };
}
