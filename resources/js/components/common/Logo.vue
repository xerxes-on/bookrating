<script setup>
const props = defineProps({
    width: {
        type: String,
        default: '200px',
    },
    height: {
        type: String,
        default: '200px',
    },
})
</script>

<template>
    <div class="flex items-center mb-8">
        <img :height="props.height" :width="props.width" alt="logo" class="rounded-full" src="/images/logo.png" />
    </div>
</template>

<style scoped></style>
