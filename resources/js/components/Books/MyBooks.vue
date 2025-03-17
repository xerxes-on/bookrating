<template>
    <section class="min-h-screen bg-primary p-6">
        <div class="flex justify-between items-center mb-6">
            <span></span>
            <h1 class="text-3xl font-bold">My Books</h1>
            <div class="relative">
                <input v-model="searchQuery" type="text" class="w-64 p-3 rounded-full shadow-sm focus:border" placeholder="Search Your Books" />
                <span class="absolute top-3 right-3 text-gray-500">
                    <i class="fas fa-search"></i>
                </span>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-8">
            <div class="col-span-3">
                <div class="bg-orange p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold mb-4">Book Shelves</h2>
                    <ul>
                        <li v-for="shelf in shelves" :key="shelf.name" class="flex justify-between items-center mb-2">
                            <span>{{ shelf.name }}</span>
                            <span class="text-gray-500">({{ shelf.count }})</span>
                        </li>
                    </ul>
                    <div class="mt-4 flex">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">All Self</button>
                        <button class="ml-4 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Add</button>
                    </div>
                    <h2 class="text-xl font-semibold mt-8 mb-4">Reading Activities</h2>
                    <ul class="space-y-2">
                        <li v-for="activity in activities" :key="activity" class="text-blue-700 hover:text-blue-900 cursor-pointer">
                            {{ activity }}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-span-9">
                <div class="grid grid-cols-4 gap-4">
                    <div v-for="book in filteredBooks" :key="book.id" class="bg-white rounded-2xl overflow-hidden shadow-xl">
                        <img :src="book.image" :alt="book.title" class="h-56 object-cover" />
                        <div class="p-4">
                            <h3 class="text-center font-semibold">{{ book.title }}</h3>
                        </div>
                    </div>
                    <div class="bg-gray-100 rounded-lg flex justify-center items-center h-72 shadow-md cursor-pointer hover:bg-gray-200">
                        <i class="fas fa-plus-circle text-4xl text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { computed, ref } from 'vue'

const books = ref([
    { id: 1, title: 'Reading Breaks Barriers', image: 'https://img.freepik.com/free-vector/reading-poster-vintage-design_23-2148920473.jpg' },
    { id: 2, title: 'Book and Ideas', image: 'https://img.freepik.com/free-vector/book-ideas-concept-art_23-2148897947.jpg' },
    { id: 3, title: "I Can't Live Without Books", image: 'https://img.freepik.com/free-vector/i-can-t-live-without-books-concept_23-2148931014.jpg' },
    { id: 4, title: 'Knowledge Library', image: 'https://img.freepik.com/free-vector/knowledge-library-quote-poster_23-2148920475.jpg' },
    { id: 5, title: 'Love of Books', image: 'https://img.freepik.com/free-vector/love-books-concept-illustration_23-2148920472.jpg' },
    { id: 6, title: 'Book Adventures', image: 'https://img.freepik.com/free-vector/book-adventure-concept_23-2148920478.jpg' },
])
const shelves = ref([
    { name: 'All', count: books.value.length },
    { name: 'Read', count: 0 },
    { name: 'Currently Reading', count: 0 },
    { name: 'Want to Read', count: 0 },
])
const activities = ref(['Draft', 'Highlights', 'Notes', 'Reading Challenge', 'Your Reading Stats'])
const searchQuery = ref('')
const filteredBooks = computed(() => {
    if (!searchQuery.value) return books.value
    return books.value.filter((book) => book.title.toLowerCase().includes(searchQuery.value.toLowerCase()))
})
</script>

<style scoped>
*:focus {
    outline: none;
}
</style>
