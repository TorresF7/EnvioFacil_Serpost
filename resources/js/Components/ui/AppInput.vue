<script setup>
import AppField from './AppField.vue';

defineProps({
    label: String,
    error: String,
    help: String,
    required: Boolean,
    type: { type: String, default: 'text' },
    placeholder: String,
    inputmode: String,
    maxlength: [String, Number],
    disabled: Boolean,
});

const modelo = defineModel({ type: [String, Number], default: '' });
</script>

<template>
    <AppField :label="label" :error="error" :help="help" :required="required">
        <input
            v-model="modelo"
            :type="type"
            :placeholder="placeholder"
            :inputmode="inputmode"
            :maxlength="maxlength"
            :disabled="disabled"
            :required="required"
            :aria-required="required ? 'true' : undefined"
            :aria-invalid="error ? 'true' : undefined"
            class="input-base"
            :class="error ? 'border-stop focus:border-stop focus:ring-stop/25' : 'hover:border-serpost/50'"
        />
    </AppField>
</template>
