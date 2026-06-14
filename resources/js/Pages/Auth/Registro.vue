<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppInput from '@/Components/ui/AppInput.vue';
import AppButton from '@/Components/ui/AppButton.vue';
import Icon from '@/Components/ui/Icon.vue';

const form = useForm({
    name: '',
    email: '',
    numero_documento: '',
    telefono: '',
    password: '',
    password_confirmation: '',
});

function enviar() {
    form.post('/registro', { onFinish: () => form.reset('password', 'password_confirmation') });
}
</script>

<template>
    <Head title="Crear cuenta" />
    <div class="app-shell flex min-h-screen flex-col">
        <header class="bg-gradient-to-b from-serpost to-serpost-dark px-4 pb-8 pt-10 text-center text-white">
            <span class="inline-flex h-14 w-14 items-center justify-center rounded-card bg-white/15">
                <Icon name="mailbox" :size="30" />
            </span>
            <h1 class="mt-2 text-title font-extrabold">Crea tu cuenta</h1>
            <p class="text-body text-white/80">Para registrar y seguir tus envíos</p>
        </header>

        <main class="flex-1 px-4 py-6 sm:px-6 lg:flex lg:flex-col lg:justify-center lg:py-10">
            <div class="mx-auto w-full max-w-md">
                <div class="surface-raised p-5 sm:p-6">
                    <form @submit.prevent="enviar">
                        <AppInput v-model="form.name" label="Nombres y apellidos" :error="form.errors.name" required />
                        <AppInput v-model="form.email" label="Correo electrónico" type="email" :error="form.errors.email" required />
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            <AppInput v-model="form.numero_documento" label="N° de documento" :error="form.errors.numero_documento" inputmode="numeric" required />
                            <AppInput v-model="form.telefono" label="Teléfono" :error="form.errors.telefono" inputmode="tel" />
                        </div>
                        <AppInput v-model="form.password" label="Contraseña" type="password" :error="form.errors.password" required />
                        <AppInput v-model="form.password_confirmation" label="Repite la contraseña" type="password" required />

                        <AppButton tipo="submit" variante="primary" bloque :cargando="form.processing">
                            Crear cuenta
                        </AppButton>
                    </form>

                    <p class="mt-4 text-center text-body text-texto-medio">
                        ¿Ya tienes cuenta?
                        <Link href="/login" class="font-semibold text-serpost">Inicia sesión</Link>
                    </p>
                </div>
            </div>
        </main>
    </div>
</template>
