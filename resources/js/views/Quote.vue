<script setup>
import Quote from'@/components/Quotes/Quote.vue'
import Layout from '@/components/common/Layout.vue'
defineProps({
    id:{
        type: String,
        required: true,
    }
})
</script>

<template>
<Layout>
    <Quote :id="id"/>
</Layout>
</template>

<style scoped>

</style>
