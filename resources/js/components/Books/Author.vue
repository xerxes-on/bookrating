<script setup>
import {reactive, ref} from 'vue'
import {useMainStore} from '@/stores/main.js'
import {useToast} from 'vue-toastification'
import FollowAuthorButton from '@/components/Books/FollowAuthorButton.vue'
import bookApi from '@/api/book.js'
import {vConfetti} from '@neoconfetti/vue'
import 'animate.css'
import {useBookStore} from '@/stores/book.js'

const rates = [
    {star: 5, width: '80%', percent: '80%'},
    {star: 4, width: '60%', percent: '60%'},
    {star: 3, width: '40%', percent: '40%'},
    {star: 2, width: '20%', percent: '20%'},
    {star: 1, width: '10%', percent: '10%'},
]

const props = defineProps({
    author: {
        required: true,
        type: Object,
    },
    book: {
        type: Object,
        required: true,
    },
})
const localAuthor = ref(props.author)
const isFlipped = ref(false)
const toast = useToast()
const mainStore = useMainStore()

const currentRating = ref(0);
const data = reactive({
    rating: currentRating.value ,
    comment: null,
    book_id: props.book.id,
})
const visible = ref(false)
const submitReview = async () => {
    try {
        mainStore.loading = true
        const response = await bookApi.rateBook(data)
        if (response?.status === 200) {
            const bookReviews = await bookApi.getBookReviews(data.book_id)
            useBookStore().reviews = bookReviews.data
            sleep(3000).then(
                () => isFlipped.value = !isFlipped.value
            )
            visible.value = !visible.value
        }
    } catch (err) {
    } finally {
        mainStore.loading = false
    }
}

function sleep(time) {
    return new Promise((resolve) => setTimeout(resolve, time));
}

const hoverRating = ref(0); // Stores the hover rating
const setRating = (rating) => {
    currentRating.value = rating;
}
const setHoverRating = (rating) => {
    hoverRating.value = rating;
};
const resetHoverRating = () => {
    hoverRating.value = 0;
};
data.rating = currentRating.value;
import { watch } from 'vue'

watch(currentRating, (val) => {
    data.rating = val
})
</script>

<template>
    <section v-if="author" class="mt-12 flex relative p-6 bg-primary">
        <div class="absolute right-64 w-3/5 h-0.5 bg-dark_blue"></div>
        <span class="w-1/2"></span>
        <div class="w-2/3 relative py-14">
            <div class="flex flex-col justify-between">
                <div class="w-full h-16">
                    <img :src="author?.data?.author_picture" alt="Author Image"
                         class="w-40 h-40 absolute top-10 -left-1/3 rounded-full object-cover"/>
                </div>
                <div>
                    <div>
                        <h3 class="text-4xl font-bold mb-10">{{ author?.data?.name }}</h3>
                        <div>
                            <FollowAuthorButton v-model="localAuthor.isFollowed" :author="author"/>
                        </div>
                    </div>
                    <p class="text-gray-600">
                        No.of Books: <span
                        class="font-bold text-dark_blue text-xl">{{
                            author?.books_count ? author?.books_count : '0'
                        }}</span>
                        &nbsp;|&nbsp; Followers:
                        <strong>{{
                                author?.data?.followers_count ? author?.data?.followers_count : '0'
                            }}</strong>
                    </p>
                    <p class="text-gray-700 mt-2">
                        {{ author?.data?.about_author }}
                    </p>
                </div>
                <button class="ml-auto bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">
                    Follow
                </button>
            </div>
        </div>
        <div class="absolute -bottom-2 right-64 b-0 w-3/5 h-0.5 bg-dark_blue"></div>
    </section>
    <section class="mb-6 max-w-4xl mx-auto">
        <div :class="['flip-card-inner', { flipped: isFlipped }]" class="w-full">
            <!-- Front Side (Profile Header) -->
            <div
                class="flip-card-front bg-orange p-4 md:p-6 rounded-2xl shadow-md w-full h-full flex flex-col md:flex-row justify-center items-center">
                <section class="w-full md:w-10/12 flex flex-col md:flex-row justify-between items-center relative">
                    <!-- Rating Section -->
                    <div class="w-full md:w-1/2 flex flex-col justify-around items-center p-4">
                        <h2 class="text-4xl md:text-6xl font-tangerine font-bold mb-4">Rating & Reviews</h2>
                        <img alt="User Avatar" class="w-20 h-20 md:w-24 md:h-24 rounded-full mb-3"
                             src="https://randomuser.me/api/portraits/women/44.jpg"/>
                        <h1 class="text-xl md:text-2xl mb-3">What Do You Think?</h1>
                        <div class="flex flex-col md:flex-row justify-between items-center w-full">
                            <div class="text-center mb-3 md:mb-0">
                                <div class="flex items-center text-lg md:text-xl space-x-1 md:space-x-2">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <i class="fas fa-star text-gray-300"></i>
                                    <i class="fas fa-star text-gray-300"></i>
                                </div>
                                <h1 class="pt-2 md:pt-4 text-base md:text-lg">Rate this Book</h1>
                            </div>

                            <button
                                class="bg-dark_blue text-white p-2 px-4 rounded-2xl hover:bg-light_blue mt-2 md:mt-0 md:ml-4"
                                @click="isFlipped = !isFlipped">
                                Write a Review
                            </button>
                        </div>
                    </div>

                    <!-- Community Reviews Section -->
                    <div class="w-full md:w-1/2 mt-6 md:mt-0 p-4">
                        <h2 class="text-4xl md:text-6xl font-tangerine mb-4 text-center">Community Reviews</h2>
                        <div class="flex items-center mb-4 flex-wrap justify-center md:justify-start">
                            <div class="text-lg text-center mr-2">
                <span v-for="n in 5" :key="n">
                  <i :class="['text-yellow m-1 md:m-2', n <= 4 ? 'fa-solid fa-star' : 'fa-regular fa-star']"></i>
                </span>
                            </div>
                            <span class="text-xl mr-2">4.5</span>
                            <span class="text-gray-500 text-sm md:text-base">(654 Ratings • 756 Reviews)</span>
                        </div>

                        <div v-if="rates" class="w-full space-y-3 md:space-y-5">
                            <div v-for="(rate, index) in rates" :key="index" class="flex items-center">
                                <div class="text-xs md:text-sm whitespace-nowrap">
                                    <span class="mr-1">{{ rate.star ?? '0' }}</span>stars
                                </div>
                                <div class="w-full bg-gray h-2 rounded-full mx-2">
                                    <div
                                        :style="{ width: rate.width }"
                                        :class="['h-2 rounded-full',
                      rate.star === 1 ? 'bg-red' : 'bg-green',
                      rate.star === 2 ? 'bg-orange_dark' : ''
                    ]"
                                    ></div>
                                </div>
                                <span class="text-xs md:text-sm">{{ rate.percent }}</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Back Side (Edit Form) -->
            <div class="flip-card-back bg-primary p-4 md:p-6 rounded-2xl w-full flex items-center justify-center">
                <section v-if="book" class="p-4 bg-orange w-full md:w-2/3 rounded-2xl shadow-2xl">
                    <div v-if="visible" v-confetti="{ particleCount: 1000 }" class="absolute celebrate left-1/2"/>
                    <h2 class="text-xl md:text-2xl font-bold text-center text-gray-700 mb-4">Write a Review</h2>
                    <div class="flex justify-center mb-4">
                        <img  :src="book?.image" alt="Book cover"
                             class="w-24 h-24 md:w-32 md:h-32 object-cover rounded"/>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-center text-gray-700 mb-2">{{ book?.title }}</h3>
                    <p class="text-center text-gray-600 mb-4">{{ book?.author?.data?.name }}</p>
                    <div class="flex space-x-2 justify-center items-center">
            <span
                v-for="star in 5"
                :key="star"
                :class="[
                'text-3xl md:text-4xl cursor-pointer transition-transform duration-300',
                star <= hoverRating || star <= currentRating ? 'text-yellow' : 'text-gray-300',
                star <= hoverRating ? 'scale-125' : ''
              ]"
                @mouseover="setHoverRating(star)"
                @mouseleave="resetHoverRating"
                @click="setRating(star)"
            >
              &#9733;
            </span>
                    </div>
                    <div class="flex justify-center mt-4">
            <textarea
                v-model="data.comment"
                class="w-full md:w-3/4 px-4 py-2 rounded-2xl"
                placeholder="Write your review about this book"
                rows="4"
            ></textarea>
                    </div>
                    <div class="w-full flex items-center justify-center md:justify-end mt-4">
                        <button
                            class="border-2 m-2 border-dark_blue rounded-2xl p-2 md:p-3 font-semibold text-sm md:text-base"
                            @click="isFlipped = !isFlipped">Cancel
                        </button>
                        <button
                            class="bg-dark_blue rounded-2xl p-2 md:p-3 text-white font-semibold text-sm md:text-base"
                            type="submit"
                            @click.prevent="submitReview">Submit
                        </button>
                    </div>
                </section>
            </div>
        </div>
    </section>
</template>

<style scoped>
.flip-card-inner {
    position: relative;
    width: 100%;
    height: 600px; /* Fixed height that works on different screens */
    transition: transform 0.6s ease-in-out;
    transform-style: preserve-3d;
}

.flipped {
    transform: rotateY(180deg);
}

.flip-card-front,
.flip-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    overflow-y: auto; /* Allow scrolling for small screens */
}

.flip-card-back {
    transform: rotateY(180deg);
}

@media (max-width: 768px) {
    .flip-card-inner {
        height: 700px; /* Taller on mobile */
    }
}

@media (max-width: 480px) {
    .flip-card-inner {
        height: 800px; /* Even taller on very small screens */
    }
}
</style>
