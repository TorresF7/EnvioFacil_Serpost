<script setup>
import { computed, ref, watch } from 'vue';
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import { usePesoCalculador } from '@/Composables/usePesoCalculador';
import { SERVICIOS, MODALIDADES, VIAS, INSTRUCCIONES_NO_ENTREGA } from '@/Utils/constantes';
import { formatearPeso } from '@/Utils/formateadores';
import AppCard from '@/Components/ui/AppCard.vue';
import AppInput from '@/Components/ui/AppInput.vue';
import AppSelect from '@/Components/ui/AppSelect.vue';
import AppAlert from '@/Components/ui/AppAlert.vue';
import Icon from '@/Components/ui/Icon.vue';
import MapaOficinas from '@/Components/maps/MapaOficinas.vue';
import StepHeader from '@/Components/ui/StepHeader.vue';

const store = useEnvioStore();
const {
    paquete, servicio, oficina, oficinasDisponibles,
    esEncomienda, servicioActual, servicioRecomendado, porqueServicio,
    pesoNetoTotalGramos, pesoBrutoConsistente,
} = storeToRefs(store);
const { pesoVolumetrico, pesoFacturable, cobraVolumetrico } = usePesoCalculador(paquete);

const eligioManual = ref(false);
watch(servicioRecomendado, (rec) => {
    if (rec && !eligioManual.value) store.seleccionarServicio(rec);
}, { immediate: true });

function elegirManual(valor) {
    eligioManual.value = true;
    store.seleccionarServicio(valor);
}

const otrasOpciones = computed(() => SERVICIOS.filter((s) => s.valor !== servicio.value));
const muestraModalidad = computed(() => servicioActual.value && servicioActual.value.valor !== 'pp');
const pesoExcedido = computed(() => Number(paquete.value.peso_bruto) > 31.5);
</script>

<template>
    <div class="space-y-4">
        <StepHeader titulo-key="enc_servicio_t" subtitulo-key="enc_servicio_s" />

        <AppCard titulo="¿Cuánto pesa todo?" icono="scale" subtitulo="El peso total nos dice qué servicio te conviene.">
            <AppInput
                v-model.number="paquete.peso_bruto"
                label="Peso total (kg)"
                type="number"
                inputmode="decimal"
                placeholder="Ej: 1.250"
                help="Pesa el paquete completo, ya embalado."
                required
            />

            <details class="rounded-input border border-borde bg-surface2">
                <summary class="flex cursor-pointer list-none items-center gap-2 px-3 py-2.5 text-sm font-semibold text-texto-fuerte">
                    <Icon name="ruler" :size="18" class="text-serpost" />Agregar medidas (opcional)
                    <Icon name="chevron-down" :size="18" class="ml-auto text-texto-suave" />
                </summary>
                <div class="border-t border-borde px-3 py-3">
                    <div class="grid grid-cols-3 gap-2">
                        <AppInput v-model.number="paquete.largo_cm" label="Largo (cm)" type="number" inputmode="numeric" />
                        <AppInput v-model.number="paquete.ancho_cm" label="Ancho (cm)" type="number" inputmode="numeric" />
                        <AppInput v-model.number="paquete.alto_cm" label="Alto (cm)" type="number" inputmode="numeric" />
                    </div>
                    <div class="rounded-input bg-fondo p-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-texto-medio">Peso volumétrico</span>
                            <span class="font-semibold">{{ formatearPeso(pesoVolumetrico) }}</span>
                        </div>
                        <div class="mt-1 flex justify-between">
                            <span class="text-texto-medio">Peso a cobrar</span>
                            <span class="font-bold text-serpost">{{ formatearPeso(pesoFacturable) }}</span>
                        </div>
                        <p v-if="cobraVolumetrico" class="mt-1 text-[11px] font-semibold text-serpost-naranja">
                            ⚠️ Por su volumen, se cobrará el peso volumétrico.
                        </p>
                    </div>
                </div>
            </details>

            <AppAlert v-if="!pesoBrutoConsistente" tipo="warning" titulo="Revisa el peso total">
                La suma del peso de tus productos ({{ formatearPeso(pesoNetoTotalGramos / 1000) }}) es mayor
                que el peso total que ingresaste. El peso total —ya embalado— debe incluir el de todos los productos.
            </AppAlert>
        </AppCard>

        <AppCard titulo="Servicio recomendado" icono="sparkles">
            <p v-if="!paquete.peso_bruto" class="text-sm text-texto-medio">
                Ingresa el peso arriba y te recomendamos el servicio ideal.
            </p>
            <AppAlert v-else-if="pesoExcedido" tipo="error" titulo="Peso muy alto">
                El máximo por envío postal es 31.5 kg. Reduce el peso o divídelo en varios envíos.
            </AppAlert>

            <template v-else-if="servicioActual">
                <div class="rounded-card border-2 border-serpost bg-serpost-tint p-4">
                    <div class="flex items-start gap-3">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xs bg-serpost text-white">
                            <Icon :name="servicioActual.icono" :size="24" />
                        </span>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-base font-bold text-texto-fuerte">{{ servicioActual.nombre }}</p>
                                <span class="rounded-full bg-serpost-verde/10 px-2 py-0.5 text-[11px] font-semibold text-serpost-verde">
                                    Recomendado
                                </span>
                            </div>
                            <p class="text-sm text-texto-medio">{{ servicioActual.resumen }}</p>
                            <p class="mt-1 text-xs text-texto-suave">🕒 Tiempo estimado: {{ servicioActual.tiempo }}</p>
                        </div>
                    </div>
                </div>

                <p v-if="porqueServicio" class="mt-2 flex items-start gap-1.5 rounded-input bg-fondo px-3 py-2 text-xs text-texto-medio">
                    <Icon name="info" :size="14" class="mt-0.5 shrink-0 text-serpost" />
                    <span>{{ porqueServicio }} <span class="text-texto-suave">Según la Directiva N° 003-G/21 (UPU).</span></span>
                </p>

                <details class="mt-2 rounded-input border border-borde bg-surface">
                    <summary class="flex cursor-pointer list-none items-center gap-2 px-3 py-2.5 text-sm font-semibold text-texto-fuerte">
                        <Icon name="refresh" :size="18" class="text-serpost" />Ver otras opciones
                        <Icon name="chevron-down" :size="18" class="ml-auto text-texto-suave" />
                    </summary>
                    <div class="space-y-2 border-t border-borde px-3 py-3">
                        <button
                            v-for="s in otrasOpciones"
                            :key="s.valor"
                            type="button"
                            class="flex min-h-[56px] w-full items-center gap-3 rounded-input border border-borde px-3 py-2 text-left hover:border-serpost/50"
                            @click="elegirManual(s.valor)"
                        >
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xs bg-serpost-tint text-serpost">
                                <Icon :name="s.icono" :size="20" />
                            </span>
                            <span class="flex-1">
                                <span class="block text-sm font-bold text-texto-fuerte">{{ s.nombre }}</span>
                                <span class="block text-xs text-texto-medio">{{ s.resumen }} · 🕒 {{ s.tiempo }}</span>
                            </span>
                        </button>
                        <p class="text-[11px] text-texto-suave">Tus datos se conservan aunque cambies de servicio.</p>
                    </div>
                </details>
            </template>
        </AppCard>

        <AppCard titulo="¿Dónde lo dejarás?" icono="office" subtitulo="Elige la oficina SERPOST donde depositarás tu envío.">
            <MapaOficinas
                :oficinas="oficinasDisponibles"
                :seleccionada="oficina"
                @seleccionar="store.seleccionarOficina"
            />
            <div class="mt-2 grid gap-1.5">
                <button
                    v-for="of in oficinasDisponibles"
                    :key="of.codigo"
                    type="button"
                    class="flex min-h-[56px] items-center justify-between rounded-input border px-3 py-2 text-left text-sm"
                    :class="oficina?.codigo === of.codigo ? 'border-serpost bg-serpost/5' : 'border-borde'"
                    @click="store.seleccionarOficina(of)"
                >
                    <span><b>{{ of.nombre }}</b> · {{ of.ciudad }}</span>
                    <span v-if="oficina?.codigo === of.codigo" class="text-serpost">✓</span>
                </button>
            </div>
        </AppCard>

        <details v-if="muestraModalidad" class="surface-flat">
            <summary class="flex cursor-pointer list-none items-center gap-2 px-4 py-3 text-body font-bold text-texto-fuerte">
                <Icon name="plane" :size="18" class="text-serpost" />Opciones de envío (opcional)
                <Icon name="chevron-down" :size="18" class="ml-auto text-texto-suave" />
            </summary>
            <div class="border-t border-borde px-4 py-3">
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    <AppSelect v-model="paquete.modalidad" label="Rapidez" placeholder="—" :options="MODALIDADES" />
                    <AppSelect v-model="paquete.via" label="Vía" placeholder="—" :options="VIAS" />
                </div>
                <AppInput v-model.number="paquete.gastos_porte" label="Gastos de porte / flete (opcional)" type="number" inputmode="decimal" />
            </div>
        </details>

        <AppCard v-if="esEncomienda" titulo="Datos de la encomienda" icono="package" subtitulo="Requeridos para encomiendas grandes.">
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <AppInput v-model.number="paquete.valor_aduanas" label="Valor declarado (aduanas)" type="number" inputmode="decimal" />
                <AppInput v-model.number="paquete.valor_seguro" label="Valor declarado (seguro)" type="number" inputmode="decimal" />
            </div>
            <AppInput v-model.number="paquete.cantidad_bultos" label="Cantidad de bultos" type="number" inputmode="numeric" />
            <AppSelect
                v-model="paquete.instruccion_no_entrega"
                label="Si no se puede entregar…"
                placeholder="Selecciona una opción"
                :options="INSTRUCCIONES_NO_ENTREGA"
            />
            <div v-if="paquete.instruccion_no_entrega" class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <AppInput v-model.number="paquete.devolver_dias" label="Devolver tras N días" type="number" inputmode="numeric" />
                <AppInput
                    v-if="paquete.instruccion_no_entrega === 'redirigir'"
                    v-model="paquete.direccion_redireccion"
                    label="Dirección alternativa"
                    required
                />
            </div>
        </AppCard>

        <AppCard titulo="Observaciones" icono="edit">
            <AppInput v-model="paquete.observaciones" label="Notas (opcional)" placeholder="Ej: Frágil, manipular con cuidado" />
        </AppCard>

        <AppAlert tipo="info" titulo="Tip de embalaje">
            Lleva el paquete <b>abierto</b>. Usa cartón resistente; en ventanilla lo cerrarán y sellarán.
        </AppAlert>
    </div>
</template>
