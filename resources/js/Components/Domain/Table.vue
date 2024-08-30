<script setup>
const props = defineProps({
    heads: {
        type: Array
    },
    rows: {
        type: Object
    },
    keys: {
        type: Array
    }
});
</script>

<template>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-black-900">
            <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left" v-for="head in heads">
                        {{ head }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b">
                    <td v-for="key in keys" class="px-6 py-4" v-html="rows[key]"></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
