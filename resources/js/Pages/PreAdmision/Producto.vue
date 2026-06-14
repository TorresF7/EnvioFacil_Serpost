<script setup>
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import { useI18n } from '@/Composables/useI18n';
import AppCard from '@/Components/ui/AppCard.vue';
import AppAlert from '@/Components/ui/AppAlert.vue';
import Icon from '@/Components/ui/Icon.vue';
import TablaArticulos from '@/Components/forms/TablaArticulos.vue';
import StepHeader from '@/Components/ui/StepHeader.vue';

const { t } = useI18n();
const store = useEnvioStore();
const { hayArticulosBloqueados } = storeToRefs(store);

const proceso = [
    { icono: 'edit', titulo: 'Registra tu envío', texto: 'Completa los datos desde tu celular en pocos minutos.' },
    { icono: 'qr', titulo: 'Recibe tu código QR', texto: 'Te damos un código para mostrar en ventanilla.' },
    { icono: 'office', titulo: 'Ve a la oficina', texto: 'El personal escanea tu código, sin volver a digitar.' },
    { icono: 'package', titulo: 'Deposita y listo', texto: 'Pesan, cobran y tu envío sale. Atención en ~5 min.' },
];

const requisitos = [
    'DNI original (para el proceso forense: foto + huella)',
    'El envío abierto, para que verifiquen el contenido',
    'Factura o boleta si no es un regalo',
    'Embalaje resistente (caja o sobre acolchado)',
];
</script>

<template>
    <div class="space-y-section">
        <StepHeader titulo-key="bienvenida_titulo" subtitulo-key="bienvenida_sub" />

        <AppCard :titulo="t('producto_titulo')" icono="package" :subtitulo="t('producto_sub')" enfasis>
            <AppAlert v-if="hayArticulosBloqueados" tipo="error" titulo="Hay algo que no se puede enviar">
                Uno o más productos no se admiten por vía postal. Quítalos para poder continuar.
            </AppAlert>
            <div class="mt-2">
                <TablaArticulos modo="producto" />
            </div>
        </AppCard>

        <details class="surface-flat">
            <summary class="flex cursor-pointer list-none items-center gap-2 px-4 py-3 text-body font-bold text-texto-fuerte">
                <Icon name="compass" :size="18" class="text-serpost" />{{ t('como_funciona') }}
                <Icon name="chevron-down" :size="18" class="ml-auto text-texto-suave" />
            </summary>
            <div class="space-y-section border-t border-borde px-4 py-3">
                <ol class="space-y-3">
                    <li v-for="(p, i) in proceso" :key="i" class="flex gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xs bg-serpost-tint text-serpost">
                            <Icon :name="p.icono" :size="18" />
                        </span>
                        <div>
                            <p class="text-body font-semibold text-texto-fuerte">{{ i + 1 }}. {{ p.titulo }}</p>
                            <p class="text-caption text-texto-medio">{{ p.texto }}</p>
                        </div>
                    </li>
                </ol>
                <div>
                    <p class="mb-2 flex items-center gap-1.5 text-body font-bold text-texto-fuerte">
                        <Icon name="check" :size="18" class="text-go" />{{ t('que_llevar') }}
                    </p>
                    <ul class="space-y-2">
                        <li v-for="(r, i) in requisitos" :key="i" class="flex items-start gap-2 text-body text-texto-fuerte">
                            <Icon name="check" :size="16" class="mt-0.5 shrink-0 text-go" />{{ r }}
                        </li>
                    </ul>
                </div>
            </div>
        </details>

        <AppAlert tipo="info" titulo="Recuerda">
            Lleva tu envío <b>abierto</b>. En ventanilla revisan el contenido antes de cerrarlo y sellarlo.
        </AppAlert>
    </div>
</template>
