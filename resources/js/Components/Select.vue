<script setup>
import { ref } from 'vue';

const props = defineProps({
    options: {
        type: Array,
        default: [Object]
    }
});

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);
</script>

<template>
    <select
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        v-model="model"
        ref="input"
    >
        <option value="" disabled>Select an option</option>
        <template v-for="option in props.options" :key="option">
            <option :value="option.value">{{ option.label }}</option>
        </template>
    </select>
</template>
