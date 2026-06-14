<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PanelLayout from '@/Components/PanelLayout.vue';
import AppCard from '@/Components/ui/AppCard.vue';

const abierto = ref(0);
const alternar = (i) => (abierto.value = abierto.value === i ? -1 : i);

const protocolo = [
    { t: 'Recibir al cliente', i: '👋', p: ['Saluda cordialmente.', '¿Tiene código QR? → SÍ: escanéalo y salta al paso 4.', 'Si NO tiene: continúa con el paso 2.'] },
    { t: 'Orientar sobre requisitos', i: '📋', p: ['Informa el servicio según peso y destino.', 'Indica documentos necesarios (DNI, factura).', 'Verifica que el contenido esté permitido.'] },
    { t: 'Asistir con el formulario', i: '✍️', p: ['Guía el llenado campo por campo.', 'Verifica datos de remitente y destinatario.', 'Confirma descripción y valor de los artículos.'] },
    { t: 'Verificar el envío', i: '🔍', p: ['Revisa el contenido (debe venir abierto).', 'Confirma que no haya artículos prohibidos.', 'Comprueba el embalaje y cierra el envío.'] },
    { t: 'Proceso forense', i: '🪪', p: ['Registra los datos en el sistema.', 'Captura DNI por ambos lados.', 'Toma foto del depositante.', 'Registra huella digital y firma.'] },
    { t: 'Registro en sistema', i: '💻', p: ['Datos de remitente y destinatario.', 'Artículos y documentos adjuntos.', 'Pesa el envío y calcula peso volumétrico.', 'Adhiere el formulario correspondiente.'] },
    { t: 'Cobro y cierre', i: '💳', p: ['Selecciona la forma de pago.', 'Emite el comprobante.', 'Entrega copias al cliente.', 'Informa el tiempo estimado de entrega.'] },
];

const sospechosos = ['Artesanías (revisar huecos)', 'Frascos de shampoo', 'Tacos de zapatos', 'Latas de conservas', 'Juguetes con compartimentos', 'Libros con páginas pegadas'];

const formularios = [
    { s: 'Pequeño Paquete', f: 'CN22', p: 'Hasta 2 kg' },
    { s: 'EMS Express', f: 'CN23 EMS', p: 'Hasta 30 kg' },
    { s: 'Encomienda', f: 'CP72', p: 'Hasta 31.5 kg' },
];
</script>

<template>
    <Head title="Protocolo de Ventanilla" />
    <PanelLayout titulo="Protocolo de atención" subtitulo="Proceso estandarizado para todos los clientes">
        <div class="max-w-4xl space-y-4">
            <AppCard titulo="Protocolo de atención" icono="📑" subtitulo="Mismo proceso para todos los clientes">
                <div class="space-y-2">
                    <div v-for="(paso, i) in protocolo" :key="i" class="overflow-hidden rounded-input border border-borde">
                        <button
                            class="flex w-full items-center gap-2.5 px-3 py-2.5 text-left"
                            :class="abierto === i ? 'bg-serpost-azul/5' : 'bg-white'"
                            @click="alternar(i)"
                        >
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-serpost-azul text-xs font-bold text-white">{{ i + 1 }}</span>
                            <span class="flex-1 text-sm font-semibold text-texto-fuerte">{{ paso.i }} {{ paso.t }}</span>
                            <span class="text-texto-suave">{{ abierto === i ? '▲' : '▼' }}</span>
                        </button>
                        <ul v-if="abierto === i" class="space-y-1.5 border-t border-borde bg-fondo px-4 py-2.5">
                            <li v-for="(sub, j) in paso.p" :key="j" class="flex gap-2 text-sm text-texto-fuerte">
                                <span class="text-serpost-azul">▸</span>{{ sub }}
                            </li>
                        </ul>
                    </div>
                </div>
            </AppCard>

            <AppCard titulo="Envíos sospechosos" icono="🕵️" subtitulo="Inspeccionar con mayor atención">
                <div class="flex flex-wrap gap-1.5">
                    <span v-for="(s, i) in sospechosos" :key="i" class="rounded-full bg-orange-50 px-2.5 py-1 text-xs text-serpost-naranja">⚠️ {{ s }}</span>
                </div>
            </AppCard>

            <AppCard titulo="Peso volumétrico" icono="📐">
                <div class="rounded-input bg-serpost-azul/5 p-3 text-center">
                    <p class="font-mono text-sm font-bold text-serpost-azul">(Largo × Ancho × Alto) ÷ 6000</p>
                    <p class="mt-1 text-xs text-texto-medio">Se cobra el mayor entre el peso real y el volumétrico.</p>
                </div>
            </AppCard>

            <AppCard titulo="Formulario por servicio" icono="📄">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-borde text-left text-xs text-texto-medio">
                            <th class="py-1">Servicio</th><th>Formulario</th><th>Peso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="f in formularios" :key="f.f" class="border-b border-borde/60">
                            <td class="py-1.5">{{ f.s }}</td>
                            <td class="font-bold text-serpost-azul">{{ f.f }}</td>
                            <td class="text-texto-medio">{{ f.p }}</td>
                        </tr>
                    </tbody>
                </table>
            </AppCard>
        </div>
    </PanelLayout>
</template>
