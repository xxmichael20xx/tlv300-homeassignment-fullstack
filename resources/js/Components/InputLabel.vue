<script setup>
defineProps({
    value: {
        type: String,
    },
    size: {
        type: String,
        default: 'text-sm'
    },
    color: {
        type: String,
        default: 'text-gray-400'
    }
});
</script>

<template>
    <label class="block font-medium" :class="color + ' ' + size">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
