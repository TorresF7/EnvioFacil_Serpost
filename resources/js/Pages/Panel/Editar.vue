<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PanelLayout from '@/Components/PanelLayout.vue';
import AppCard from '@/Components/ui/AppCard.vue';
import AppInput from '@/Components/ui/AppInput.vue';
import AppSelect from '@/Components/ui/AppSelect.vue';
import AppButton from '@/Components/ui/AppButton.vue';
import {
    SERVICIOS, NATURALEZAS, TIPOS_DOCUMENTO, TIPOS_ENVIO, MONEDAS, PAISES,
    MODALIDADES, VIAS, INSTRUCCIONES_NO_ENTREGA, TIPOS_DOC_COMERCIAL, ENTIDADES_CERTIFICADO,
} from '@/Utils/constantes';

const props = defineProps({
    envio: { type: Object, required: true },
    oficinas: { type: Array, default: () => [] },
});

const e = props.envio;
const form = useForm({
    tipo_servicio: e.tipo_servicio,
    pais_destino: e.pais_destino,
    oficina_deposito: e.oficina_deposito,
    tipo_envio: e.tipo_envio,
    naturaleza: e.naturaleza,
    divisa: e.divisa,
    peso_bruto: e.peso_bruto,
    largo_cm: e.largo_cm,
    ancho_cm: e.ancho_cm,
    alto_cm: e.alto_cm,
    modalidad: e.modalidad ?? '',
    via: e.via ?? '',
    cantidad_bultos: e.cantidad_bultos,
    gastos_porte: e.gastos_porte,
    valor_aduanas: e.valor_aduanas,
    valor_seguro: e.valor_seguro,
    instruccion_no_entrega: e.instruccion_no_entrega ?? '',
    devolver_dias: e.devolver_dias,
    direccion_redireccion: e.direccion_redireccion ?? '',
    observaciones: e.observaciones ?? '',
    doc_comercial_tipo: e.doc_comercial_tipo ?? '',
    doc_comercial_serie: e.doc_comercial_serie ?? '',
    doc_comercial_numero: e.doc_comercial_numero ?? '',
    doc_comercial_razon: e.doc_comercial_razon ?? '',
    certificado_numero: e.certificado_numero ?? '',
    licencia_numero: e.licencia_numero ?? '',
    certificado_entidad: e.certificado_entidad ?? '',
    remitente: {
        tipo_documento: e.remitente.tipo_documento,
        numero_documento: e.remitente.numero_documento,
        nombre_completo: e.remitente.nombre_completo,
        empresa: e.remitente.empresa ?? '',
        ruc: e.remitente.ruc ?? '',
        direccion: e.remitente.direccion,
        ciudad: e.remitente.ciudad,
        departamento: e.remitente.departamento ?? '',
        codigo_postal: e.remitente.codigo_postal ?? '',
        telefono: e.remitente.telefono,
        email: e.remitente.email ?? '',
        depositante_es_remitente: e.remitente.depositante_es_remitente,
        depositante_nombre: e.remitente.depositante_nombre ?? '',
        depositante_tipo_doc: e.remitente.depositante_tipo_doc ?? 'dni',
        depositante_numero_doc: e.remitente.depositante_numero_doc ?? '',
        depositante_direccion: e.remitente.depositante_direccion ?? '',
    },
    destinatario: {
        nombre_completo: e.destinatario.nombre_completo,
        empresa: e.destinatario.empresa ?? '',
        referencia_importador: e.destinatario.referencia_importador ?? '',
        direccion: e.destinatario.direccion,
        ciudad: e.destinatario.ciudad,
        estado_region: e.destinatario.estado_region ?? '',
        codigo_postal: e.destinatario.codigo_postal,
        pais: e.destinatario.pais,
        telefono: e.destinatario.telefono ?? '',
        email: e.destinatario.email ?? '',
    },
    articulos: e.articulos.map((a) => ({ ...a })),
});

const opcionesOficina = props.oficinas.map((o) => ({ valor: o.nombre, label: `${o.nombre} · ${o.ciudad}` }));

const opcionesPais = PAISES.map((p) => ({ valor: p.nombre, label: p.nombre }));
const esEncomienda = () => form.tipo_servicio === 'encomienda';

function agregarArticulo() {
    form.articulos.push({ descripcion: '', cantidad: 1, peso_neto_gramos: null, valor: null, pais_origen: 'Perú', codigo_hs: '' });
}
function quitarArticulo(i) {
    if (form.articulos.length > 1) form.articulos.splice(i, 1);
}
function guardar() {
    form.transform((d) => ({
        ...d,
        modalidad: d.modalidad || null,
        via: d.via || null,
        instruccion_no_entrega: d.instruccion_no_entrega || null,
    })).patch(`/panel/solicitud/${e.codigo}/datos`);
}
</script>

<template>
    <Head :title="`Editar ${envio.codigo}`" />
    <PanelLayout :titulo="`Editar solicitud ${envio.codigo}`" subtitulo="Corrige o completa los datos del envío">
        <Link :href="`/panel/solicitud/${envio.codigo}`" class="mb-4 inline-block text-sm font-semibold text-serpost-azul">← Volver al detalle</Link>

        <form class="grid gap-4 lg:grid-cols-2" @submit.prevent="guardar">
            <AppCard titulo="Servicio y destino" icono="📋">
                <AppSelect v-model="form.tipo_servicio" label="Tipo de servicio / formulario" :options="SERVICIOS" value-key="valor" label-key="nombre" />
                <AppSelect v-model="form.pais_destino" label="País de destino" :options="opcionesPais" />
                <AppSelect v-model="form.oficina_deposito" label="Oficina de depósito" :options="opcionesOficina" />
                <div class="grid grid-cols-2 gap-2">
                    <AppSelect v-model="form.tipo_envio" label="Tipo de envío" :options="TIPOS_ENVIO" />
                    <AppSelect v-model="form.naturaleza" label="Naturaleza" :options="NATURALEZAS" />
                </div>
                <AppSelect v-model="form.divisa" label="Divisa" :options="MONEDAS" value-key="codigo" label-key="nombre" />
            </AppCard>

            <AppCard titulo="Paquete y modalidad" icono="⚖️">
                <AppInput v-model="form.peso_bruto" label="Peso bruto (kg)" type="number" :error="form.errors.peso_bruto" />
                <div class="grid grid-cols-3 gap-2">
                    <AppInput v-model="form.largo_cm" label="Largo" type="number" />
                    <AppInput v-model="form.ancho_cm" label="Ancho" type="number" />
                    <AppInput v-model="form.alto_cm" label="Alto" type="number" />
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <AppSelect v-model="form.modalidad" label="Modalidad" placeholder="—" :options="MODALIDADES" />
                    <AppSelect v-model="form.via" label="Vía" placeholder="—" :options="VIAS" />
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <AppInput v-model="form.gastos_porte" label="Gastos de porte/flete" type="number" />
                    <AppInput v-model="form.cantidad_bultos" label="Cantidad de bultos" type="number" />
                </div>
                <AppInput v-model="form.observaciones" label="Observaciones" />
            </AppCard>

            <AppCard titulo="Remitente" icono="👤">
                <div class="grid grid-cols-2 gap-2">
                    <AppSelect v-model="form.remitente.tipo_documento" label="Tipo doc." :options="TIPOS_DOCUMENTO" />
                    <AppInput v-model="form.remitente.numero_documento" label="N° documento" />
                </div>
                <AppInput v-model="form.remitente.nombre_completo" label="Nombre completo" />
                <div class="grid grid-cols-2 gap-2">
                    <AppInput v-model="form.remitente.empresa" label="Empresa (opcional)" />
                    <AppInput v-model="form.remitente.ruc" label="RUC (opcional)" />
                </div>
                <AppInput v-model="form.remitente.direccion" label="Dirección" />
                <div class="grid grid-cols-2 gap-2">
                    <AppInput v-model="form.remitente.ciudad" label="Ciudad" />
                    <AppInput v-model="form.remitente.departamento" label="Departamento" />
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <AppInput v-model="form.remitente.codigo_postal" label="Cód. postal" />
                    <AppInput v-model="form.remitente.telefono" label="Teléfono" />
                </div>
                <AppInput v-model="form.remitente.email" label="Email" type="email" />
            </AppCard>

            <AppCard titulo="Destinatario" icono="🎯">
                <AppInput v-model="form.destinatario.nombre_completo" label="Nombre completo" />
                <div class="grid grid-cols-2 gap-2">
                    <AppInput v-model="form.destinatario.empresa" label="Empresa (opcional)" />
                    <AppInput v-model="form.destinatario.referencia_importador" label="Ref. importador" />
                </div>
                <AppInput v-model="form.destinatario.direccion" label="Dirección" />
                <div class="grid grid-cols-2 gap-2">
                    <AppInput v-model="form.destinatario.ciudad" label="Ciudad" />
                    <AppInput v-model="form.destinatario.estado_region" label="Estado / Región" />
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <AppInput v-model="form.destinatario.codigo_postal" label="Cód. postal" />
                    <AppInput v-model="form.destinatario.pais" label="País" />
                </div>
                <AppInput v-model="form.destinatario.telefono" label="Teléfono" />
                <AppInput v-model="form.destinatario.email" label="Email" type="email" />
            </AppCard>

            <AppCard titulo="Artículos" icono="📦" class="lg:col-span-2">
                <div v-for="(a, i) in form.articulos" :key="i" class="mb-2 grid grid-cols-12 items-end gap-2 rounded-input border border-borde bg-[#FAFAFA] p-2">
                    <input v-model="a.descripcion" placeholder="Descripción" class="input-base col-span-4 bg-white" />
                    <input v-model.number="a.cantidad" type="number" placeholder="Cant." class="input-base col-span-1 bg-white" />
                    <input v-model.number="a.peso_neto_gramos" type="number" placeholder="Peso g" class="input-base col-span-2 bg-white" />
                    <input v-model.number="a.valor" type="number" placeholder="Valor" class="input-base col-span-2 bg-white" />
                    <input v-model="a.codigo_hs" placeholder="HS" class="input-base col-span-2 bg-white" />
                    <button type="button" class="col-span-1 rounded-input border border-serpost/40 py-2 text-xs font-semibold text-serpost" @click="quitarArticulo(i)">✕</button>
                </div>
                <button type="button" class="rounded-input border-2 border-dashed border-serpost/40 px-3 py-2 text-sm font-semibold text-serpost" @click="agregarArticulo">＋ Agregar artículo</button>
            </AppCard>

            <AppCard titulo="Documento comercial" icono="🧾">
                <AppSelect v-model="form.doc_comercial_tipo" label="Tipo" placeholder="—" :options="TIPOS_DOC_COMERCIAL" />
                <div class="grid grid-cols-2 gap-2">
                    <AppInput v-model="form.doc_comercial_serie" label="Serie" />
                    <AppInput v-model="form.doc_comercial_numero" label="Número" />
                </div>
                <AppInput v-model="form.doc_comercial_razon" label="Razón social" />
            </AppCard>

            <AppCard titulo="Certificados y no entrega" icono="📑">
                <div class="grid grid-cols-2 gap-2">
                    <AppInput v-model="form.certificado_numero" label="N° certificado" />
                    <AppInput v-model="form.licencia_numero" label="N° licencia" />
                </div>
                <AppSelect v-model="form.certificado_entidad" label="Entidad" placeholder="—" :options="ENTIDADES_CERTIFICADO.map((x) => ({ valor: x, label: x }))" />
                <template v-if="esEncomienda()">
                    <div class="grid grid-cols-2 gap-2">
                        <AppInput v-model="form.valor_aduanas" label="Valor aduanas" type="number" />
                        <AppInput v-model="form.valor_seguro" label="Valor seguro" type="number" />
                    </div>
                    <AppSelect v-model="form.instruccion_no_entrega" label="Instrucción no entrega" placeholder="—" :options="INSTRUCCIONES_NO_ENTREGA" />
                    <div class="grid grid-cols-2 gap-2">
                        <AppInput v-model="form.devolver_dias" label="Devolver tras N días" type="number" />
                        <AppInput v-if="form.instruccion_no_entrega === 'redirigir'" v-model="form.direccion_redireccion" label="Dirección redirección" />
                    </div>
                </template>
            </AppCard>

            <div class="flex items-center gap-3 lg:col-span-2">
                <AppButton tipo="submit" variante="azul" :cargando="form.processing">💾 Guardar cambios</AppButton>
                <Link :href="`/panel/solicitud/${envio.codigo}`" class="text-sm font-semibold text-texto-medio">Cancelar</Link>
                <span v-if="form.recentlySuccessful" class="text-sm font-semibold text-serpost-verde">Guardado ✓</span>
            </div>
        </form>
    </PanelLayout>
</template>
