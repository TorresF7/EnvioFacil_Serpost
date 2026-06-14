<script setup>
import { computed } from 'vue';
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import { useI18n } from '@/Composables/useI18n';
import { ARTICULOS_COMUNES, ESTADO_VISUAL } from '@/Utils/constantes';
import { COLOR_POR_ESTADO } from '@/Utils/semaforoNormativa';
import { clasificar, esDescripcionVaga, requisitoArticulo } from '@/Composables/useVerificadorProducto';
import { vAutoAnimate } from '@formkit/auto-animate/vue';
import Icon from '@/Components/ui/Icon.vue';
import VeredictoSemaforo from '@/Components/VeredictoSemaforo.vue';

defineProps({
    modo: { type: String, default: 'detalle' },
});

const store = useEnvioStore();
const { articulos, divisa } = storeToRefs(store);
const { t } = useI18n();

const gruposEjemplos = computed(() =>
    [
        { color: 'verde', titulo: t('grupo_verde') },
        { color: 'ambar', titulo: t('grupo_ambar') },
        { color: 'rojo', titulo: t('grupo_rojo') },
    ].map((g) => ({
        ...g,
        items: ARTICULOS_COMUNES.filter((a) => COLOR_POR_ESTADO[a.estado] === g.color),
    })),
);
</script>

<template>
    <div v-auto-animate class="space-y-3">
        <div
            v-for="(articulo, i) in articulos"
            :key="i"
            class="rounded-input border border-borde bg-[#FAFAFA] p-3"
        >
            <div class="mb-2 flex items-center justify-between">
                <span class="text-xs font-semibold text-texto-medio">Artículo {{ i + 1 }}</span>
                <button
                    v-if="articulos.length > 1"
                    type="button"
                    class="flex min-h-[44px] items-center rounded-input px-2 py-1 text-xs font-semibold text-serpost hover:underline"
                    @click="store.eliminarArticulo(i)"
                >
                    Quitar
                </button>
            </div>

            <template v-if="modo === 'producto'">
                <label class="block text-sm font-semibold text-texto-fuerte">
                    ¿Qué vas a enviar?
                    <input
                        v-model="articulo.descripcion"
                        type="text"
                        placeholder="Ej: chompa de alpaca, paracetamol, café tostado…"
                        aria-label="Describe el producto, sin marcas"
                        class="input-base mt-1 bg-white text-base"
                        data-field="articulo_descripcion"
                        @input="store.reverificarArticulo(i)"
                    />
                </label>
                <p class="mt-1 text-xs text-texto-suave">Escríbelo en palabras simples, sin marcas.</p>

                <p
                    v-if="esDescripcionVaga(articulo.descripcion)"
                    class="mt-2 flex items-start gap-1.5 rounded-input border border-ambar/40 bg-ambar-tint px-3 py-2 text-xs font-semibold text-ambar"
                    role="status"
                    aria-live="polite"
                >
                    <Icon name="triangle-alert" :size="16" class="mt-0.5 shrink-0" />
                    Eso es muy general. ¿Qué hay dentro? Ej: 2 polos de algodón, 1 chompa de alpaca.
                </p>

                <div v-else-if="articulo.descripcion.trim().length >= 2" class="mt-2">
                    <VeredictoSemaforo :veredicto="clasificar(articulo.descripcion)" />
                </div>
            </template>

            <template v-else>
                <p class="mb-2 flex items-center gap-2 text-sm font-bold text-texto-fuerte">
                    <Icon :name="ESTADO_VISUAL[articulo.categoria].icono" :size="18" :class="ESTADO_VISUAL[articulo.categoria].clase" />
                    {{ articulo.descripcion || 'Producto sin describir' }}
                </p>

                <p
                    v-if="articulo.categoria === 'restringido' && requisitoArticulo(articulo.descripcion)"
                    class="mb-2 flex items-start gap-1.5 rounded-input border border-ambar/40 bg-ambar-tint px-2.5 py-1.5 text-[11px] font-semibold text-ambar"
                >
                    <Icon name="file" :size="14" class="mt-0.5 shrink-0" />
                    <span>
                        Requiere: {{ requisitoArticulo(articulo.descripcion).documento || 'permiso especial' }}
                        <template v-if="requisitoArticulo(articulo.descripcion).entidad">
                            · emitido por {{ requisitoArticulo(articulo.descripcion).entidad }}
                        </template>
                    </span>
                </p>

                <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                    <label class="text-[11px] font-medium text-texto-medio">
                        Cantidad
                        <input v-model.number="articulo.cantidad" type="number" min="1" inputmode="numeric" class="input-base mt-0.5 bg-white" data-field="articulo_cantidad" />
                    </label>
                    <label class="text-[11px] font-medium text-texto-medio">
                        Peso de este producto (g)
                        <input v-model.number="articulo.peso_neto_gramos" type="number" min="1" inputmode="numeric" placeholder="gramos" class="input-base mt-0.5 bg-white" data-field="articulo_peso" />
                    </label>
                    <label class="text-[11px] font-medium text-texto-medio">
                        ¿Cuánto cuesta? ({{ divisa }})
                        <input v-model.number="articulo.valor" type="number" min="0" step="0.01" inputmode="decimal" class="input-base mt-0.5 bg-white" data-field="articulo_valor" />
                    </label>
                    <label class="text-[11px] font-medium text-texto-medio">
                        Hecho en (país)
                        <input v-model="articulo.pais_origen" type="text" class="input-base mt-0.5 bg-white" data-field="articulo_pais_origen" />
                    </label>
                </div>
            </template>
        </div>

        <button
            v-if="modo === 'producto'"
            type="button"
            class="flex min-h-[56px] w-full items-center justify-center rounded-input border-2 border-dashed border-serpost/40 py-2.5 text-sm font-semibold text-serpost transition hover:bg-serpost/5"
            @click="store.agregarArticulo()"
        >
            <Icon name="plus" :size="18" class="mr-1" />{{ t('agregar_producto') }}
        </button>

        <div v-if="modo === 'producto'" class="rounded-input border border-borde bg-surface2 p-3">
            <p class="mb-2 text-caption font-bold uppercase tracking-wide text-texto-suave">{{ t('ejemplos_label') }}</p>
            <div class="space-y-2.5">
                <div v-for="grupo in gruposEjemplos" :key="grupo.color">
                    <p class="mb-1 text-[11px] font-semibold text-texto-medio">{{ grupo.titulo }}</p>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="art in grupo.items"
                            :key="art.nombre"
                            type="button"
                            class="inline-flex min-h-[56px] items-center gap-1.5 rounded-input border px-4 py-2 text-caption font-medium transition hover:brightness-95"
                            :class="ESTADO_VISUAL[art.estado].fondo"
                            @click="store.agregarDesdeEjemplo(art.nombre)"
                        >
                            <Icon :name="ESTADO_VISUAL[art.estado].icono" :size="14" :class="ESTADO_VISUAL[art.estado].clase" />
                            {{ art.nombre }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
