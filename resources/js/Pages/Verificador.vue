<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';
import AppCard from '@/Components/ui/AppCard.vue';
import Icon from '@/Components/ui/Icon.vue';
import CajaVerificador from '@/Components/CajaVerificador.vue';
import { PROHIBIDOS, PELIGROSOS, RESTRINGIDOS } from '@/Utils/constantes';

const listas = [
    { titulo: 'Prohibidos', icono: 'octagon-x', items: PROHIBIDOS },
    { titulo: 'Peligrosos', icono: 'triangle-alert', items: PELIGROSOS },
    { titulo: 'Restringidos', icono: 'file', items: RESTRINGIDOS },
];
</script>

<template>
    <Head title="¿Puedo enviar esto?" />
    <div class="app-shell flex min-h-screen flex-col">
        <AppHeader titulo="¿Puedo enviar esto?" subtitulo="Verificador de productos" />

        <main class="content-col flex-1 space-y-section px-4 py-4 sm:px-6 lg:py-8">
            <AppCard titulo="Escribe tu producto" icono="search" enfasis>
                <CajaVerificador />
            </AppCard>

            <div class="grid gap-3 sm:grid-cols-2">
                <AppCard v-for="l in listas" :key="l.titulo" :titulo="l.titulo" :icono="l.icono">
                    <div class="flex flex-wrap gap-1.5">
                        <span
                            v-for="(item, i) in l.items"
                            :key="i"
                            class="rounded-xs bg-surface2 px-2.5 py-1 text-caption capitalize text-texto-medio"
                        >{{ item }}</span>
                    </div>
                </AppCard>
            </div>

            <Link href="/" class="flex min-h-[56px] items-center justify-center gap-2 rounded-input bg-serpost text-base font-bold text-white transition hover:bg-serpost-dark lg:min-h-[48px]">
                Empezar mi envío <Icon name="arrow-right" :size="18" />
            </Link>
        </main>
    </div>
</template>
