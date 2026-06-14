import { z } from 'zod';

const texto = (msg) => z.string().trim().min(1, msg);

const destinatarioSchema = z.object({
    nombre_completo: texto('Escribe los nombres y apellidos de quien recibe.'),
    direccion: texto('Falta la dirección del destinatario.'),
    ciudad: texto('Falta la ciudad de destino.'),
    codigo_postal: texto('Falta el código postal (es obligatorio para la entrega).'),
    pais: texto('Falta el país del destinatario.'),
});

const remitenteSchema = z.object({
    nombre_completo: texto('Escribe tus nombres y apellidos.'),
    numero_documento: texto('Falta tu número de documento.'),
    direccion: texto('Falta tu dirección en Perú.'),
    ciudad: texto('Falta tu ciudad.'),
    telefono: texto('Falta tu teléfono de contacto.'),
});

function primerMensaje(schema, data) {
    const r = schema.safeParse(data);
    return r.success ? null : r.error.issues[0]?.message ?? 'Completa los datos requeridos.';
}

export function validarPaso(paso, store) {
    switch (paso) {
        case 0: {
            if (store.hayArticulosBloqueados)
                return { valido: false, mensaje: 'Hay productos que no se pueden enviar. Quítalos para continuar.' };
            if (store.hayDescripcionesVagas)
                return { valido: false, mensaje: 'Describe mejor el contenido: evita “varios”, “regalo” o “cosas”.' };
            if (!store.articulos.some((a) => a.descripcion.trim()))
                return { valido: false, mensaje: 'Agrega al menos un producto para empezar.' };
            return { valido: true };
        }
        case 1: {
            if (!store.pais) return { valido: false, mensaje: 'Elige el país de destino.' };
            const m = primerMensaje(destinatarioSchema, store.destinatario);
            return m ? { valido: false, mensaje: m } : { valido: true };
        }
        case 2: {
            if (store.hayArticulosBloqueados)
                return { valido: false, mensaje: 'Hay productos que no se pueden enviar. Vuelve y quítalos.' };
            if (store.hayDescripcionesVagas)
                return { valido: false, mensaje: 'Describe mejor cada producto para la aduana.' };
            const exigeValor = store.naturaleza === 'regalo';
            const incompleto = store.articulos.some(
                (a) => !(a.descripcion.trim() && a.cantidad > 0 && a.peso_neto_gramos > 0 && (exigeValor ? a.valor > 0 : a.valor >= 0)),
            );
            if (incompleto)
                return {
                    valido: false,
                    mensaje: exigeValor
                        ? 'Completa cantidad, peso y valor (mayor que 0) de cada producto.'
                        : 'Completa cantidad, peso y valor de cada producto.',
                };
            return { valido: true };
        }
        case 3: {
            const m = primerMensaje(remitenteSchema, store.remitente);
            if (m) return { valido: false, mensaje: m };
            if (store.remitente.tipo_documento === 'dni' && !/^\d{8}$/.test(store.remitente.numero_documento))
                return { valido: false, mensaje: 'El DNI debe tener 8 dígitos.' };
            if (!store.remitente.depositante_es_remitente
                && !(store.remitente.depositante_nombre.trim() && store.remitente.depositante_numero_doc.trim()))
                return { valido: false, mensaje: 'Completa los datos de quién depositará el envío.' };
            return { valido: true };
        }
        case 4: {
            if (!(Number(store.paquete.peso_bruto) > 0))
                return { valido: false, mensaje: 'Ingresa el peso total del paquete.' };
            if (!store.servicio)
                return { valido: false, mensaje: 'Falta el servicio: ingresa el peso para recomendarlo.' };
            if (!store.oficina)
                return { valido: false, mensaje: 'Elige la oficina donde depositarás el envío.' };
            return { valido: true };
        }
        default:
            return { valido: true };
    }
}
