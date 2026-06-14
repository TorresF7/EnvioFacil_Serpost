<script setup>
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import { TIPOS_DOCUMENTO } from '@/Utils/constantes';
import AppInput from '@/Components/ui/AppInput.vue';
import AppSelect from '@/Components/ui/AppSelect.vue';

const store = useEnvioStore();
const { remitente } = storeToRefs(store);
</script>

<template>
    <div>
        <label class="flex cursor-pointer items-center gap-2.5 rounded-input border border-borde bg-[#FAFAFA] px-3 py-2.5">
            <input
                v-model="remitente.depositante_es_remitente"
                type="checkbox"
                class="h-4 w-4 accent-serpost"
            />
            <span class="text-sm font-medium text-texto-fuerte">Yo mismo depositaré el envío</span>
        </label>

        <div v-if="!remitente.depositante_es_remitente" class="mt-3 space-y-1 rounded-input bg-fondo p-3">
            <p class="mb-1 text-xs font-semibold text-texto-medio">Datos de quien deposita</p>
            <AppInput v-model="remitente.depositante_nombre" label="Nombre completo" required />
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <AppSelect v-model="remitente.depositante_tipo_doc" label="Tipo de doc." :options="TIPOS_DOCUMENTO" />
                <AppInput v-model="remitente.depositante_numero_doc" label="N° de documento" required />
            </div>
            <AppInput v-model="remitente.depositante_direccion" label="Dirección del depositante" />
        </div>
    </div>
</template>
