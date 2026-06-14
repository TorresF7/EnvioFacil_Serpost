<script setup>
defineProps({
    label: String,
    error: String,
    help: String,
    required: Boolean,
});
</script>

<template>
    <div class="mb-4">
        <label v-if="label" class="mb-1.5 block text-[0.8rem] font-semibold text-texto-fuerte">
            {{ label }}
            <span v-if="required" class="text-rojo">*</span>
        </label>
        <slot />
        <p v-if="error" role="alert" class="mt-1.5 flex items-center gap-1 text-caption font-medium text-stop">{{ error }}</p>
        <p v-else-if="help" class="mt-1.5 text-caption text-texto-suave">{{ help }}</p>
    </div>
</template>
