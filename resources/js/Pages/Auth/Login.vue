<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppInput from '@/Components/ui/AppInput.vue';
import AppButton from '@/Components/ui/AppButton.vue';
import AppAlert from '@/Components/ui/AppAlert.vue';
import Icon from '@/Components/ui/Icon.vue';

const form = useForm({ email: '', password: '', recordar: false });

function enviar() {
    form.post('/login', { onFinish: () => form.reset('password') });
}
</script>

<template>
    <Head title="Iniciar sesión" />
    <div class="app-shell flex min-h-screen flex-col">
        <header class="bg-gradient-to-b from-serpost to-serpost-dark px-4 pb-8 pt-10 text-center text-white">
            <span class="inline-flex h-14 w-14 items-center justify-center rounded-card bg-white/15">
                <Icon name="mailbox" :size="30" />
            </span>
            <h1 class="mt-2 text-title font-extrabold">SERPOST Envío Fácil</h1>
            <p class="text-body text-white/80">Inicia sesión para continuar</p>
        </header>

        <main class="flex-1 px-4 py-6 sm:px-6 lg:flex lg:flex-col lg:justify-center lg:py-10">
            <div class="mx-auto w-full max-w-md">
                <div class="surface-raised p-5 sm:p-6">
                    <AppAlert v-if="form.errors.email" tipo="error" class="mb-4">{{ form.errors.email }}</AppAlert>

                    <form @submit.prevent="enviar">
                        <AppInput v-model="form.email" label="Correo electrónico" type="email" required />
                        <AppInput v-model="form.password" label="Contraseña" type="password" :error="form.errors.password" required />

                        <label class="mb-4 flex items-center gap-2 text-body text-texto-medio">
                            <input v-model="form.recordar" type="checkbox" class="h-4 w-4 accent-serpost" />
                            Recordarme
                        </label>

                        <AppButton tipo="submit" variante="primary" bloque :cargando="form.processing">
                            Iniciar sesión
                        </AppButton>
                    </form>

                    <p class="mt-4 text-center text-body text-texto-medio">
                        ¿No tienes cuenta?
                        <Link href="/registro" class="font-semibold text-serpost">Regístrate</Link>
                    </p>
                </div>

                <div class="surface-flat mt-4 p-3 text-caption text-texto-medio">
                    <p class="mb-1 font-semibold text-texto-fuerte">Cuentas de prueba</p>
                    <p class="flex items-center gap-1.5"><Icon name="user" :size="14" class="text-serpost" /> Cliente: <b>cliente@demo.pe</b> / demo1234</p>
                    <p class="flex items-center gap-1.5"><Icon name="office" :size="14" class="text-serpost" /> Ventanilla: <b>ventanilla@demo.pe</b> / demo1234</p>
                    <p class="flex items-center gap-1.5"><Icon name="shield" :size="14" class="text-serpost" /> Admin: <b>admin@demo.pe</b> / demo1234</p>
                </div>

                <Link href="/verificador" class="mt-4 flex items-center justify-center gap-1.5 text-center text-body font-semibold text-serpost">
                    <Icon name="search" :size="18" /> Verificar un producto sin iniciar sesión
                </Link>
            </div>
        </main>
    </div>
</template>
