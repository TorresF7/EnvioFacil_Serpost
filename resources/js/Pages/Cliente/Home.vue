<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';
import AppCard from '@/Components/ui/AppCard.vue';
import Icon from '@/Components/ui/Icon.vue';
import EstadoBadge from '@/Components/ui/EstadoBadge.vue';

const props = defineProps({
    usuario: { type: Object, default: () => ({}) },
    ultimosEnvios: { type: Object, default: () => ({ data: [] }) },
    destinosRecientes: { type: Array, default: () => [] },
});

const envios = computed(() => props.ultimosEnvios.data ?? []);
</script>

<template>
    <Head title="Inicio" />
    <div class="app-shell flex min-h-screen flex-col">
        <AppHeader titulo="SERPOST Envío Fácil" :subtitulo="`Hola, ${usuario.name?.split(' ')[0] ?? ''}`" />

        <main class="content-col flex-1 space-y-section px-4 py-4 sm:px-6 lg:py-8">
            <Link href="/app/nuevo" class="block rounded-card bg-gradient-to-br from-serpost to-serpost-dark p-5 text-white shadow-card transition hover:brightness-105">
                <span class="mb-1 inline-flex h-10 w-10 items-center justify-center rounded-xs bg-white/15">
                    <Icon name="package" :size="22" />
                </span>
                <p class="mt-1 text-body-lg font-extrabold">Registrar nuevo envío</p>
                <p class="text-body text-white/85">Prepáralo en minutos y obtén tu código QR.</p>
            </Link>

            <div class="grid grid-cols-2 gap-3">
                <Link href="/verificador" class="surface-flat p-4 text-center transition hover:border-serpost/40">
                    <Icon name="search" :size="24" class="mx-auto text-serpost" />
                    <p class="mt-1.5 text-body font-semibold">¿Puedo enviar esto?</p>
                </Link>
                <Link href="/app/envios" class="surface-flat p-4 text-center transition hover:border-serpost/40">
                    <Icon name="inbox" :size="24" class="mx-auto text-serpost" />
                    <p class="mt-1.5 text-body font-semibold">Mis envíos</p>
                </Link>
            </div>

            <AppCard v-if="destinosRecientes.length" titulo="Repetir un envío" icono="refresh" subtitulo="Vuelve a enviar a un destino reciente con los datos ya cargados.">
                <div class="divide-y divide-borde">
                    <Link
                        v-for="d in destinosRecientes"
                        :key="d.id"
                        :href="`/app/nuevo?repetir=${d.id}`"
                        class="flex items-center gap-3 py-2.5 transition hover:opacity-80"
                    >
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xs bg-serpost-tint text-serpost">
                            <Icon name="mappin" :size="18" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-body font-semibold text-texto-fuerte">{{ d.nombre }}</p>
                            <p class="truncate text-caption text-texto-medio">
                                {{ [d.ciudad, d.pais].filter(Boolean).join(', ') }}
                            </p>
                        </div>
                        <Icon name="arrow-right" :size="18" class="shrink-0 text-texto-suave" />
                    </Link>
                </div>
            </AppCard>

            <AppCard titulo="Últimos envíos" icono="clock">
                <p v-if="!envios.length" class="py-4 text-center text-body text-texto-suave">
                    Aún no tienes envíos. ¡Crea el primero!
                </p>
                <div v-else class="divide-y divide-borde">
                    <div v-for="e in envios" :key="e.id" class="flex items-center justify-between py-2.5">
                        <div>
                            <p class="font-mono text-sm font-semibold tracking-wide text-serpost">{{ e.codigo }}</p>
                            <p class="text-caption text-texto-medio">{{ e.servicio_label }} · {{ e.pais_destino }}</p>
                        </div>
                        <EstadoBadge :estado="e.estado" :label="e.estado_label" />
                    </div>
                </div>
            </AppCard>
        </main>
    </div>
</template>
