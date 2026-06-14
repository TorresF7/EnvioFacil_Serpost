<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import SelectorIdioma from '@/Components/SelectorIdioma.vue';
import BarraAccesibilidad from '@/Components/BarraAccesibilidad.vue';
import Icon from '@/Components/ui/Icon.vue';

defineProps({
    titulo: { type: String, default: 'SERPOST' },
    subtitulo: String,
    modo: { type: String, default: 'cliente' },
    ancho: { type: String, default: 'col' },
});

const page = usePage();
const usuario = computed(() => page.props.auth?.user ?? null);
const menuAbierto = ref(false);

function cerrarSesion() {
    router.post('/logout');
}
</script>

<template>
    <header
        class="sticky top-0 z-30 pb-3 pt-4 text-white lg:py-2.5"
        :class="modo === 'ventanilla' ? 'bg-serpost-dark' : 'bg-gradient-to-b from-serpost to-serpost-dark'"
    >
      <div class="px-4 sm:px-6" :class="ancho === 'wide' ? 'content-col-wide' : 'content-col'">

        <div class="flex flex-wrap items-center gap-x-3 gap-y-2">

            <Link href="/" class="order-2 mr-auto flex items-center gap-2.5 lg:order-1">
                <span class="grid h-8 w-8 place-items-center rounded-xs bg-white/15">
                    <Icon name="mailbox" :size="18" />
                </span>
                <div class="leading-tight">
                    <p class="text-sm font-extrabold tracking-tight">{{ titulo }}</p>
                    <p v-if="subtitulo" class="text-caption text-white/80">{{ subtitulo }}</p>
                </div>
            </Link>

            <div class="order-1 flex w-full items-center justify-between gap-2 lg:order-2 lg:w-auto lg:justify-end">
                <SelectorIdioma />
                <BarraAccesibilidad />
            </div>

            <div class="order-3">
                <div v-if="usuario" class="relative">
                    <button
                        class="flex items-center gap-1.5 rounded-input bg-white/15 px-3 py-2 text-xs font-semibold backdrop-blur"
                        @click="menuAbierto = !menuAbierto"
                    >
                        <span class="grid h-5 w-5 place-items-center rounded-full bg-white/25">{{ usuario.name.charAt(0) }}</span>
                        <span class="max-w-24 truncate sm:max-w-40">{{ usuario.name }}</span>
                        <Icon name="chevron-down" :size="14" />
                    </button>
                    <div
                        v-if="menuAbierto"
                        class="absolute right-0 z-40 mt-1 w-52 overflow-hidden rounded-card border border-borde bg-white text-texto-fuerte shadow-xl"
                        @click="menuAbierto = false"
                    >
                        <Link href="/app" class="flex items-center gap-2 px-4 py-3 text-sm hover:bg-fondo">
                            <Icon name="home" :size="18" class="text-serpost" /> Inicio
                        </Link>
                        <Link href="/app/envios" class="flex items-center gap-2 px-4 py-3 text-sm hover:bg-fondo">
                            <Icon name="package" :size="18" class="text-serpost" /> Mis envíos
                        </Link>
                        <button class="flex w-full items-center gap-2 px-4 py-3 text-left text-sm text-serpost hover:bg-fondo" @click="cerrarSesion">
                            <Icon name="logout" :size="18" /> Cerrar sesión
                        </button>
                    </div>
                </div>
                <Link
                    v-else
                    href="/login"
                    class="rounded-input bg-white/15 px-3 py-2 text-xs font-semibold backdrop-blur hover:bg-white/25"
                >
                    Iniciar sesión
                </Link>
            </div>
        </div>

        <div v-if="$slots.default" class="mt-2 lg:mt-0">
            <slot />
        </div>
      </div>
    </header>
</template>
