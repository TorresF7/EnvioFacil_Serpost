<script setup>
import { computed } from 'vue';
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import { useConsultaDni } from '@/Composables/useConsultaDni';
import { TIPOS_DOCUMENTO } from '@/Utils/constantes';
import { documento as validarDocumento, email as validarEmail } from '@/Utils/validaciones';
import AppCard from '@/Components/ui/AppCard.vue';
import AppInput from '@/Components/ui/AppInput.vue';
import AppSelect from '@/Components/ui/AppSelect.vue';
import AppAlert from '@/Components/ui/AppAlert.vue';
import Icon from '@/Components/ui/Icon.vue';
import BuscadorDireccion from '@/Components/maps/BuscadorDireccion.vue';
import SeccionDepositante from '@/Components/forms/SeccionDepositante.vue';
import StepHeader from '@/Components/ui/StepHeader.vue';

const store = useEnvioStore();
const { remitente } = storeToRefs(store);

const errorDoc = computed(() =>
    remitente.value.numero_documento ? validarDocumento(remitente.value.tipo_documento, remitente.value.numero_documento) : null,
);
const errorEmail = computed(() => validarEmail(remitente.value.email));

const dniConsultable = computed(() =>
    remitente.value.tipo_documento === 'dni' ? remitente.value.numero_documento : '',
);
const { cargando: cargandoDni, verificado: dniVerificado } = useConsultaDni(dniConsultable, {

    onNombre: (nombre) => { if (!remitente.value.nombre_completo?.trim()) remitente.value.nombre_completo = nombre; },
});

function autocompletar(dir) {
    remitente.value.direccion = dir.direccion || remitente.value.direccion;
    remitente.value.ciudad = dir.ciudad || remitente.value.ciudad;
    remitente.value.departamento = dir.estado || remitente.value.departamento;
    remitente.value.codigo_postal = dir.codigo_postal || remitente.value.codigo_postal;
}
</script>

<template>
    <div class="space-y-4">
        <StepHeader titulo-key="enc_remitente_t" subtitulo-key="enc_remitente_s" />

        <AppCard titulo="Identificación" icono="id-card">
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <AppSelect v-model="remitente.tipo_documento" label="Tipo de documento" :options="TIPOS_DOCUMENTO" required />
                <AppInput
                    v-model="remitente.numero_documento"
                    label="N° de documento"
                    :error="errorDoc"
                    inputmode="numeric"
                    required
                    data-field="remitente_dni"
                    :data-error-code="errorDoc ? 'documento_invalido' : null"
                />
            </div>
            <AppInput v-model="remitente.nombre_completo" label="Nombres y apellidos" required />
            <p v-if="cargandoDni" class="-mt-1 text-xs text-texto-suave">Consultando RENIEC…</p>
            <p v-else-if="dniVerificado" class="-mt-1 flex items-center gap-1 text-xs font-semibold text-go">
                <Icon name="circle-check" :size="14" /> Verificado con RENIEC <span class="text-texto-suave">(simulado)</span>
            </p>

            <details class="mt-1 rounded-input border border-borde bg-surface2" :open="!!remitente.empresa || !!remitente.ruc">
                <summary class="flex cursor-pointer list-none items-center gap-2 px-3 py-2.5 text-sm font-semibold text-texto-fuerte">
                    <Icon name="building" :size="18" class="text-serpost" />¿Envías como empresa? (opcional)
                    <Icon name="chevron-down" :size="18" class="ml-auto text-texto-suave" />
                </summary>
                <div class="grid grid-cols-1 gap-2 border-t border-borde px-3 py-3 sm:grid-cols-2">
                    <AppInput v-model="remitente.empresa" label="Empresa" />
                    <AppInput v-model="remitente.ruc" label="RUC" inputmode="numeric" />
                </div>
            </details>
        </AppCard>

        <AppCard titulo="Dirección en Perú" icono="mappin">
            <BuscadorDireccion
                pais-codigo="pe"
                help="Busca y selecciona; completamos los campos por ti."
                @seleccionar="autocompletar"
            />
            <AppInput v-model="remitente.direccion" label="Dirección" required />
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <AppInput v-model="remitente.ciudad" label="Ciudad" required />
                <AppInput v-model="remitente.departamento" label="Departamento" />
            </div>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <AppInput v-model="remitente.codigo_postal" label="Código postal" />
                <AppInput v-model="remitente.telefono" label="Teléfono" inputmode="tel" required />
            </div>
            <AppInput
                v-model="remitente.email"
                label="Email"
                type="email"
                :error="errorEmail"
                data-field="remitente_email"
                :data-error-code="errorEmail ? 'email_invalido' : null"
            />
        </AppCard>

        <AppCard titulo="¿Quién deposita el envío?" icono="handshake">
            <SeccionDepositante />
        </AppCard>

        <AppAlert tipo="warning" titulo="Lleva tu DNI original">
            En ventanilla harán el proceso forense: foto del documento (ambos lados) y registro de huella digital.
        </AppAlert>
    </div>
</template>
