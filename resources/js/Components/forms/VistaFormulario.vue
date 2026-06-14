<script setup>
import { computed } from 'vue';
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import { formatearMoneda } from '@/Utils/formateadores';

const store = useEnvioStore();
const { servicioActual, pais, oficina, naturaleza, divisa, tipoEnvio, articulos, remitente, destinatario, codigo } = storeToRefs(store);

const totales = computed(() => ({
    cantidad: articulos.value.reduce((s, a) => s + Number(a.cantidad || 0), 0),
    peso: articulos.value.reduce((s, a) => s + Number(a.peso_neto_gramos || 0), 0),
    valor: articulos.value.reduce((s, a) => s + Number(a.valor || 0), 0),
}));

const categorias = ['regalo', 'venta', 'muestra', 'documentos', 'devolucion', 'variado'];
const categoriaLabel = { regalo: 'Regalo', venta: 'Venta', muestra: 'Muestra', documentos: 'Documentos', devolucion: 'Devolución', variado: 'Otro' };
</script>

<template>
    <div class="rounded border-2 border-texto-fuerte bg-white p-3 text-[11px] leading-tight text-texto-fuerte">
        <div class="flex items-center justify-between border-b-2 border-texto-fuerte pb-2">
            <div>
                <p class="text-sm font-extrabold">SERPOST</p>
                <p class="text-[10px]">Declaración de Aduana</p>
            </div>
            <div class="text-right">
                <p class="text-base font-extrabold">{{ servicioActual?.formulario }}</p>
                <p class="text-[10px]">{{ servicioActual?.nombre }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 border-b border-texto-fuerte py-2">
            <div>
                <p class="font-bold underline">Remitente</p>
                <p>{{ remitente.nombre_completo }}</p>
                <p v-if="remitente.empresa">Empresa: {{ remitente.empresa }}</p>
                <p>{{ remitente.tipo_documento?.toUpperCase() }}: {{ remitente.numero_documento }}</p>
                <p v-if="remitente.ruc">RUC: {{ remitente.ruc }}</p>
                <p>{{ remitente.direccion }}</p>
                <p>{{ remitente.ciudad }} {{ remitente.codigo_postal }}, Perú</p>
                <p>Tel: {{ remitente.telefono }}</p>
            </div>
            <div>
                <p class="font-bold underline">Destinatario</p>
                <p>{{ destinatario.nombre_completo }}</p>
                <p v-if="destinatario.empresa">Empresa: {{ destinatario.empresa }}</p>
                <p>{{ destinatario.direccion }}</p>
                <p>{{ destinatario.ciudad }} {{ destinatario.codigo_postal }}</p>
                <p>{{ destinatario.estado_region }}, {{ destinatario.pais }}</p>
                <p>Tel: {{ destinatario.telefono }}</p>
            </div>
        </div>

        <table class="w-full border-collapse py-1 text-[10px]">
            <thead>
                <tr class="border-b border-texto-fuerte text-left">
                    <th class="py-0.5">Cant.</th>
                    <th>Descripción detallada</th>
                    <th>HS</th>
                    <th class="text-right">Peso(g)</th>
                    <th class="text-right">Valor</th>
                    <th>Origen</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(a, i) in articulos" :key="i" class="border-b border-borde">
                    <td class="py-0.5">{{ a.cantidad }}</td>
                    <td>{{ a.descripcion }}</td>
                    <td>{{ a.codigo_hs || '—' }}</td>
                    <td class="text-right">{{ a.peso_neto_gramos }}</td>
                    <td class="text-right">{{ formatearMoneda(a.valor, divisa) }}</td>
                    <td>{{ a.pais_origen }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="font-bold">
                    <td>{{ totales.cantidad }}</td>
                    <td colspan="2">TOTALES</td>
                    <td class="text-right">{{ totales.peso }}</td>
                    <td class="text-right">{{ formatearMoneda(totales.valor, divisa) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div class="border-y border-texto-fuerte py-1.5">
            <p class="font-bold">Categoría del envío:</p>
            <div class="mt-0.5 flex flex-wrap gap-x-3 gap-y-0.5">
                <span v-for="c in categorias" :key="c">
                    <span class="inline-block h-2.5 w-2.5 border border-texto-fuerte align-middle"
                        :class="{ 'bg-texto-fuerte': naturaleza === c }" />
                    {{ categoriaLabel[c] }}
                </span>
            </div>
            <p class="mt-1 text-[10px]">Tipo: {{ tipoEnvio }} · Oficina de depósito: {{ oficina?.nombre }}</p>
        </div>

        <p class="py-1.5 text-[9px] italic">
            Declaro que los datos consignados en la presente declaración son exactos y que este envío no contiene
            ningún objeto peligroso o prohibido por la reglamentación postal o aduanera.
        </p>

        <div class="flex items-end justify-between pt-2">
            <div>
                <div class="h-8 w-32 border-b border-texto-fuerte"></div>
                <p class="text-[9px]">Firma y huella del remitente</p>
            </div>
            <div class="rounded border border-dashed border-serpost px-2 py-1 text-center text-serpost">
                <p class="text-[9px] font-bold">PRE-ADMISIÓN DIGITAL</p>
                <p class="text-[11px] font-extrabold">{{ codigo ?? '—' }}</p>
            </div>
        </div>
    </div>
</template>
