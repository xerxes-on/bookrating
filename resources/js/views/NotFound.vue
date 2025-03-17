<script setup>
import Image from '@/components/common/Image.vue'
</script>

<template>
    <div class="h-screen bg-primary flex flex-col items-center justify-center w-screen">
        <div class="bg-light_blue w-2/3 h-2/3 rounded-2xl p-4 py-10 mb-2 relative flex flex-col justify-center items-center relative">
            <Image name="notFound" ext="svg" w="100vw" h="100vh" />
            <RouterLink to="/" class="absolute bottom-10 bg-primary rounded-2xl px-5 py-3 hover:bg-dark_blue hover:text-white font-bold"> Go to Home</RouterLink>
        </div>
    </div>
</template>

<style scoped></style>
