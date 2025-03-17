<script setup>



import { useAuthStore } from '@/stores/auth.js'
</script>

<template>
    <div class="flex justify-center">
        <button @click="useAuthStore().logout()" class="bg-dark_blue text-white px-6 py-2 rounded-lg">Logout</button>
    </div>
</template>

<style scoped></style>
