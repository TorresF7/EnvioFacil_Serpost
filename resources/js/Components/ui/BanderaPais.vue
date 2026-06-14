<script setup>

defineProps({
    codigo: { type: String, required: true },
    size: { type: [String, Number], default: 20 },
});
</script>

<template>
    <span
        :class="`fi fi-${codigo.toLowerCase()}`"
        :style="{ fontSize: typeof size === 'number' ? size + 'px' : size }"
        class="inline-block shrink-0 rounded-[2px] shadow-sm"
        role="img"
        :aria-label="`Bandera ${codigo}`"
    />
</template>
