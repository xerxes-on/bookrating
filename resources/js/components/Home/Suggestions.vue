<script setup>
import { VueperSlide, VueperSlides } from 'vueperslides'
import 'vueperslides/dist/vueperslides.css'

defineProps({
    suggestedBooks: {
        required: true,
    },
})
</script>

<template>
    <vueper-slides :infinite="true" duration="2000" autoplay class="no-shadow h-full" :visible-slides="3" :gap="2" :bullets="false" :slide-ratio="1 / 4" fixed-height="300px">
        <vueper-slide v-for="book in suggestedBooks" :key="book.id" :image="book.image" class="rounded-2xl">
            <RouterLink :to="`/book/${book.id}`" class="block w-full h-full cursor-pointer">
                <img :src="book.image" alt="book image" class="w-full h-full object-cover rounded-2xl" />
            </RouterLink>
        </vueper-slide>
    </vueper-slides>

</template>

<style scoped></style>
