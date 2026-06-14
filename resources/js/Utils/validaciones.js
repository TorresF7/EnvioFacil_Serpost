

export function requerido(valor, etiqueta = 'Este campo') {
    const vacio = valor === null || valor === undefined || String(valor).trim() === '';
    return vacio ? `${etiqueta} es obligatorio` : null;
}

export function dni(valor) {
    if (!valor) return 'El DNI es obligatorio';
    return /^\d{8}$/.test(valor) ? null : 'El DNI debe tener 8 dígitos';
}

export function email(valor) {
    if (!valor) return null;
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor) ? null : 'Correo electrónico inválido';
}

export function numeroPositivo(valor, etiqueta = 'El valor') {
    if (valor === '' || valor === null || valor === undefined) return `${etiqueta} es obligatorio`;
    return Number(valor) > 0 ? null : `${etiqueta} debe ser mayor a cero`;
}

export function longitudExacta(valor, largo, etiqueta = 'Este campo') {
    if (!valor) return `${etiqueta} es obligatorio`;
    return String(valor).length === largo ? null : `${etiqueta} debe tener ${largo} caracteres`;
}

export function documento(tipo, numero) {
    if (tipo === 'dni') return dni(numero);
    return requerido(numero, 'El número de documento');
}
