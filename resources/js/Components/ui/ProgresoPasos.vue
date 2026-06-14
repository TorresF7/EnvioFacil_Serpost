<script setup>
defineProps({
    total: { type: Number, required: true },
    actual: { type: Number, required: true },
});
</script>

<template>
    <div
        class="flex gap-1"
        role="progressbar"
        :aria-valuenow="actual + 1"
        :aria-valuemin="1"
        :aria-valuemax="total"
        :aria-label="`Paso ${actual + 1} de ${total}`"
    >
        <span
            v-for="i in total"
            :key="i"
            class="h-2 flex-1 rounded-full transition-colors"
            :class="i - 1 <= actual ? 'bg-white' : 'bg-white/30'"
        />
    </div>
</template>
