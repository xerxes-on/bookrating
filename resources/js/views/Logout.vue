<script setup>
import { useAuthStore } from '@/stores/auth.js'

useAuthStore().logout()
</script>

<template>
Logging out :(
</template>

<style scoped>

</style>
