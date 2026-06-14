<script setup>
import AppField from './AppField.vue';

defineProps({
    label: String,
    error: String,
    help: String,
    required: Boolean,
    placeholder: { type: String, default: 'Selecciona una opción' },

    options: { type: Array, default: () => [] },
    valueKey: { type: String, default: 'valor' },
    labelKey: { type: String, default: 'label' },
    disabled: Boolean,
});

const modelo = defineModel({ default: '' });
</script>

<template>
    <AppField :label="label" :error="error" :help="help" :required="required">
        <select
            v-model="modelo"
            :disabled="disabled"
            class="input-base appearance-none bg-[url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%234e525b%22 stroke-width=%221.75%22><path d=%22M6 9l6 6 6-6%22/></svg>')] bg-[length:14px] bg-[right_0.75rem_center] bg-no-repeat pr-9"
            :class="{ 'border-stop': error }"
        >
            <option value="" disabled>{{ placeholder }}</option>
            <option v-for="op in options" :key="op[valueKey]" :value="op[valueKey]">
                {{ op[labelKey] }}
            </option>
        </select>
    </AppField>
</template>
