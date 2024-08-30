<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-red-900 font-medium">
            {{ message }}
        </p>
    </div>
</template>
