<script setup>
import { computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';

defineProps({
    titulo: { type: String, default: 'Panel' },
    subtitulo: String,
});

const page = usePage();
const usuario = computed(() => page.props.auth?.user ?? null);
const urlActual = computed(() => page.url);

const nav = computed(() => {
    const items = [
        { href: '/panel', label: 'Bandeja de solicitudes', icono: '📥' },
        { href: '/panel/protocolo', label: 'Protocolo de atención', icono: '📑' },
    ];
    if (usuario.value?.rol === 'admin') {
        items.push({ href: '/admin/metrics', label: 'Métricas UX', icono: '📊' });
    }
    return items;
});

const activo = (href) => urlActual.value === href || (href !== '/panel' && urlActual.value.startsWith(href));

function cerrarSesion() {
    router.post('/logout');
}
</script>

<template>
    <div class="min-h-screen bg-fondo lg:flex">
        <aside class="bg-serpost-azul text-white lg:fixed lg:inset-y-0 lg:w-64">
            <div class="flex items-center gap-2 px-5 py-5">
                <span class="text-2xl">📮</span>
                <div class="leading-tight">
                    <p class="text-sm font-extrabold">SERPOST</p>
                    <p class="text-[11px] text-white/70">Panel de gestión</p>
                </div>
            </div>
            <nav class="px-3">
                <Link
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    class="mb-1 flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium transition"
                    :class="activo(item.href) ? 'bg-white/20' : 'hover:bg-white/10'"
                >
                    <span>{{ item.icono }}</span>{{ item.label }}
                </Link>
            </nav>
        </aside>

        <div class="flex-1 lg:ml-64">
            <header class="flex items-center justify-between border-b border-borde bg-white px-6 py-3">
                <div>
                    <h1 class="text-lg font-bold text-texto-fuerte">{{ titulo }}</h1>
                    <p v-if="subtitulo" class="text-xs text-texto-medio">{{ subtitulo }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-texto-fuerte">{{ usuario?.name }}</p>
                        <p class="text-[11px] uppercase text-serpost-azul">{{ usuario?.rol }}</p>
                    </div>
                    <button class="rounded-input border border-borde px-3 py-1.5 text-xs font-semibold text-serpost hover:bg-fondo" @click="cerrarSesion">
                        Salir
                    </button>
                </div>
            </header>

            <main class="p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
