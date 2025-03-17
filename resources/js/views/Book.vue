<script setup>
import Layout from '@/components/common/Layout.vue'
import Book from '@/components/Books/Book.vue'

defineProps({
    book_id: {
        type: String,
        required:true
    }
})
</script>

<template>
  <Layout>
      <Book :id="book_id"/>
  </Layout>
</template>

<style scoped>

</style>
