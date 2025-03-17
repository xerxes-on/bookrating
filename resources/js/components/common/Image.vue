<script setup>
const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    ext: {
        type: String,
        required: true,
    },
    w: {
        type: String,
        default: '200px',
    },
    h: {
        type: String,
        default: '200px',
    },
})

const src = "/images/" + props.name + "." + props.ext
</script>

<template>
    <img :alt="name" :src="src" :style="{ maxWidth: props.w, maxHeight: props.h }" />
</template>

<style scoped></style>
