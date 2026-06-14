<script setup>
const props = defineProps({
    servicioActual: String,
    interactivo: { type: Boolean, default: false },
    datos: {
        type: Object,
        default: () => ({
            compartidos: [
                'Datos del remitente',
                'Datos del destinatario',
                'Artículos (descripción, cantidad, peso, valor, origen, HS)',
                'País de destino y oficina',
                'Naturaleza del envío',
                'Peso del paquete',
            ],
            formularios: {
                pp: { nombre: 'Pequeño Paquete', formulario: 'CN22', especificos: ['Hasta 2 kg', 'Declaración simple'] },
                ems: { nombre: 'EMS Express', formulario: 'CN23 EMS', especificos: ['Hasta 30 kg', 'Porte / flete', 'Seguro', 'Certificados y licencias'] },
                encomienda: { nombre: 'Encomienda', formulario: 'CP72', especificos: ['Hasta 31.5 kg', 'Valor declarado', 'Instrucciones de no entrega', 'Porte / cobro'] },
            },
        }),
    },
});

const emit = defineEmits(['cambiar']);

function seleccionar(clave) {
    if (props.interactivo && clave !== props.servicioActual) emit('cambiar', clave);
}
</script>

<template>
    <div class="space-y-3">
        <div class="rounded-input border border-green-200 bg-green-50 p-3">
            <p class="text-xs font-bold text-green-800">✓ Estos datos se conservan al cambiar de formulario</p>
            <ul class="mt-1.5 grid grid-cols-1 gap-0.5">
                <li v-for="(c, i) in datos.compartidos" :key="i" class="text-xs text-green-900">• {{ c }}</li>
            </ul>
        </div>

        <div class="grid gap-2">
            <button
                v-for="(f, clave) in datos.formularios"
                :key="clave"
                type="button"
                :disabled="!interactivo"
                class="rounded-input border-2 p-2.5 text-left transition"
                :class="[
                    servicioActual === clave ? 'border-serpost bg-serpost/5' : 'border-borde',
                    interactivo && servicioActual !== clave ? 'hover:border-serpost/50 cursor-pointer' : '',
                    !interactivo ? 'cursor-default' : '',
                ]"
                @click="seleccionar(clave)"
            >
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-texto-fuerte">{{ f.nombre }}</span>
                    <span class="rounded bg-fondo px-2 py-0.5 text-[11px] font-semibold">{{ f.formulario }}</span>
                </div>
                <div class="mt-1 flex flex-wrap gap-1">
                    <span v-for="(e, j) in f.especificos" :key="j" class="rounded-full bg-fondo px-2 py-0.5 text-[10px] text-texto-medio">{{ e }}</span>
                </div>
                <p v-if="interactivo && servicioActual === clave" class="mt-1 text-[10px] font-semibold text-serpost">Formulario actual</p>
            </button>
        </div>
    </div>
</template>
