import { MONEDAS } from './constantes';

export function formatearPeso(kg) {
    const valor = Number(kg) || 0;
    return `${valor.toLocaleString('es-PE', { maximumFractionDigits: 3 })} kg`;
}

export function formatearGramos(gramos) {
    const g = Number(gramos) || 0;
    if (g >= 1000) return `${(g / 1000).toLocaleString('es-PE', { maximumFractionDigits: 3 })} kg`;
    return `${g.toLocaleString('es-PE')} g`;
}

export function formatearMoneda(monto, divisa = 'USD') {
    const moneda = MONEDAS.find((m) => m.codigo === divisa);
    const simbolo = moneda?.simbolo ?? '';
    const valor = (Number(monto) || 0).toLocaleString('es-PE', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
    return `${simbolo} ${valor}`.trim();
}

export function capitalizar(texto) {
    if (!texto) return '';
    return texto.charAt(0).toUpperCase() + texto.slice(1);
}
