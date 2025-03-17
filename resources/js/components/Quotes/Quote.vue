<script setup>
import { computed, onMounted, ref } from 'vue'
import { formatDate } from '@/utilities.js'
import { useMainStore } from '@/stores/main.js'
import { useToast } from 'vue-toastification'
import Author from '@/components/Books/Author.vue'
import profileApi from '@/api/profile.js'
import LikeButton from '@/components/Books/LikeButton.vue'
import { useBookStore } from '@/stores/book.js'
import { useRoute } from 'vue-router'


const mainStore = useMainStore()
const toast = useToast()
const quoteStore = useBookStore()
const route = useRoute()
onMounted(async () => {
    try {
        mainStore.loading = true
        const quote_response = await profileApi.getQuote(route.params.id)
        if (quote_response.status === 200) {
            quoteStore.quote = quote_response.data
        } else {
            toast.error('Oops! Something went wrong')
        }
    } catch (e) {
        toast.error('Oops! Something went wrong')
        console.log(e)
    } finally {
        mainStore.loading = false
    }
})
const quote = computed(()=>quoteStore.quote)
</script>

<template>
    <main class="min-h-screen p-6 bg-primary text-gray-900">
        <div v-if="quote" class="flex items-center justify-center ">
            <div class="col-span-8 p-8  rounded-2xl shadow-2xl w-2/3">
                <h1 class="text-xl font-bold mb-2 text-center">{{ quote.quote }}</h1>
                <h2 class="text-sm font-semibold mb-4 mr-0 text-end">{{ quote.author.data.name }}</h2>
                <div class="flex flex-wrap mb-4">
                    <span v-for="genre in quote.categories" :key="genre"
                          class="bg-light_blue py-1 px-2 rounded-full mr-2 mb-2 hover:bg-gray hover:cursor-pointer">
                        {{ genre.category_name }}
                    </span>
                </div>
                <div class="mt-2 flex justify-between">
                    <span class="text-gray">{{ formatDate(quote.created_at) }}</span>
                    <span>
                        <LikeButton :id="quote.id"  like_what="quote" v-model="quote.is_liked"/>
                    </span>
                </div>
            </div>
        </div>
<!--        <Author v-if="quote" :author="quote.author"  />-->
    </main>
</template>

<style scoped>
.text-end {
    text-align: right
}
</style>
