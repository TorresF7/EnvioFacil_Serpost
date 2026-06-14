<script setup>
import { computed, ref } from 'vue';
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import { PAISES } from '@/Utils/constantes';
import { normalizarBusqueda } from '@/Utils/strings';
import { email as validarEmail } from '@/Utils/validaciones';
import AppCard from '@/Components/ui/AppCard.vue';
import AppInput from '@/Components/ui/AppInput.vue';
import AppAlert from '@/Components/ui/AppAlert.vue';
import Icon from '@/Components/ui/Icon.vue';
import BanderaPais from '@/Components/ui/BanderaPais.vue';
import BuscadorDireccion from '@/Components/maps/BuscadorDireccion.vue';
import StepHeader from '@/Components/ui/StepHeader.vue';

const store = useEnvioStore();
const { destinatario, pais } = storeToRefs(store);

const filtroPais = ref('');
const paisesFiltrados = computed(() => {
    const q = normalizarBusqueda(filtroPais.value);
    if (!q) return PAISES;
    return PAISES.filter((p) => normalizarBusqueda(p.nombre).includes(q) || p.codigo.toLowerCase().includes(q));
});

const mostrarRefImportador = ref(!!destinatario.value.referencia_importador);

const errorEmail = computed(() => validarEmail(destinatario.value.email));

function autocompletar(dir) {
    destinatario.value.direccion = dir.direccion || destinatario.value.direccion;
    destinatario.value.ciudad = dir.ciudad || destinatario.value.ciudad;
    destinatario.value.estado_region = dir.estado || destinatario.value.estado_region;
    destinatario.value.codigo_postal = dir.codigo_postal || destinatario.value.codigo_postal;
    if (dir.pais) destinatario.value.pais = dir.pais;
}
</script>

<template>
    <div class="space-y-4">
        <StepHeader titulo-key="enc_destino_t" subtitulo-key="enc_destino_s" />

        <AppCard titulo="País de destino" icono="globe">
            <div v-if="pais" class="surface-tint flex items-center justify-between px-3 py-2.5">
                <span class="flex items-center gap-2 text-body font-semibold"><BanderaPais :codigo="pais.codigo" />{{ pais.nombre }}</span>
                <button class="min-h-[40px] px-2 text-sm font-semibold text-serpost" @click="store.seleccionarPais(null)">Cambiar</button>
            </div>
            <template v-else>
                <input v-model="filtroPais" type="text" placeholder="Busca un país…" aria-label="Buscar país de destino" class="input-base mb-2" />
                <div class="grid max-h-52 grid-cols-2 gap-1.5 overflow-auto sm:grid-cols-3 lg:grid-cols-2">
                    <button
                        v-for="p in paisesFiltrados"
                        :key="p.codigo"
                        type="button"
                        class="flex items-center gap-2 rounded-input border border-borde px-2.5 py-2.5 text-left text-sm hover:border-serpost/50"
                        @click="store.seleccionarPais(p)"
                    >
                        <BanderaPais :codigo="p.codigo" :size="18" />
                        <span class="truncate">{{ p.nombre }}</span>
                    </button>
                </div>
            </template>
        </AppCard>

        <AppCard titulo="¿Quién recibe?" icono="user" subtitulo="Sus datos viajan a la aduana de destino (obligatorio).">
            <AppInput v-model="destinatario.nombre_completo" label="Nombres y apellidos" required />

            <BuscadorDireccion
                label="Buscar dirección"
                help="Escribe la dirección y selecciónala; completamos los campos por ti."
                :pais-codigo="pais?.codigo"
                @seleccionar="autocompletar"
            />

            <AppInput v-model="destinatario.direccion" label="Dirección" required />
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <AppInput v-model="destinatario.ciudad" label="Ciudad" required />
                <AppInput v-model="destinatario.estado_region" label="Estado / Región" />
            </div>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <AppInput
                    v-model="destinatario.codigo_postal"
                    label="Código postal"
                    help="Lo completa el buscador. Es obligatorio para la entrega."
                    required
                />
                <AppInput v-model="destinatario.pais" label="País" required />
            </div>
            <AppInput v-model="destinatario.telefono" label="Teléfono (con código de país)" inputmode="tel" placeholder="Ej: +1 305..." />
            <AppInput
                v-model="destinatario.email"
                label="Email (opcional)"
                type="email"
                :error="errorEmail"
                data-field="destinatario_email"
                :data-error-code="errorEmail ? 'email_invalido' : null"
            />
        </AppCard>

        <details class="surface-flat" :open="!!destinatario.empresa || mostrarRefImportador">
            <summary class="flex cursor-pointer list-none items-center gap-2 px-4 py-3 text-body font-bold text-texto-fuerte">
                <Icon name="building" :size="18" class="text-serpost" />¿Va a una empresa? (opcional)
                <Icon name="chevron-down" :size="18" class="ml-auto text-texto-suave" />
            </summary>
            <div class="space-y-1 border-t border-borde px-4 py-3">
                <AppInput v-model="destinatario.empresa" label="Empresa" />
                <AppInput
                    v-model="destinatario.referencia_importador"
                    label="Referencia del importador"
                    help="Código que algunas aduanas/empresas piden. Déjalo vacío si no lo tienes."
                />
            </div>
        </details>

        <AppAlert tipo="warning" titulo="Verifica la dirección">
            Confirma la dirección y el código postal con quien recibe antes de continuar. Un error retrasa la entrega.
        </AppAlert>
    </div>
</template>
