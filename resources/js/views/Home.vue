<script setup>
import Layout from '@/components/common/Layout.vue'
import Home from '@/components/Home/Home.vue'
import {useAuthStore} from "@/stores/auth.js";
console.log(useAuthStore().token.value)
</script>
<template>
    <Layout>
        <Home />
    </Layout>
</template>
<style scoped></style>
