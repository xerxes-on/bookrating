<script setup>
import Loading from '@/components/common/Loading.vue'
import { useMainStore } from '@/stores/main.js'
const mainStore = useMainStore()
</script>

<template>
    <Loading v-if="mainStore.loading" />
    <RouterView />
</template>
