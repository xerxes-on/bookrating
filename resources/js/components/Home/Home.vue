<script setup>
import {onMounted, ref, watch} from 'vue'
import {useHomeStore} from '@/stores/home.js'
import {useProfileStore} from '@/stores/profile.js'
import homeApi from '@/api/home.js'
import profileApi from '@/api/profile.js'
import Svg from '@/components/common/Image.vue'
import Suggestions from '@/components/Home/Suggestions.vue'
import {useMainStore} from '@/stores/main.js'
import {useToast} from 'vue-toastification'

// Goal setter for challenge
const count = ref(1)
watch(count, () => {
    if (count.value < 1) {
        count.value = 1
    }
})

const homeStore = useHomeStore()
const profileStore = useProfileStore()
const mainStore = useMainStore()
const toast = useToast()
onMounted(async () => {
    try {
        mainStore.loading = true
        const suggestions = await homeApi.getSuggestedBooks()
        const trending = await homeApi.getTrendingBooks()
        const response_user = await profileApi.getProfileDetails()
        if (response_user.status === 200) {
            profileStore.setUser(response_user.data.user)
        }
        if (suggestions.status === 200) {
            homeStore.setSuggestions(suggestions.data)
        }
        if (trending.status === 200) {
            homeStore.setTrending(trending.data)
        }
    } catch (err) {
        toast.error('Oops!' + err)
    } finally {
        mainStore.loading = false
    }
})
const classes = ['row-span-2', 'col-span-2', 'col-span-2', '', '']
</script>

<template>
    <main class="p-4 pb-10 bg-primary">
        <section class="flex justify-around">
            <div
                class="w-1/4 h-fit bg-primary_dark flex justify-between flex-col rounded-2xl shadow-md p-4 mr-4 border-1 border-dark_blue">
                <h2 class="text-lg font-bold mb-2 text-center">2024 READING CHALLENGE</h2>
                <p class="text-sm mb-4">
                    Challenge Your Self to Read More<br/>
                    This Year
                </p>
                <div class="bg-light_blue rounded-2xl p-4 my-4">
                    <Svg name="home1" ext="svg"/>
                    <p class="text-center font-medium">Reading Challenge</p>
                    <div class="flex justify-center items-center mt-2">
                        <button @click="count--" class="text-3xl font-bold mr-2">-</button>
                        <span class="text-2xl">{{ count }}</span>
                        <button @click="count++" class="text-3xl font-bold ml-2">+</button>
                    </div>
                    <p class="text-xs text-center mt-2">You Can Change Your Goal at Any Time</p>
                </div>
                <h3 class="font-bold my-8 text-center">THE BOOK SHELVES</h3>
                <ul>
                    <li class="flex justify-between mb-1">
                        <span>Want to Read</span><span>{{ profileStore.books_status.finished.length }}</span>
                    </li>
                    <li class="flex justify-between mb-1">
                        <span>Currently Reading</span><span>{{ profileStore.books_status.reading.length }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Completed</span><span>{{ profileStore.books_status.wantToRead.length }}</span>
                    </li>
                </ul>
                <div class="flex mt-14 justify-between py-10">
                    <a href="#" class="text-2xl"><i class="fa-brands fa-google"></i></a>
                    <a href="#" class="text-2xl"><i class="fa-brands fa-apple"></i></a>
                    <a href="#" class="text-2xl"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="text-2xl"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="text-2xl"><i class="fa-brands fa-x-twitter"></i></a>
                </div>
            </div>

            <div class="flex-1 h-fit">
                <h2 class="text-xl font-bold mb-4 text-center">Trending Books</h2>
                <div class="grid grid-cols-2 gap-3 p-2 lg:grid-cols-3">
                    <div v-for="(book, index) in homeStore.trending" :key="index"
                         class="relative p-3 rounded-xl shadow-lg" :class="classes[index]">
                        <RouterLink :to="'book/'+book.id">
                            <div :style="{ backgroundImage: `url(${book.image})` }"
                                 class="absolute inset-0 bg-cover rounded-xl blur-sm bg-center"></div>
                            <div
                                class="relative rounded-3xl z-5 flex flex-col justify-center items-center p-2 h-full bg-white opacity-70">
                                <h1 class="text-center font-bold">{{ book.title }}</h1>
                                <div class="text-lg text-center">
                                <span v-for="n in 5" :key="n">
                                    <i :class="['text-yellow', n <= book.rating / 2 ? 'fa-solid fa-star' : 'fa-regular fa-star']"></i>
                                </span>
                                </div>
                            </div>
                        </RouterLink>
                    </div>
                </div>
                <h2 class="text-xl font-bold mb-4 text-center pt-10">Suggestions</h2>
                <div class="relative overflow-hidden w-full">
                    <!-- Scrolling container -->
                    <Suggestions v-if="homeStore.suggestions" :suggested-books="homeStore.suggestions"/>
                </div>
            </div>
            <!-- Right Sidebar -->
            <div class="w-1/4 bg-primary_dark h-fit rounded-lg shadow-md p-4 ml-4">
                <a href="/join-the-race">
                    <h2 class="text-lg font-bold mb-2 text-center">WELCOME TO LetsRate</h2>
                    <p class="text-sm mb-4 text-center">Meet your favorite book, find your reading community, and manage
                        your reading life.</p>
                    <div class="bg-light_blue rounded-lg p-4 mb-4 flex flex-col justify-center">
                        <Svg name="home2" ext="svg" w="350px" h="300px" class="mx-5"/>
                        <h3 class="font-bold text-center">LetsRate</h3>
                        <p class="text-center">CHOICE AWARD</p>
                        <p class="text-center text-2xl font-bold">2024</p>
                        <p class="text-center mt-2">Announcing the Best <br/>Books of 2024</p>
                    </div>
                    <h3 class="font-bold mb-2 text-center py-4">WORK WITH US</h3>
                    <ul class="text-sm py-4">
                        <li class="mb-1 text-center">Authors</li>
                        <li class="mb-1 text-center">Advertise</li>
                        <li class="text-center">Authors & Ads Blog</li>
                    </ul>
                </a>
            </div>
        </section>
    </main>
</template>

<style scoped></style>
