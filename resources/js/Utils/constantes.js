

export const PAISES = [
    { codigo: 'US', nombre: 'Estados Unidos', bandera: '🇺🇸', dial: '+1' },
    { codigo: 'ES', nombre: 'España', bandera: '🇪🇸', dial: '+34' },
    { codigo: 'AR', nombre: 'Argentina', bandera: '🇦🇷', dial: '+54' },
    { codigo: 'CL', nombre: 'Chile', bandera: '🇨🇱', dial: '+56' },
    { codigo: 'BR', nombre: 'Brasil', bandera: '🇧🇷', dial: '+55' },
    { codigo: 'MX', nombre: 'México', bandera: '🇲🇽', dial: '+52' },
    { codigo: 'CO', nombre: 'Colombia', bandera: '🇨🇴', dial: '+57' },
    { codigo: 'EC', nombre: 'Ecuador', bandera: '🇪🇨', dial: '+593' },
    { codigo: 'BO', nombre: 'Bolivia', bandera: '🇧🇴', dial: '+591' },
    { codigo: 'CA', nombre: 'Canadá', bandera: '🇨🇦', dial: '+1' },
    { codigo: 'IT', nombre: 'Italia', bandera: '🇮🇹', dial: '+39' },
    { codigo: 'FR', nombre: 'Francia', bandera: '🇫🇷', dial: '+33' },
    { codigo: 'DE', nombre: 'Alemania', bandera: '🇩🇪', dial: '+49' },
    { codigo: 'GB', nombre: 'Reino Unido', bandera: '🇬🇧', dial: '+44' },
    { codigo: 'NL', nombre: 'Países Bajos', bandera: '🇳🇱', dial: '+31' },
    { codigo: 'CH', nombre: 'Suiza', bandera: '🇨🇭', dial: '+41' },
    { codigo: 'JP', nombre: 'Japón', bandera: '🇯🇵', dial: '+81' },
    { codigo: 'CN', nombre: 'China', bandera: '🇨🇳', dial: '+86' },
    { codigo: 'KR', nombre: 'Corea del Sur', bandera: '🇰🇷', dial: '+82' },
    { codigo: 'AU', nombre: 'Australia', bandera: '🇦🇺', dial: '+61' },
    { codigo: 'PT', nombre: 'Portugal', bandera: '🇵🇹', dial: '+351' },
    { codigo: 'BE', nombre: 'Bélgica', bandera: '🇧🇪', dial: '+32' },
    { codigo: 'SE', nombre: 'Suecia', bandera: '🇸🇪', dial: '+46' },
    { codigo: 'UY', nombre: 'Uruguay', bandera: '🇺🇾', dial: '+598' },
    { codigo: 'PY', nombre: 'Paraguay', bandera: '🇵🇾', dial: '+595' },
    { codigo: 'VE', nombre: 'Venezuela', bandera: '🇻🇪', dial: '+58' },
    { codigo: 'CR', nombre: 'Costa Rica', bandera: '🇨🇷', dial: '+506' },
    { codigo: 'PA', nombre: 'Panamá', bandera: '🇵🇦', dial: '+507' },
    { codigo: 'IN', nombre: 'India', bandera: '🇮🇳', dial: '+91' },
    { codigo: 'RU', nombre: 'Rusia', bandera: '🇷🇺', dial: '+7' },
    { codigo: 'AE', nombre: 'Emiratos Árabes', bandera: '🇦🇪', dial: '+971' },
    { codigo: 'NZ', nombre: 'Nueva Zelanda', bandera: '🇳🇿', dial: '+64' },
];

export const MONEDAS = [
    { codigo: 'USD', nombre: 'Dólar estadounidense', simbolo: '$' },
    { codigo: 'EUR', nombre: 'Euro', simbolo: '€' },
    { codigo: 'PEN', nombre: 'Sol peruano', simbolo: 'S/' },
    { codigo: 'GBP', nombre: 'Libra esterlina', simbolo: '£' },
    { codigo: 'JPY', nombre: 'Yen japonés', simbolo: '¥' },
    { codigo: 'BRL', nombre: 'Real brasileño', simbolo: 'R$' },
    { codigo: 'ARS', nombre: 'Peso argentino', simbolo: '$' },
    { codigo: 'CLP', nombre: 'Peso chileno', simbolo: '$' },
    { codigo: 'MXN', nombre: 'Peso mexicano', simbolo: '$' },
    { codigo: 'CAD', nombre: 'Dólar canadiense', simbolo: '$' },
    { codigo: 'AUD', nombre: 'Dólar australiano', simbolo: '$' },
];

export const SERVICIOS = [
    {
        valor: 'pp',
        nombre: 'Pequeño Paquete',
        formulario: 'CN22',
        pesoMaximo: 2,
        icono: 'package',
        resumen: 'El más económico',
        descripcion: 'Hasta 2 kg. Ideal para envíos pequeños y económicos.',
        tiempo: '15 a 30 días',
    },
    {
        valor: 'ems',
        nombre: 'EMS Express',
        formulario: 'CN23 EMS',
        pesoMaximo: 30,
        icono: 'zap',
        resumen: 'El más rápido, con seguimiento',
        descripcion: 'Hasta 30 kg. Entrega rápida con seguimiento detallado.',
        tiempo: '5 a 10 días',
    },
    {
        valor: 'encomienda',
        nombre: 'Encomienda',
        formulario: 'CP72',
        pesoMaximo: 31.5,
        icono: 'truck',
        resumen: 'Para paquetes grandes',
        descripcion: 'Hasta 31.5 kg. La mejor opción para paquetes voluminosos.',
        tiempo: '20 a 45 días',
    },
];

export const UMBRAL_VALOR_CN22_PEN = 1500;

export const TIPO_CAMBIO_A_PEN = {
    PEN: 1, USD: 3.75, EUR: 4.05, GBP: 4.75, JPY: 0.025, BRL: 0.65,
    ARS: 0.004, CLP: 0.004, MXN: 0.20, CAD: 2.70, AUD: 2.45,
};

export const UMBRAL_REGALO_USD = 100;
export const UMBRAL_REGALO_KG = 5;

export const DESCRIPCIONES_VAGAS = [
    'regalo', 'regalos', 'gift', 'mercaderia', 'mercancia', 'mercaderias', 'mercancias',
    'varios', 'cosas', 'objetos', 'articulo', 'articulos', 'producto', 'productos',
    'otros', 'encomienda', 'paquete', 'envio',
];

export const NATURALEZAS = [
    { valor: 'regalo', label: 'Regalo' },
    { valor: 'venta', label: 'Venta de bienes' },
    { valor: 'muestra', label: 'Muestra comercial' },
    { valor: 'documentos', label: 'Documentos' },
    { valor: 'devolucion', label: 'Devolución' },
    { valor: 'variado', label: 'Contenido variado' },
    { valor: 'otro', label: 'Otro' },
];

export const TIPOS_DOCUMENTO = [
    { valor: 'dni', label: 'DNI' },
    { valor: 'pasaporte', label: 'Pasaporte' },
    { valor: 'ce', label: 'Carnet de Extranjería' },
    { valor: 'cip', label: 'CIP' },
];

export const TIPOS_ENVIO = [
    { valor: 'mercaderia', label: 'Mercadería' },
    { valor: 'documento', label: 'Documentos' },
];

export const TIPOS_DOC_COMERCIAL = [
    { valor: 'factura', label: 'Factura' },
    { valor: 'boleta', label: 'Boleta de venta' },
    { valor: 'guia', label: 'Guía de remisión' },
    { valor: 'otro', label: 'Otro' },
];

export const ENTIDADES_CERTIFICADO = [
    'SENASA', 'INRENA', 'Ministerio de Cultura', 'Receta médica', 'Otro',
];

export const INSTRUCCIONES_NO_ENTREGA = [
    { valor: 'devolver', label: 'Devolver al remitente' },
    { valor: 'abandonar', label: 'Tratar como abandonado' },
    { valor: 'redirigir', label: 'Redirigir a otra dirección' },
];

export const MODALIDADES = [
    { valor: 'prioritario', label: 'Internacional Prioritario' },
    { valor: 'economico', label: 'Internacional Económico' },
];

export const VIAS = [
    { valor: 'aereo', label: 'Por aire' },
    { valor: 'superficie', label: 'Por superficie (S.A.L.)' },
];

export const OFICINAS_FALLBACK = [
    { codigo: 'TVALLE', nombre: 'Tomás Valle', ciudad: 'Lima', latitud: -12.0177, longitud: -77.0598 },
    { codigo: 'SISIDRO', nombre: 'San Isidro', ciudad: 'Lima', latitud: -12.0972, longitud: -77.0365 },
    { codigo: 'MIRAFLORES', nombre: 'Miraflores', ciudad: 'Lima', latitud: -12.1211, longitud: -77.0297 },
];

export { PROHIBIDOS, PELIGROSOS, RESTRINGIDOS, PERMITIDOS_CONOCIDOS } from '@/Utils/semaforoNormativa';

export const ARTICULOS_COMUNES = [
    { nombre: 'Ropa y textiles', estado: 'permitido' },
    { nombre: 'Artesanías de tela', estado: 'permitido' },
    { nombre: 'Libros y documentos', estado: 'permitido' },
    { nombre: 'Juguetes (sin pilas)', estado: 'permitido' },
    { nombre: 'Café y cacao', estado: 'permitido' },
    { nombre: 'Chocolates', estado: 'permitido' },
    { nombre: 'Perfumes y colonias', estado: 'peligroso' },
    { nombre: 'Pilas y baterías de litio', estado: 'peligroso' },
    { nombre: 'Aerosoles', estado: 'peligroso' },
    { nombre: 'Bebidas alcohólicas', estado: 'peligroso' },
    { nombre: 'Medicinas', estado: 'restringido' },
    { nombre: 'Semillas', estado: 'restringido' },
    { nombre: 'Joyas de oro o plata', estado: 'restringido' },
    { nombre: 'Dinero en efectivo', estado: 'prohibido' },
    { nombre: 'Encendedores / fósforos', estado: 'prohibido' },
];

export const ESTADO_VISUAL = {
    permitido: { icono: 'circle-check', label: 'Sí puede enviarse', clase: 'text-go', fondo: 'bg-go-tint border-go/40' },
    restringido: { icono: 'triangle-alert', label: 'Necesita un permiso', clase: 'text-ambar', fondo: 'bg-ambar-tint border-ambar/40' },
    peligroso: { icono: 'octagon-x', label: 'No se puede enviar', clase: 'text-stop', fondo: 'bg-stop-tint border-stop/40' },
    prohibido: { icono: 'octagon-x', label: 'No se puede enviar', clase: 'text-stop', fondo: 'bg-stop-tint border-stop/50' },
};
