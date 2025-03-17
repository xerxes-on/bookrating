<script setup>
defineProps({
    items: {
        type: Array,
        required: true,
    },
})
</script>
<template>
    <div class="absolute top-full left-0 w-40 bg-primary_dark shadow-md rounded-lg py-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
        <a v-for="item in items" :key="item" class="block px-4 py-2 hover:bg-light_blue p-2 rounded-2xl" href="#">
            {{ item }}
        </a>
    </div>
</template>

<style scoped></style>
