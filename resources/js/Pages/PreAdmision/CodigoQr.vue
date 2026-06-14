<script setup>
import { ref, computed } from 'vue';
import QrcodeVue from 'qrcode.vue';
import { storeToRefs } from 'pinia';
import { useEnvioStore } from '@/Stores/envio';
import { useFormularioDescarga } from '@/Composables/useFormularioDescarga';
import { documentosRequeridos } from '@/Composables/useVerificadorProducto';
import AppCard from '@/Components/ui/AppCard.vue';
import AppButton from '@/Components/ui/AppButton.vue';
import Icon from '@/Components/ui/Icon.vue';
import VistaFormulario from '@/Components/forms/VistaFormulario.vue';
import GestorDocumentos from '@/Components/forms/GestorDocumentos.vue';

const store = useEnvioStore();
const { codigo, qrPayload, descargarUrl, rotuladoUrl, servicioActual, articulos } = storeToRefs(store);
const { descargar } = useFormularioDescarga();

const requeridos = computed(() => documentosRequeridos(articulos.value));

const tab = ref('qr');

const s10 = computed(() => {
    const src = (codigo.value || 'SRP000000').replace(/[^A-Za-z0-9]/g, '').toUpperCase();
    let d = '';
    for (let i = 0; i < src.length && d.length < 9; i++) d += src.charCodeAt(i) % 10;
    return `RR${(d + '000000000').slice(0, 9)}PE`;
});

const barras = computed(() => {
    const src = (codigo.value || 'SRP000000').replace(/[^A-Za-z0-9]/g, '');
    const out = [];
    for (const ch of src) {
        const c = ch.charCodeAt(0);
        out.push((c % 3) + 1, ((c >> 2) % 3) + 1, ((c >> 4) % 2) + 1, (c % 2) + 1);
    }
    return out;
});

const pasosVentanilla = [
    'Saca un ticket y espera tu turno.',
    'Muestra este código QR al personal de ventanilla.',
    'Entrega tu envío abierto y tu DNI original.',
    'El personal escanea, pesa, cobra y sella. ¡Listo!',
];

const llevar = ['DNI original', 'El envío abierto', 'Factura/boleta (si no es regalo)', 'Este código (impreso o en pantalla)'];
</script>

<template>
    <div class="space-y-section">
        <div class="text-center">
            <span class="mb-1 inline-flex h-12 w-12 items-center justify-center rounded-full bg-go-tint text-go">
                <Icon name="circle-check" :size="30" />
            </span>
            <h2 class="font-display text-title font-extrabold text-go">¡Pre-admisión completada!</h2>
            <p class="text-body text-texto-medio">Muestra esto en ventanilla y ahorra tiempo.</p>
        </div>

        <div class="flex gap-1 rounded-input bg-surface2 p-1">
            <button
                class="flex-1 rounded-xs py-2 text-sm font-semibold transition"
                :class="tab === 'qr' ? 'bg-white text-serpost shadow-sm' : 'text-texto-medio'"
                @click="tab = 'qr'"
            >Código QR</button>
            <button
                class="flex-1 rounded-xs py-2 text-sm font-semibold transition"
                :class="tab === 'form' ? 'bg-white text-serpost shadow-sm' : 'text-texto-medio'"
                @click="tab = 'form'"
            >Mi formulario</button>
        </div>

        <template v-if="tab === 'qr'">

            <div class="ticket overflow-hidden bg-surface">
                <div class="flex items-center justify-between border-b border-dashed border-paper-line px-4 py-2">
                    <span class="font-mono text-[11px] font-semibold uppercase tracking-[0.14em] text-texto-medio">Pre-admisión · SERPOST</span>
                    <span class="font-mono text-[11px] text-texto-suave">{{ servicioActual?.formulario }}</span>
                </div>

                <div class="relative flex flex-col items-center px-4 py-5">

                    <span class="pointer-events-none absolute right-3 top-3 grid h-20 w-20 -rotate-12 place-items-center rounded-full border-2 border-dashed border-serpost-line/60 text-center font-mono text-[8px] uppercase leading-tight tracking-wider text-serpost-line/70">
                        SERPOST<br />· PERÚ ·
                    </span>

                    <div class="rounded-card border border-borde p-3">
                        <QrcodeVue v-if="qrPayload" :value="qrPayload" :size="190" level="M" />
                        <div v-else class="flex h-[190px] w-[190px] items-center justify-center text-texto-suave">Generando…</div>
                    </div>

                    <p class="mt-3 text-caption uppercase tracking-wide text-texto-suave">Código de envío</p>
                    <p class="font-mono text-2xl font-extrabold tracking-[0.12em] text-serpost-dark">{{ codigo }}</p>

                    <div class="mt-3 flex h-12 items-stretch justify-center gap-px" aria-hidden="true">
                        <div
                            v-for="(w, i) in barras"
                            :key="i"
                            :style="{ width: (w * 1.4) + 'px' }"
                            :class="i % 2 === 0 ? 'bg-ink' : 'bg-transparent'"
                        />
                    </div>
                    <p class="mt-1 font-mono text-sm tracking-[0.2em] text-texto-medio">{{ s10 }}</p>
                </div>

                <div class="flex items-center gap-1.5 border-t border-dashed border-paper-line bg-go-tint/50 px-4 py-2 text-caption font-semibold text-go">
                    <Icon name="clock" :size="15" /> ~5 min en ventanilla (antes 15 min)
                </div>
            </div>

            <AppCard titulo="En ventanilla" icono="office">
                <ol class="space-y-2.5">
                    <li v-for="(p, i) in pasosVentanilla" :key="i" class="flex gap-2.5 text-body">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-serpost text-xs font-bold text-white">{{ i + 1 }}</span>
                        <span class="text-texto-fuerte">{{ p }}</span>
                    </li>
                </ol>
            </AppCard>

            <AppCard titulo="Qué llevar" icono="checks">
                <ul class="space-y-1.5">
                    <li v-for="(l, i) in llevar" :key="i" class="flex items-center gap-2 text-body text-texto-fuerte">
                        <Icon name="check" :size="17" class="text-go" />{{ l }}
                    </li>
                </ul>
            </AppCard>

            <AppCard titulo="Tu rótulo para pegar" icono="mappin">
                <p class="text-sm text-texto-medio">
                    Imprime esta etiqueta y pégala en la cara más visible del paquete. Así llegas con el rótulo hecho
                    y avanzas más rápido en ventanilla.
                </p>
                <img
                    src="/img/conoce-como-rotular.jpg"
                    alt="Cómo rotular correctamente tu envío: remitente arriba a la izquierda, destinatario abajo a la derecha"
                    class="mt-2 w-full rounded-card border border-borde"
                />
                <AppButton variante="primary" bloque class="mt-3" @click="descargar(rotuladoUrl)">
                    <Icon name="download" :size="18" /> Descargar rótulo (PDF)
                </AppButton>
                <p class="mt-1.5 text-center text-caption text-texto-suave">
                    Etiqueta con remitente y destinatario lista para imprimir y pegar.
                </p>
            </AppCard>

            <AppCard v-if="codigo" titulo="Documentos y certificados" icono="file">
                <p class="text-sm text-texto-medio">
                    Adjunta aquí los certificados o permisos de tu envío. Quedan guardados y el personal de
                    ventanilla podrá verlos al admitirlo.
                </p>
                <div class="mt-2">
                    <GestorDocumentos :codigo="codigo" :requeridos="requeridos" />
                </div>
            </AppCard>
        </template>

        <template v-else>
            <AppCard :titulo="`Vista previa · ${servicioActual?.formulario}`" icono="file">
                <VistaFormulario />
                <AppButton variante="primary" bloque class="mt-3" @click="descargar(descargarUrl)">
                    <Icon name="download" :size="18" /> Descargar formulario
                </AppButton>
                <p class="mt-1.5 text-center text-caption text-texto-suave">
                    Archivo HTML imprimible con botón de impresión integrado.
                </p>
            </AppCard>
        </template>
    </div>
</template>

<style scoped>
.ticket {
    border-radius: 20px;
    border: 1px solid theme('colors.borde');
    box-shadow: 0 1px 3px rgba(15, 42, 102, 0.08), 0 1px 2px rgba(15, 42, 102, 0.04);
    position: relative;
}
.ticket::before,
.ticket::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 18px;
    height: 18px;
    background: theme('colors.fondo');
    border: 1px solid theme('colors.borde');
    border-radius: 9999px;
    transform: translateY(-50%);
}
.ticket::before {
    left: -10px;
}
.ticket::after {
    right: -10px;
}
</style>
