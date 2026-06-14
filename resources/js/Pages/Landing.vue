<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';

const ventajas = [
    { i: 'search', t: 'Verifica antes de viajar', d: 'Comprueba si tu producto puede salir del país.' },
    { i: 'edit', t: 'Llena el formulario en casa', d: 'Sin errores, guiado paso a paso.' },
    { i: 'qr', t: 'Código QR para ventanilla', d: 'El personal no vuelve a digitar tus datos.' },
    { i: 'mappin', t: 'Sigue tu envío', d: 'Notificaciones de cada cambio de estado.' },
];
</script>

<template>
    <Head title="Bienvenido" />
    <div class="app-shell flex min-h-screen flex-col">
        <header class="bg-gradient-to-b from-serpost to-serpost-dark pb-10 pt-12 text-white">
            <div class="content-col px-5 sm:px-6">
                <div class="flex items-center gap-2 text-white">
                    <Icon name="mailbox" :size="40" :stroke="1.6" />
                    <Icon name="plane" :size="32" :stroke="1.6" />
                </div>
                <h1 class="mt-3 font-display text-display font-black leading-tight">Envía al mundo,<br />sin filas ni errores</h1>
                <p class="mt-2 text-body text-white/85">
                    Prepara tu envío internacional desde tu celular y reduce tu tiempo en ventanilla de
                    <b>15</b> a <b>~5 minutos</b>.
                </p>
                <div class="mt-5 flex flex-col gap-2 sm:flex-row">
                    <Link href="/registro" class="flex min-h-[52px] flex-1 items-center justify-center rounded-input bg-white text-base font-bold text-serpost transition hover:bg-white/90">
                        Crear cuenta
                    </Link>
                    <Link href="/login" class="flex min-h-[52px] flex-1 items-center justify-center rounded-input bg-white/15 text-base font-bold text-white backdrop-blur transition hover:bg-white/25">
                        Ya tengo cuenta
                    </Link>
                </div>
            </div>
        </header>

        <main class="content-col flex-1 space-y-3 px-4 py-6 sm:px-6 lg:py-8">
            <div class="grid gap-3 sm:grid-cols-2">
                <div v-for="(v, i) in ventajas" :key="i" class="surface-flat flex items-start gap-3 p-4">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xs bg-serpost-tint text-serpost">
                        <Icon :name="v.i" :size="20" />
                    </span>
                    <div>
                        <p class="text-body font-bold text-texto-fuerte">{{ v.t }}</p>
                        <p class="text-caption text-texto-medio">{{ v.d }}</p>
                    </div>
                </div>
            </div>

            <Link href="/verificador" class="flex min-h-[56px] items-center justify-center gap-2 rounded-input border-2 border-serpost text-base font-bold text-serpost transition hover:bg-serpost-tint lg:min-h-[48px]">
                <Icon name="search" :size="18" /> Verificar un producto (sin cuenta)
            </Link>
        </main>

        <footer class="content-col px-4 py-4 text-center text-caption text-texto-suave">
            SERPOST · Servicios Postales del Perú
        </footer>
    </div>
</template>
