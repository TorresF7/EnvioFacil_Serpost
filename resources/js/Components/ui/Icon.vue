<script setup>
import { computed } from 'vue';
import { ICONS } from './iconRegistry';

const props = defineProps({
    name: { type: String, required: true },
    size: { type: [String, Number], default: 22 },
    stroke: { type: [String, Number], default: 1.75 },
});

const Glifo = computed(() => ICONS[props.name] ?? null);
</script>

<template>
    <component
        :is="Glifo"
        v-if="Glifo"
        :size="size"
        :stroke-width="stroke"
        class="inline-block shrink-0"
        aria-hidden="true"
        focusable="false"
    />
</template>
