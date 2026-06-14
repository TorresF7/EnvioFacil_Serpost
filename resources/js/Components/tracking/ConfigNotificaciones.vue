<script setup>
import { ref } from 'vue';
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import AppInput from '@/Components/ui/AppInput.vue';
import AppButton from '@/Components/ui/AppButton.vue';
import AppAlert from '@/Components/ui/AppAlert.vue';

const store = useEnvioStore();
const { notificaciones } = storeToRefs(store);
const guardado = ref(false);

function activar() {
    notificaciones.value.activas = true;
    guardado.value = true;
}
</script>

<template>
    <div class="space-y-3">
        <p class="text-sm text-texto-medio">Te avisamos cada vez que tu envío cambie de estado.</p>
        <AppInput v-model="notificaciones.email" label="Email" type="email" placeholder="tucorreo@ejemplo.com" />
        <AppInput v-model="notificaciones.whatsapp" label="WhatsApp / SMS" inputmode="tel" placeholder="+51 9..." />

        <AppButton variante="verde" bloque @click="activar">🔔 Activar notificaciones</AppButton>

        <AppAlert v-if="guardado" tipo="success" titulo="¡Notificaciones activadas!">
            Te avisaremos por
            <template v-if="notificaciones.email">correo</template>
            <template v-if="notificaciones.email && notificaciones.whatsapp"> y </template>
            <template v-if="notificaciones.whatsapp">WhatsApp/SMS</template>
            sobre el estado de tu envío.
        </AppAlert>
    </div>
</template>
