import { defineStore } from 'pinia';
import { PAISES, SERVICIOS, UMBRAL_VALOR_CN22_PEN, TIPO_CAMBIO_A_PEN, UMBRAL_REGALO_USD, UMBRAL_REGALO_KG } from '@/Utils/constantes';
import { esDescripcionVaga, clasificar } from '@/Composables/useVerificadorProducto';
import { trackVerificador } from '@/Composables/useAnalytics';
import { formatearMoneda } from '@/Utils/formateadores';

const TOTAL_PASOS = 8;

function articuloVacio() {
    return {
        descripcion: '',
        cantidad: 1,
        peso_neto_gramos: null,
        valor: null,
        pais_origen: 'Perú',
        codigo_hs: '',
        categoria: 'permitido',
    };
}

function remitenteVacio() {
    return {
        tipo_documento: 'dni',
        numero_documento: '',
        nombre_completo: '',
        empresa: '',
        ruc: '',
        direccion: '',
        ciudad: '',
        departamento: '',
        codigo_postal: '',
        telefono: '',
        email: '',
        depositante_es_remitente: true,
        depositante_nombre: '',
        depositante_tipo_doc: 'dni',
        depositante_numero_doc: '',
        depositante_direccion: '',
    };
}

function destinatarioVacio() {
    return {
        nombre_completo: '',
        empresa: '',
        referencia_importador: '',
        direccion: '',
        ciudad: '',
        estado_region: '',
        codigo_postal: '',
        pais: '',
        telefono: '',
        email: '',
    };
}

function paqueteVacio() {
    return {
        peso_bruto: null,
        largo_cm: null,
        ancho_cm: null,
        alto_cm: null,
        observaciones: '',
        valor_aduanas: null,
        valor_seguro: null,
        instruccion_no_entrega: '',
        devolver_dias: null,
        direccion_redireccion: '',
        modalidad: '',
        via: '',
        cantidad_bultos: null,
        gastos_porte: null,
    };
}

export const useEnvioStore = defineStore('envio', {
    state: () => ({
        pasoActual: 0,
        oficinasDisponibles: [],
        servicio: null,
        pais: null,
        oficina: null,
        tipoEnvio: 'mercaderia',
        naturaleza: 'regalo',
        divisa: 'USD',
        articulos: [articuloVacio()],
        documentoComercial: { tipo: 'factura', serie: '', numero: '', razon: '' },
        certificado: { numero: '', licencia: '', entidad: 'SENASA' },
        remitente: remitenteVacio(),
        destinatario: destinatarioVacio(),
        paquete: paqueteVacio(),
        notificaciones: { email: '', whatsapp: '', activas: false },

        codigo: null,
        qrPayload: null,
        descargarUrl: null,
        rotuladoUrl: null,
        guardando: false,
        errorGuardado: null,
    }),

    getters: {
        totalPasos: () => TOTAL_PASOS,

        servicioActual: (state) => SERVICIOS.find((s) => s.valor === state.servicio) ?? null,

        formularioRequerido(state) {
            return this.servicioActual?.formulario ?? null;
        },

        esEncomienda: (state) => state.servicio === 'encomienda',

        requiereDocumento: (state) => state.naturaleza !== 'regalo',

        hayArticulosRestringidos: (state) =>
            state.articulos.some((a) => a.categoria === 'restringido'),

        hayArticulosBloqueados: (state) =>
            state.articulos.some((a) => a.categoria === 'prohibido' || a.categoria === 'peligroso'),

        pesoVolumetrico: (state) => {
            const { largo_cm: l, ancho_cm: a, alto_cm: h } = state.paquete;
            if (!l || !a || !h) return 0;
            return Math.round(((l * a * h) / 6000) * 1000) / 1000;
        },

        pesoFacturable(state) {
            const real = Number(state.paquete.peso_bruto) || 0;
            return Math.max(real, this.pesoVolumetrico);
        },

        pesoNetoTotalGramos(state) {
            return state.articulos.reduce((s, a) => s + (Number(a.peso_neto_gramos) || 0), 0);
        },

        pesoBrutoConsistente(state) {
            const bruto = Number(state.paquete.peso_bruto) || 0;
            if (!bruto || !this.pesoNetoTotalGramos) return true;
            return bruto * 1000 >= this.pesoNetoTotalGramos;
        },

        valorDeclaradoUSD() {
            return this.valorDeclaradoPEN / TIPO_CAMBIO_A_PEN.USD;
        },

        regaloPareceComercial(state) {
            if (state.naturaleza !== 'regalo') return false;
            return this.valorDeclaradoUSD > UMBRAL_REGALO_USD || this.pesoFacturable > UMBRAL_REGALO_KG;
        },

        puedeAvanzar(state) {
            switch (state.pasoActual) {
                case 0:
                    return this.productoValido;
                case 1:
                    return this.destinoValido;
                case 2:
                    return this.contenidoValido;
                case 3:
                    return this.remitenteValido;
                case 4:
                    return this.servicioPagoValido;
                default:
                    return true;
            }
        },

        productoValido(state) {
            if (this.hayArticulosBloqueados || this.hayDescripcionesVagas) return false;
            return state.articulos.length >= 1 && state.articulos.every((a) => a.descripcion.trim());
        },

        destinoValido(state) {
            return !!state.pais && this.destinatarioValido;
        },

        contenidoValido(state) {
            if (this.hayArticulosBloqueados || this.hayDescripcionesVagas) return false;
            const valorOk = state.naturaleza === 'regalo' ? (a) => a.valor > 0 : (a) => a.valor >= 0;
            return state.articulos.every(
                (a) => a.descripcion.trim() && a.cantidad > 0 && a.peso_neto_gramos > 0 && valorOk(a),
            );
        },

        servicioPagoValido(state) {
            return !!state.servicio && !!state.oficina && Number(state.paquete.peso_bruto) > 0;
        },

        valorDeclaradoPEN(state) {
            const tc = TIPO_CAMBIO_A_PEN[state.divisa] ?? TIPO_CAMBIO_A_PEN.USD;
            return state.articulos.reduce((s, a) => s + (Number(a.valor) || 0), 0) * tc;
        },

        superaUmbralCN22() {
            return this.valorDeclaradoPEN > UMBRAL_VALOR_CN22_PEN;
        },

        servicioRecomendado(state) {
            const kg = Number(state.paquete.peso_bruto) || 0;
            if (!kg) return null;
            if (kg <= 2 && !this.superaUmbralCN22 && !this.hayArticulosRestringidos) return 'pp';
            if (kg <= 30) return 'ems';
            if (kg <= 31.5) return 'encomienda';
            return null;
        },

        porqueServicio(state) {
            const rec = this.servicioRecomendado;
            if (!rec) return null;
            const tope = formatearMoneda(UMBRAL_VALOR_CN22_PEN, 'PEN');
            if (rec === 'pp') {
                return `Hasta 2 kg y valor hasta ~${tope} → Pequeño Paquete, declaración CN22 de la UPU.`;
            }
            const motivos = [];
            if (Number(state.paquete.peso_bruto) > 2) motivos.push('superar los 2 kg');
            if (this.superaUmbralCN22) motivos.push(`el valor declarado (supera ~${tope})`);
            if (this.hayArticulosRestringidos) motivos.push('contener artículos que requieren permiso');
            const causa = motivos.length ? ` por ${motivos.join(' y ')}` : '';
            return `Corresponde la declaración ${rec === 'encomienda' ? 'CP72' : 'CN23'} de la UPU${causa}.`;
        },

        hayDescripcionesVagas(state) {
            return state.articulos.some((a) => esDescripcionVaga(a.descripcion));
        },

        descripcionDeclarada(state) {
            return state.articulos
                .map((a) => {
                    const d = a.descripcion.trim();
                    if (!d) return null;
                    const c = Number(a.cantidad) || 1;
                    return c > 1 ? `${c} ${d}` : d;
                })
                .filter(Boolean)
                .join('; ');
        },

        remitenteValido(state) {
            const r = state.remitente;
            const docOk = r.tipo_documento === 'dni' ? /^\d{8}$/.test(r.numero_documento) : !!r.numero_documento;
            const base = docOk && r.nombre_completo.trim() && r.direccion.trim() && r.ciudad.trim() && r.telefono.trim();
            if (!base) return false;
            if (!r.depositante_es_remitente) {
                return !!(r.depositante_nombre.trim() && r.depositante_numero_doc.trim());
            }
            return true;
        },

        destinatarioValido(state) {
            const d = state.destinatario;
            return !!(d.nombre_completo.trim() && d.direccion.trim() && d.ciudad.trim() && d.codigo_postal.trim() && d.pais.trim());
        },
    },

    actions: {
        setOficinas(lista) {
            this.oficinasDisponibles = Array.isArray(lista) && lista.length ? lista : [];
        },

        avanzar() {
            if (this.pasoActual < TOTAL_PASOS - 1 && this.puedeAvanzar) {
                this.pasoActual += 1;
            }
        },

        retroceder() {
            if (this.pasoActual > 0) this.pasoActual -= 1;
        },

        irA(paso) {
            if (paso >= 0 && paso < TOTAL_PASOS) this.pasoActual = paso;
        },

        seleccionarServicio(valor) {
            this.servicio = valor;
        },

        agregarArticulo() {
            this.articulos.push(articuloVacio());
        },

        agregarDesdeEjemplo(nombre) {
            let destino = this.articulos.find((a) => !a.descripcion.trim());
            if (!destino) {
                this.articulos.push(articuloVacio());
                destino = this.articulos[this.articulos.length - 1];
            }
            destino.descripcion = nombre;
            const v = clasificar(nombre);
            destino.categoria = v.estado;
            trackVerificador(v, nombre);
        },

        eliminarArticulo(indice) {
            if (this.articulos.length > 1) this.articulos.splice(indice, 1);
        },

        reverificarArticulo(indice) {
            const articulo = this.articulos[indice];
            if (!articulo) return;
            const v = clasificar(articulo.descripcion);
            articulo.categoria = v.estado;
            trackVerificador(v, articulo.descripcion);
        },

        async derivarAranceles() {
            await Promise.all(
                this.articulos.map(async (articulo) => {
                    if (articulo.codigo_hs || !articulo.descripcion.trim()) return;
                    try {
                        const { data } = await window.axios.post('/envio/sugerir-arancel', {
                            descripcion: articulo.descripcion.trim(),
                        });
                        const sugerido = data?.sugerencias?.[0]?.codigo;
                        if (sugerido) articulo.codigo_hs = sugerido;
                    } catch {
                                            }
                }),
            );
        },

        seleccionarPais(pais) {
            this.pais = pais;
            this.destinatario.pais = pais?.nombre ?? '';
        },

        cargarDesdeReciente(precarga) {
            if (!precarga) return;
            if (precarga.remitente) {
                this.remitente = { ...remitenteVacio(), ...precarga.remitente };
            }
            if (precarga.destinatario) {
                this.destinatario = { ...destinatarioVacio(), ...precarga.destinatario };

                const pais = PAISES.find((p) => p.nombre === this.destinatario.pais);
                if (pais) this.seleccionarPais(pais);
            }
        },

        seleccionarOficina(oficina) {
            this.oficina = oficina;
        },

        reiniciar() {
            this.$reset();
        },

        construirPayload() {
            return {
                tipo_servicio: this.servicio,
                pais_destino: this.pais?.nombre ?? '',
                oficina_deposito: this.oficina?.nombre ?? '',
                tipo_envio: this.tipoEnvio,
                naturaleza: this.naturaleza,
                divisa: this.divisa,
                observaciones: this.paquete.observaciones || null,
                peso_bruto: this.paquete.peso_bruto,
                largo_cm: this.paquete.largo_cm,
                ancho_cm: this.paquete.ancho_cm,
                alto_cm: this.paquete.alto_cm,
                valor_aduanas: this.esEncomienda ? this.paquete.valor_aduanas : null,
                valor_seguro: this.esEncomienda ? this.paquete.valor_seguro : null,
                instruccion_no_entrega: this.esEncomienda ? this.paquete.instruccion_no_entrega || null : null,
                devolver_dias: this.esEncomienda ? this.paquete.devolver_dias : null,
                direccion_redireccion: this.paquete.direccion_redireccion || null,
                modalidad: this.paquete.modalidad || null,
                via: this.paquete.via || null,
                gastos_porte: this.paquete.gastos_porte,
                cantidad_bultos: this.esEncomienda ? this.paquete.cantidad_bultos : null,
                doc_comercial_tipo: this.requiereDocumento ? this.documentoComercial.tipo : null,
                doc_comercial_serie: this.requiereDocumento ? this.documentoComercial.serie : null,
                doc_comercial_numero: this.requiereDocumento ? this.documentoComercial.numero : null,
                doc_comercial_razon: this.requiereDocumento ? this.documentoComercial.razon : null,
                certificado_numero: this.hayArticulosRestringidos ? this.certificado.numero : null,
                licencia_numero: this.hayArticulosRestringidos ? this.certificado.licencia : null,
                certificado_entidad: this.hayArticulosRestringidos ? this.certificado.entidad : null,
                articulos: this.articulos.map((a) => ({
                    descripcion: a.descripcion,
                    cantidad: Number(a.cantidad),
                    peso_neto_gramos: Number(a.peso_neto_gramos),
                    valor: Number(a.valor),
                    pais_origen: a.pais_origen,
                    codigo_hs: a.codigo_hs || null,
                })),
                remitente: { ...this.remitente },
                destinatario: { ...this.destinatario },
            };
        },

        async guardar() {
            this.guardando = true;
            this.errorGuardado = null;
            try {
                const { data } = await window.axios.post('/envio/guardar', this.construirPayload());
                this.codigo = data.codigo;
                this.qrPayload = data.qr_payload;
                this.descargarUrl = data.descargar_url;
                this.rotuladoUrl = data.rotulado_url;
                return data;
            } catch (error) {
                this.errorGuardado = error?.response?.data?.message ?? 'No se pudo guardar el envío. Revisa los datos.';
                throw error;
            } finally {
                this.guardando = false;
            }
        },
    },
});
