<script setup>
import { computed, onMounted } from 'vue'
import { formatDate } from '@/utilities.js'
import bookApi from '@/api/book.js'
import { useBookStore } from '@/stores/book.js'
import { useMainStore } from '@/stores/main.js'
import { useToast } from 'vue-toastification'
import Reviews from '@/components/Books/Reviews.vue'
import Author from '@/components/Books/Author.vue'
import { useRoute } from 'vue-router'

const bookStore = useBookStore()
const mainStore = useMainStore()
const toast = useToast()
const route = useRoute()
onMounted(async () => {
    try {
        mainStore.loading = true
        const bookResponse = await bookApi.getBookDetails(route.params.id)
        if (bookResponse.status === 200) {
            bookStore.book = bookResponse.data
        } else {
            toast.error(bookResponse.data.message)
        }
    } catch (e) {
        toast.error('Oops! '+ e)
    } finally {
        mainStore.loading = false
    }
})
const book = computed(() => bookStore.book)
</script>

<template>
    <main v-if="book" class="min-h-screen p-6 bg-primary text-gray-900">
        <div  class="grid grid-cols-12 gap-8">
            <div class="col-span-4">
                <div class="p-4 rounded-2xl shadow-md">
                    <img :src="book?.image" alt="Book Cover" class="rounded-lg w-full h-auto" />
                    <div class="mt-4">
                        <button class="w-full bg-dark_blue text-white py-2 rounded-xl mb-2 hover:bg-blue-700">Currently Reading</button>
                        <button class="w-full bg-gray-300 text-gray-700 py-2 rounded-xl mb-2 hover:bg-gray-400">Want to Read</button>
                        <button class="w-full bg-gray-100 text-gray-700 py-2 rounded-xl hover:bg-gray-200">Finished Reading</button>
                    </div>
                </div>
            </div>

            <!-- Right Column (Book Details) -->
            <div class="col-span-8 p-8 rounded-2xl shadow-md">
                <h1 class="text-3xl font-bold mb-2">{{ book?.title }}</h1>
                <h2 class="text-xl mb-4">{{ book?.author?.name }}</h2>

                <!-- Ratings and Reviews -->
                <div class="flex items-center mb-4">
                    <div class="flex justify-center text-2xl items-center mt-2">
                        <span v-for="n in 5" :key="n">
                            <i :class="['text-yellow', n <= book?.average_rating / 2 ? 'fa-solid fa-star' : 'fa-regular fa-star']"></i>
                        </span>
                    </div>
                    <span class="ml-2 text-lg">{{ book?.rating }}</span>
                    <!--                    <span class="ml-4 text-gray-500 opacity-50">({{ book?.ratings_count }} Ratings • {{ book?.reviews.length }} Reviews)</span>-->
                </div>

                <!-- Book Description -->
                <p class="text-gray-700 mb-6">
                    <br />
                    <br />
                    {{ book?.description }}
                </p>

                <!-- Genres -->
                <div class="flex flex-wrap mb-4">
                    <span v-for="genre in book?.categories" :key="genre" class="bg-light_blue py-1 px-2 rounded-full mr-2 mb-2 hover:bg-gray hover:cursor-pointer">
                        {{ genre.category_name }}
                    </span>
                </div>

                <!-- Book Information -->
                <div class="mt-8 grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-lg font-semibold">Publication Details</h3>
                        <ul class="text-gray-600">
                            <li>Published: {{ formatDate(book?.created_at) }}</li>
                        </ul>
                    </div>
                </div>
                <!-- User Engagement -->
                <div class="mt-8">
                    <p><i class="fas fa-user"></i> {{ book?.readers_stats?.reading }} people are currently reading</p>
                    <p class="mt-2"><i class="fas fa-user"></i> {{ book?.readers_stats?.want }} people want to read</p>
                </div>
            </div>
        </div>
        <Author :author="book?.author" :book="book" />
        <Reviews v-if="book"  :book-id="route.params.id"/>
        <h1 id="xyz"></h1>
    </main>
</template>

<style scoped></style>
