<script setup>
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import { formatearGramos, formatearMoneda, formatearPeso } from '@/Utils/formateadores';
import { NATURALEZAS, TIPOS_ENVIO } from '@/Utils/constantes';
import AppCard from '@/Components/ui/AppCard.vue';
import AppAlert from '@/Components/ui/AppAlert.vue';
import Icon from '@/Components/ui/Icon.vue';
import BanderaPais from '@/Components/ui/BanderaPais.vue';
import StepHeader from '@/Components/ui/StepHeader.vue';

const store = useEnvioStore();
const {
    servicioActual, pais, oficina, tipoEnvio, naturaleza, divisa,
    articulos, remitente, destinatario, paquete, pesoFacturable,
    requiereDocumento, documentoComercial, hayArticulosRestringidos,
    certificado, esEncomienda, errorGuardado, descripcionDeclarada,
} = storeToRefs(store);

const etiqueta = (lista, valor) => lista.find((x) => x.valor === valor)?.label ?? valor;
</script>

<template>
    <div class="space-y-4">
        <StepHeader titulo-key="enc_revisa_t" subtitulo-key="enc_revisa_s" />

        <AppAlert v-if="errorGuardado" tipo="error" titulo="No se pudo guardar">{{ errorGuardado }}</AppAlert>

        <AppCard titulo="Datos del envío" icono="clipboard">
            <dl class="grid grid-cols-2 gap-y-2 text-sm">
                <dt class="text-texto-medio">Servicio</dt>
                <dd class="text-right font-semibold">{{ servicioActual?.nombre }}</dd>
                <dt class="text-texto-medio">Formulario</dt>
                <dd class="text-right font-semibold">{{ servicioActual?.formulario }}</dd>
                <dt class="text-texto-medio">Destino</dt>
                <dd class="flex items-center justify-end gap-2 text-right font-semibold"><BanderaPais v-if="pais" :codigo="pais.codigo" :size="18" />{{ pais?.nombre }}</dd>
                <dt class="text-texto-medio">Oficina</dt>
                <dd class="text-right font-semibold">{{ oficina?.nombre }}</dd>
                <dt class="text-texto-medio">Tipo</dt>
                <dd class="text-right font-semibold">{{ etiqueta(TIPOS_ENVIO, tipoEnvio) }}</dd>
                <dt class="text-texto-medio">Naturaleza</dt>
                <dd class="text-right font-semibold">{{ etiqueta(NATURALEZAS, naturaleza) }}</dd>
                <dt class="text-texto-medio">Divisa</dt>
                <dd class="text-right font-semibold">{{ divisa }}</dd>
                <dt class="text-texto-medio">Peso facturable</dt>
                <dd class="text-right font-semibold">{{ formatearPeso(pesoFacturable) }}</dd>
            </dl>
        </AppCard>

        <AppCard titulo="Artículos" icono="package">
            <p class="mb-2 rounded bg-fondo px-2 py-1 text-xs text-texto-medio">
                <b>Declaración:</b> {{ descripcionDeclarada || '—' }}
            </p>
            <div v-for="(a, i) in articulos" :key="i" class="border-b border-borde/60 py-2 text-sm last:border-0">
                <div class="flex justify-between font-semibold">
                    <span>{{ a.cantidad }}× {{ a.descripcion || '—' }}</span>
                    <span>{{ formatearMoneda(a.valor, divisa) }}</span>
                </div>
                <div class="text-xs text-texto-medio">
                    {{ formatearGramos(a.peso_neto_gramos) }} · Origen: {{ a.pais_origen }}
                    <template v-if="a.codigo_hs"> · Partida {{ a.codigo_hs }}</template>
                </div>
            </div>
        </AppCard>

        <AppCard v-if="requiereDocumento && documentoComercial.numero" titulo="Documento de sustento" icono="receipt">
            <p class="text-sm">
                {{ documentoComercial.tipo }} {{ documentoComercial.serie }}-{{ documentoComercial.numero }}
            </p>
            <p class="text-xs text-texto-medio">{{ documentoComercial.razon }}</p>
        </AppCard>

        <AppCard titulo="Remitente" icono="user">
            <p class="text-sm font-semibold">{{ remitente.nombre_completo }}</p>
            <p class="text-xs text-texto-medio">
                {{ remitente.tipo_documento.toUpperCase() }}: {{ remitente.numero_documento }}<br />
                {{ remitente.direccion }}, {{ remitente.ciudad }}<br />
                <span class="inline-flex items-center gap-1"><Icon name="phone" :size="13" /> {{ remitente.telefono }}</span> <template v-if="remitente.email">· {{ remitente.email }}</template>
            </p>
            <p v-if="!remitente.depositante_es_remitente" class="mt-2 rounded bg-fondo px-2 py-1 text-xs">
                Deposita: <b>{{ remitente.depositante_nombre }}</b> ({{ remitente.depositante_numero_doc }})
            </p>
        </AppCard>

        <AppCard titulo="Destinatario" icono="target">
            <p class="text-sm font-semibold">{{ destinatario.nombre_completo }}</p>
            <p class="text-xs text-texto-medio">
                {{ destinatario.direccion }}, {{ destinatario.ciudad }}<br />
                {{ destinatario.estado_region }} {{ destinatario.codigo_postal }} · {{ destinatario.pais }}<br />
                <span v-if="destinatario.telefono" class="inline-flex items-center gap-1"><Icon name="phone" :size="13" /> {{ destinatario.telefono }}</span>
            </p>
        </AppCard>

        <AppCard v-if="hayArticulosRestringidos && certificado.numero" titulo="Certificado / Licencia" icono="file">
            <p class="text-sm">Cert: {{ certificado.numero }} · Lic: {{ certificado.licencia }}</p>
            <p class="text-xs text-texto-medio">Entidad: {{ certificado.entidad }}</p>
        </AppCard>

        <AppCard v-if="esEncomienda && (paquete.valor_aduanas || paquete.valor_seguro)" titulo="Valores declarados" icono="money">
            <dl class="grid grid-cols-2 gap-y-1 text-sm">
                <dt class="text-texto-medio">Aduanas</dt>
                <dd class="text-right font-semibold">{{ formatearMoneda(paquete.valor_aduanas, divisa) }}</dd>
                <dt class="text-texto-medio">Seguro</dt>
                <dd class="text-right font-semibold">{{ formatearMoneda(paquete.valor_seguro, divisa) }}</dd>
            </dl>
        </AppCard>

        <AppAlert tipo="info" titulo="Después de generar tu código">
            Tienes <b>2 días hábiles</b> para depositar tu envío en la oficina que elegiste. Llévalo abierto y con tu DNI original.
        </AppAlert>
    </div>
</template>
