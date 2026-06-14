<script setup>
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import { MONEDAS, NATURALEZAS, TIPOS_ENVIO, TIPOS_DOC_COMERCIAL, ENTIDADES_CERTIFICADO } from '@/Utils/constantes';
import AppCard from '@/Components/ui/AppCard.vue';
import AppSelect from '@/Components/ui/AppSelect.vue';
import AppInput from '@/Components/ui/AppInput.vue';
import AppAlert from '@/Components/ui/AppAlert.vue';
import TablaArticulos from '@/Components/forms/TablaArticulos.vue';
import StepHeader from '@/Components/ui/StepHeader.vue';

const store = useEnvioStore();
const {
    tipoEnvio, naturaleza, divisa,
    requiereDocumento, hayArticulosRestringidos,
    hayArticulosBloqueados, documentoComercial, certificado,
    regaloPareceComercial,
} = storeToRefs(store);
</script>

<template>
    <div class="space-y-4">
        <StepHeader titulo-key="enc_contenido_t" subtitulo-key="enc_contenido_s" />

        <AppCard titulo="Detalle de cada producto" icono="package" subtitulo="¿Cuánto pesa y cuánto cuesta cada uno?">
            <AppAlert v-if="hayArticulosBloqueados" tipo="error" titulo="Contenido no permitido">
                Uno o más artículos no pueden enviarse. Vuelve al paso anterior y quítalos.
            </AppAlert>
            <div class="mt-1">
                <TablaArticulos modo="detalle" />
            </div>
        </AppCard>

        <AppCard titulo="Sobre el envío" icono="clipboard">
            <AppSelect v-model="naturaleza" label="¿Por qué lo envías?" :options="NATURALEZAS" required />
            <AppSelect v-model="tipoEnvio" label="¿Qué tipo de envío es?" :options="TIPOS_ENVIO" required />
            <AppSelect
                v-model="divisa"
                label="Moneda de los valores"
                help="En qué moneda están los precios que escribiste."
                :options="MONEDAS"
                value-key="codigo"
                label-key="nombre"
                required
            />
        </AppCard>

        <AppAlert v-if="naturaleza === 'regalo'" tipo="warning" titulo="Regalos: declara el valor real">
            Aunque sea un regalo, declara cuánto vale cada artículo (no puede ir en 0) y guarda tu comprobante
            —boleta o captura de compra—. Aduanas puede pedirlo.
        </AppAlert>

        <AppAlert v-if="regaloPareceComercial" tipo="warning" titulo="¿Seguro que es un regalo?">
            Por el valor o el peso declarado, este envío parece comercial. En el país de destino los regalos
            suelen estar libres de impuestos solo bajo cierto monto (p. ej. EE.&nbsp;UU. ~US$100). Si en realidad
            es una venta, decláralo como «Venta de bienes» para evitar demoras o multas en aduana.
        </AppAlert>

        <AppCard v-if="requiereDocumento" titulo="Documento de sustento" icono="receipt" subtitulo="Como no es un regalo, adjunta tu comprobante.">
            <AppSelect v-model="documentoComercial.tipo" label="Tipo de documento" :options="TIPOS_DOC_COMERCIAL" />
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <AppInput v-model="documentoComercial.serie" label="Serie" placeholder="Ej: F001" />
                <AppInput v-model="documentoComercial.numero" label="Número" placeholder="Ej: 001234" />
            </div>
            <AppInput v-model="documentoComercial.razon" label="Razón social del proveedor" />
        </AppCard>

        <AppCard v-if="hayArticulosRestringidos" titulo="Permiso requerido" icono="file" subtitulo="Uno de tus productos necesita un permiso.">
            <AppAlert tipo="warning" titulo="Llévalo el día de tu envío">
                Anota aquí los datos del permiso si ya lo tienes. Si aún no, puedes obtenerlo antes de ir a la oficina.
            </AppAlert>
            <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                <AppInput v-model="certificado.numero" label="N° de certificado" />
                <AppInput v-model="certificado.licencia" label="N° de licencia" />
            </div>
            <AppSelect
                v-model="certificado.entidad"
                label="Entidad que lo emite"
                :options="ENTIDADES_CERTIFICADO.map((e) => ({ valor: e, label: e }))"
            />
        </AppCard>
    </div>
</template>
