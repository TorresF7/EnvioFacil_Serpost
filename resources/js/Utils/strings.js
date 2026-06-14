

export const IDIOMAS = [
    { codigo: 'es', nombre: 'Español', corto: 'ES' },
    { codigo: 'qu', nombre: 'Runasimi', corto: 'QU' },
    { codigo: 'en', nombre: 'English', corto: 'EN' },
];

export function distanciaLevenshtein(a = '', b = '') {
    if (a === b) return 0;
    if (!a.length) return b.length;
    if (!b.length) return a.length;

    let fila = Array.from({ length: b.length + 1 }, (_, i) => i);

    for (let i = 1; i <= a.length; i += 1) {
        let anterior = fila[0];
        fila[0] = i;
        for (let j = 1; j <= b.length; j += 1) {
            const temp = fila[j];
            const costo = a[i - 1] === b[j - 1] ? 0 : 1;
            fila[j] = Math.min(fila[j] + 1, fila[j - 1] + 1, anterior + costo);
            anterior = temp;
        }
    }

    return fila[b.length];
}

export function normalizarBusqueda(texto = '') {
    return texto
        .toLowerCase()
        .normalize('NFD')
        .replace(/\p{Diacritic}/gu, '')
        .trim();
}

export const STRINGS = {
    es: {
        brand: 'SERPOST Envío Fácil',
        brand_tag: 'El Correo del Perú',

        btn_atras: 'Atrás',
        btn_siguiente: 'Siguiente',
        btn_comenzar: 'Comenzar registro',
        btn_generar_qr: 'Generar mi código',
        btn_ver_seguimiento: 'Ver seguimiento',
        btn_otro_envio: 'Registrar otro envío',
        btn_generando: 'Generando…',
        btn_revisar: 'Revisar mi envío',

        paso_producto: 'Producto',
        paso_destino: 'Destino',
        paso_contenido: 'Contenido',
        paso_remitente: 'Remitente',
        paso_servicio: 'Servicio y pago',
        paso_revisa: 'Revisa',
        paso_qr: 'Tu código',
        paso_seguimiento: 'Seguimiento',

        enc_paso: 'Paso',
        enc_de: 'de',
        enc_tu_envio: 'Tu envío',
        enc_pasos_aria: 'Pasos del registro',
        enc_destino_t: '¿A qué país y a quién le envías?',
        enc_destino_s: 'Indica el destino y los datos de quien recibe; viajan con tu envío a la aduana.',
        enc_contenido_t: 'Cuéntanos del contenido',
        enc_contenido_s: 'Detalla cuánto pesa y cuánto vale cada producto para la declaración.',
        enc_remitente_t: '¿Quién envía?',
        enc_remitente_s: 'Tus datos como remitente y quién depositará el paquete.',
        enc_servicio_t: 'Peso, servicio y dónde lo dejas',
        enc_servicio_s: 'Con el peso te recomendamos el servicio y eliges la oficina de depósito.',
        enc_revisa_t: 'Revisa tu envío',
        enc_revisa_s: 'Verifica que todo esté correcto antes de generar tu código.',
        sub_producto: '¿Qué envías?',
        sub_destino: 'País y destinatario',
        sub_contenido: 'Peso y valor',
        sub_remitente: 'Tus datos',
        sub_servicio: 'Servicio y oficina',
        sub_revisa: 'Confirma y genera',

        toast_guardado: 'Listo: tu código se generó correctamente.',
        toast_error_guardar: 'No se pudo guardar el envío. Revisa los datos e inténtalo otra vez.',
        toast_bloqueado: 'Hay productos que no se pueden enviar. Revisa el contenido.',
        toast_revisa: 'Revisa los datos: falta completar algún campo.',

        a11y_titulo: 'Accesibilidad',
        a11y_texto: 'Texto',
        a11y_contraste: 'Alto contraste',
        a11y_audio: 'Escuchar',
        a11y_audio_detener: 'Detener audio',
        a11y_idioma: 'Idioma',

        bienvenida_titulo: 'Prepara tu envío internacional',
        bienvenida_sub: 'Registra todo desde casa y reduce tu tiempo en ventanilla de 15 a ~5 minutos.',
        verificador_titulo: '¿Puedo enviar esto?',
        verificador_sub: 'Verifícalo antes de salir de casa',
        como_funciona: '¿Cómo funciona?',
        que_llevar: 'Qué necesitas llevar',

        producto_titulo: '¿Qué vas a enviar?',
        producto_sub: 'Agrégalo y te decimos al instante si se puede.',
        ejemplos_label: 'O toca un ejemplo',
        grupo_verde: 'Se puede',
        grupo_ambar: 'Con permiso',
        grupo_rojo: 'No se puede',
        agregar_producto: 'Agregar otro producto',
        semaforo_verde: 'Se puede enviar',
        semaforo_permiso_de: 'Necesita permiso de',
        semaforo_permiso: 'Necesita un permiso',
        semaforo_rojo: 'No se puede enviar por correo',
        semaforo_siguiente: 'Siguiente paso',
        veredicto_confianza: 'Verificación referencial · la oficina confirma',
        veredicto_porque: 'Más detalle',
        veredicto_marco: 'Marco normativo',
        semaforo_desconocido: 'Lo confirman en la oficina',
        puede_seguir: 'Puedes seguir',

        footer_qu_nota: 'Los textos en quechua son un borrador honesto, pendiente de validación con el Ministerio de Cultura.',
        footer_legal: 'Pre-admisión según Directiva N° 003-G/21 y Normativa OPV. Datos protegidos por la Ley N° 29733. Tienes 2 días hábiles para depositar tu envío.',
        linea_1812: 'Línea 1812 · Intérprete del Estado, gratis, 24/7',
    },

    qu: {
        brand: 'SERPOST Envío Fácil',
        brand_tag: 'Perú Correo',

        btn_atras: 'Qhipaman',
        btn_siguiente: 'Qatiq',
        btn_comenzar: 'Qallariy',
        btn_generar_qr: 'Codigoyta ruway',
        btn_ver_seguimiento: 'Qatipayta qhaway',
        btn_otro_envio: 'Huk kachaytawan qillqay',
        btn_generando: 'Ruwachkan…',
        btn_revisar: 'Kachayniyta qhaway',

        paso_producto: 'Imata',
        paso_destino: 'Maytataq',
        paso_contenido: 'Ukhunpi',
        paso_remitente: 'Pi kachan',
        paso_servicio: 'Servicio, pago',
        paso_revisa: 'Qhaway',
        paso_qr: 'Codigoyki',
        paso_seguimiento: 'Qatipay',

        enc_paso: 'Paso',
        enc_de: 'de',
        enc_tu_envio: 'Kachayniyki',
        enc_pasos_aria: 'Qillqay ñankuna',
        enc_destino_t: '¿May suyuman, pitaq chaskinqa?',
        enc_destino_s: 'Maytataq hinaspa pi chaskinqa willay; aduanaman rinqa.',
        enc_contenido_t: 'Imam ukhunpi willaway',
        enc_contenido_s: 'Sapa imapaq hayk’a llasaynin hinaspa hayk’a chaninpas willay.',
        enc_remitente_t: '¿Pitaq kachan?',
        enc_remitente_s: 'Qanmanta willay, hinaspa pitaq churamunqa.',
        enc_servicio_t: 'Llasay, servicio, maypi saqinki',
        enc_servicio_s: 'Llasaywan servicio akllasunchik, hinaspa oficinata akllay.',
        enc_revisa_t: 'Kachayniyta qhaway',
        enc_revisa_s: 'Tukuy allinchu kasqanta qhaway, codigota ruwananchikpaq.',
        sub_producto: '¿Imatataq?',
        sub_destino: 'Suyu, chaskiq',
        sub_contenido: 'Llasay, chani',
        sub_remitente: 'Qanpa willayniyki',
        sub_servicio: 'Servicio, oficina',
        sub_revisa: 'Qhaway, ruway',

        toast_guardado: 'Allin: codigoyki ruwakurunña.',
        toast_error_guardar: 'Manam kachayta waqaychay atikurqachu. Willaykunata qhaway, yapamanta ruway.',
        toast_bloqueado: 'Wakin imakuna manam kachay atikunchu. Ukhunta qhaway.',
        toast_revisa: 'Willaykunata qhaway: huk casillam pisin.',

        a11y_titulo: 'Yanapay',
        a11y_texto: 'Qillqa',
        a11y_contraste: 'Sumaq rikch’ay',
        a11y_audio: 'Uyariy',
        a11y_audio_detener: 'Sayachiy',
        a11y_idioma: 'Rimay',

        bienvenida_titulo: 'Hawa suyuman kachaynikita wakichiy',
        bienvenida_sub: 'Wasikimanta tukuyta qillqay, ventanillapi pacha pisiyachiy.',
        verificador_titulo: '¿Kaytachu kachay atini?',
        verificador_sub: 'Wasikimanta lluqsishaspa qhaway',
        como_funciona: '¿Imaynam llamk’an?',
        que_llevar: 'Imata apanayki',

        producto_titulo: '¿Imatataq kachanki?',
        producto_sub: 'Yapaykuy, kachay atisqaykita willasqayki.',
        ejemplos_label: 'Utaq huk kaqta llamiy',
        grupo_verde: 'Atikun',
        grupo_ambar: 'Permisowan',
        grupo_rojo: 'Mana atikunchu',
        agregar_producto: 'Huk kaqta yapay',
        semaforo_verde: 'Kachay atikun',
        semaforo_permiso_de: 'Kay permisota munan:',
        semaforo_permiso: 'Huk permisota munan',
        semaforo_rojo: 'Manam correowan kachay atikunchu',
        semaforo_siguiente: 'Qatiq ruway',
        veredicto_confianza: 'Yanapaq qhaway · oficinam tukuchin',
        veredicto_porque: 'Astawan willay',
        veredicto_marco: 'Kamachiykuna',
        semaforo_desconocido: 'Oficinapim qhawakunqa',
        puede_seguir: 'Qatiyta atinki',

        footer_qu_nota: 'Los textos en quechua son un borrador honesto, pendiente de validación con el Ministerio de Cultura.',
        footer_legal: 'Pre-admisión según Directiva N° 003-G/21 y Normativa OPV. Datos protegidos por la Ley N° 29733. Tienes 2 días hábiles para depositar tu envío.',
        linea_1812: 'Línea 1812 · Estadup t’ikrachiqnin, qullqimanta, 24/7',
    },

    en: {
        brand: 'SERPOST Easy Send',
        brand_tag: 'Peru Post',

        btn_atras: 'Back',
        btn_siguiente: 'Next',
        btn_comenzar: 'Start registration',
        btn_generar_qr: 'Generate my code',
        btn_ver_seguimiento: 'View tracking',
        btn_otro_envio: 'Register another shipment',
        btn_generando: 'Generating…',
        btn_revisar: 'Review my shipment',

        paso_producto: 'Item',
        paso_destino: 'Destination',
        paso_contenido: 'Contents',
        paso_remitente: 'Sender',
        paso_servicio: 'Service & payment',
        paso_revisa: 'Review',
        paso_qr: 'Your code',
        paso_seguimiento: 'Tracking',

        enc_paso: 'Step',
        enc_de: 'of',
        enc_tu_envio: 'Your shipment',
        enc_pasos_aria: 'Registration steps',
        enc_destino_t: 'Where and to whom are you sending?',
        enc_destino_s: 'Set the destination and recipient details; they travel with your shipment to customs.',
        enc_contenido_t: 'Tell us about the contents',
        enc_contenido_s: 'Detail the weight and value of each item for the declaration.',
        enc_remitente_t: 'Who is sending?',
        enc_remitente_s: 'Your details as sender and who will drop off the parcel.',
        enc_servicio_t: 'Weight, service and drop-off',
        enc_servicio_s: 'We recommend a service from the weight, and you pick the drop-off office.',
        enc_revisa_t: 'Review your shipment',
        enc_revisa_s: 'Check that everything is correct before generating your code.',
        sub_producto: 'What you send',
        sub_destino: 'Country & recipient',
        sub_contenido: 'Weight & value',
        sub_remitente: 'Your details',
        sub_servicio: 'Service & office',
        sub_revisa: 'Confirm & generate',

        toast_guardado: 'Done: your code was generated successfully.',
        toast_error_guardar: 'Could not save the shipment. Check the details and try again.',
        toast_bloqueado: 'Some items cannot be shipped. Review the contents.',
        toast_revisa: 'Please review: a required field is missing.',

        a11y_titulo: 'Accessibility',
        a11y_texto: 'Text',
        a11y_contraste: 'High contrast',
        a11y_audio: 'Listen',
        a11y_audio_detener: 'Stop audio',
        a11y_idioma: 'Language',

        bienvenida_titulo: 'Prepare your international shipment',
        bienvenida_sub: 'Register everything from home and cut your counter time from 15 to ~5 minutes.',
        verificador_titulo: 'Can I send this?',
        verificador_sub: 'Check it before leaving home',
        como_funciona: 'How does it work?',
        que_llevar: 'What to bring',

        producto_titulo: 'What are you sending?',
        producto_sub: 'Add it and we’ll tell you instantly if it can be sent.',
        ejemplos_label: 'Or tap an example',
        grupo_verde: 'Allowed',
        grupo_ambar: 'With permit',
        grupo_rojo: 'Not allowed',
        agregar_producto: 'Add another item',
        semaforo_verde: 'Can be sent',
        semaforo_permiso_de: 'Needs a permit from',
        semaforo_permiso: 'Needs a permit',
        semaforo_rojo: 'Cannot be sent by mail',
        semaforo_siguiente: 'Next step',
        veredicto_confianza: 'Reference check · the office confirms',
        veredicto_porque: 'More detail',
        veredicto_marco: 'Legal framework',
        semaforo_desconocido: 'Confirmed at the office',
        puede_seguir: 'You can continue',

        footer_qu_nota: 'Quechua texts are an honest draft, pending validation with the Ministry of Culture.',
        footer_legal: 'Pre-admission under Directive No. 003-G/21 and OPV regulations. Data protected by Law No. 29733. You have 2 business days to deposit your shipment.',
        linea_1812: 'Line 1812 · State interpreter, free, 24/7',
    },
};
