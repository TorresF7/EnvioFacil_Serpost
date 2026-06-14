<script setup>
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import AppCard from '@/Components/ui/AppCard.vue';
import TimelineSeguimiento from '@/Components/tracking/TimelineSeguimiento.vue';
import ConfigNotificaciones from '@/Components/tracking/ConfigNotificaciones.vue';

const store = useEnvioStore();
const { codigo, servicioActual } = storeToRefs(store);
</script>

<template>
    <div class="space-y-4">
        <div class="text-center">
            <h2 class="text-title font-extrabold text-texto-fuerte">Seguimiento de tu envío</h2>
            <p class="text-body text-texto-medio">Código: <b class="font-mono tracking-wide text-serpost">{{ codigo }}</b></p>
        </div>

        <AppCard titulo="Recibe avisos" icono="bell">
            <ConfigNotificaciones />
        </AppCard>

        <AppCard titulo="Estado del envío" icono="mappin">
            <TimelineSeguimiento
                estado-actual="pendiente_deposito"
                :tiempo-estimado="servicioActual?.tiempo"
            />
        </AppCard>
    </div>
</template>
