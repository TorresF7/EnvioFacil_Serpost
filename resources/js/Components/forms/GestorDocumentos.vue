<script setup>
import { ref } from 'vue';
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    codigo: { type: String, required: true },
    documentos: { type: Array, default: () => [] },
    requeridos: { type: Array, default: () => [] },
});

const lista = ref([...props.documentos]);
const archivo = ref(null);
const tipo = ref('certificado');
const subiendo = ref(false);
const error = ref(null);
const inputRef = ref(null);

const TIPOS = [
    { valor: 'certificado', label: 'Certificado' },
    { valor: 'licencia', label: 'Licencia' },
    { valor: 'factura', label: 'Factura' },
    { valor: 'otro', label: 'Otro' },
];

function elegir(e) {
    archivo.value = e.target.files?.[0] ?? null;
    error.value = null;
}

async function subir() {
    if (!archivo.value || subiendo.value) return;
    subiendo.value = true;
    error.value = null;
    try {
        const fd = new FormData();
        fd.append('archivo', archivo.value);
        fd.append('tipo', tipo.value);
        const { data } = await window.axios.post(`/envio/${props.codigo}/documentos`, fd, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        lista.value.unshift(data);
        archivo.value = null;
        if (inputRef.value) inputRef.value.value = '';
    } catch (e) {
        error.value = e?.response?.data?.message ?? 'No se pudo subir el archivo (PDF/JPG/PNG, máx 5 MB).';
    } finally {
        subiendo.value = false;
    }
}

async function eliminar(doc) {
    if (!window.confirm('¿Quitar este documento?')) return;
    try {
        await window.axios.delete(`/envio/${props.codigo}/documentos/${doc.id}`);
        lista.value = lista.value.filter((d) => d.id !== doc.id);
    } catch {

    }
}

function tamano(bytes) {
    return bytes > 1024 * 1024
        ? (bytes / 1024 / 1024).toFixed(1) + ' MB'
        : Math.max(1, Math.round(bytes / 1024)) + ' KB';
}
</script>

<template>
    <div class="space-y-3">

        <div v-if="requeridos.length" class="rounded-input border border-ambar/40 bg-ambar-tint p-3">
            <p class="mb-1 flex items-center gap-1.5 text-sm font-semibold text-ambar">
                <Icon name="triangle-alert" :size="16" /> Documentos que tu envío necesita
            </p>
            <ul class="space-y-0.5 text-xs text-texto-fuerte">
                <li v-for="(req, i) in requeridos" :key="i" class="flex items-start gap-1.5">
                    <Icon name="file" :size="13" class="mt-0.5 shrink-0 text-ambar" />
                    <span>{{ req.documento || 'Permiso especial' }}<template v-if="req.entidad"> · {{ req.entidad }}</template></span>
                </li>
            </ul>
        </div>

        <ul v-if="lista.length" class="space-y-1.5">
            <li v-for="doc in lista" :key="doc.id" class="flex items-center justify-between gap-2 rounded-input border border-borde bg-surface px-3 py-2">
                <div class="flex min-w-0 items-center gap-2">
                    <Icon name="file" :size="16" class="shrink-0 text-serpost" />
                    <div class="min-w-0">
                        <a :href="doc.descargar_url" class="block truncate text-sm font-semibold text-serpost hover:underline">{{ doc.nombre }}</a>
                        <span class="text-[11px] text-texto-suave">{{ doc.tipo }}<template v-if="doc.entidad"> · {{ doc.entidad }}</template> · {{ tamano(doc.tamano) }}</span>
                    </div>
                </div>
                <button type="button" class="shrink-0 rounded-input px-2 py-1 text-xs font-semibold text-stop hover:underline" @click="eliminar(doc)">
                    Quitar
                </button>
            </li>
        </ul>
        <p v-else class="text-sm text-texto-suave">Aún no has adjuntado documentos.</p>

        <div class="rounded-input border border-dashed border-serpost/40 bg-surface2 p-3">
            <div class="flex flex-wrap items-end gap-2">
                <label class="text-[11px] font-medium text-texto-medio">
                    Tipo
                    <select v-model="tipo" class="input-base mt-0.5 bg-white">
                        <option v-for="t in TIPOS" :key="t.valor" :value="t.valor">{{ t.label }}</option>
                    </select>
                </label>
                <label class="flex-1 text-[11px] font-medium text-texto-medio">
                    Archivo (PDF, JPG o PNG · máx 5 MB)
                    <input ref="inputRef" type="file" accept=".pdf,.jpg,.jpeg,.png" class="input-base mt-0.5 bg-white" @change="elegir" />
                </label>
                <button
                    type="button"
                    class="flex min-h-[44px] items-center gap-1.5 rounded-input bg-serpost px-4 py-2 text-sm font-bold text-white transition hover:bg-serpost-dark disabled:opacity-50"
                    :disabled="!archivo || subiendo"
                    @click="subir"
                >
                    <Icon name="upload" :size="16" /> {{ subiendo ? 'Subiendo…' : 'Subir' }}
                </button>
            </div>
            <p v-if="error" class="mt-1.5 text-xs font-semibold text-stop">{{ error }}</p>
        </div>
    </div>
</template>
