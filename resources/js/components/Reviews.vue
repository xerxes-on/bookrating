<template>
    <div class="bg-primary min-h-screen p-4 flex flex-col items-center">
        <h1 class="text-center text-2xl font-bold mb-6">Most popular 100 reviewers this week in</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 max-w-screen-xl w-full">
            <div v-for="review in reviewsStore.reviews.data" :key="review.id" class="flex bg-primary p-4 rounded-xl shadow-sm space-x-4">
                <div class="w-1/4 text-center" v-if="review.user">
                    <div class="flex items-center justify-center">
                        <img :src="review.user.profile_picture" alt="User Avatar" class="w-16 h-16 rounded-full bg-cover" />
                        <div class="">
                            <p class="ml-2 font-semibold text-xl">{{ review.user.name }}</p>
                        </div>
                    </div>
                    <div class="flex justify-center text-2xl items-center mt-2">
                        <span v-for="n in 5" :key="n">
                            <i :class="['text-yellow', n <= review.rating / 2 ? 'fa-solid fa-star' : 'fa-regular fa-star']"></i>
                        </span>
                    </div>
                    <!-- Follow Button -->
                    <div>
                        <FollowButton :whoToFollow="review.user.id" v-model="review.user.is_followed" />
                    </div>
                </div>
                <div class="flex-1 pl-3">
                    <p class="text-gray text-xl">{{ formatDate(review.created_at) }}</p>
                    <p class="mt-2 text-gray-800 text-sm leading-relaxed">
                        {{ review.comment }}
                    </p>
                    <div class="flex items-center justify-around w-1/2 space-x-4 mt-4">
                        <div class="flex items-center space-x-1">
<!--                            <span class="mr-5">{{ review.likes }} Likes</span>-->
                            <LikeButton v-model="review.is_liked" :id="review.id" like_what="review"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- See More button -->
        <button v-if="showSeeMoreButton" @click="loadMoreReviews" class="bg-dark_blue  text-white text-xl p-2 hover:shadow-2xl m-5 hover:text-2npmxl rounded-2xl">
            See More
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import bookApi from '@/api/book.js'
import { useMainStore } from '@/stores/main.js'
import { useToast } from 'vue-toastification'
import { formatDate } from '@/utilities.js'
import FollowButton from '@/components/common/FollowButton.vue'
import LikeButton from '@/components/Books/LikeButton.vue'
import { useReviewsStore } from '@/stores/reviews.js'
const toast = useToast()


const reviewsStore = useReviewsStore();
const mainStore = useMainStore();

onMounted(async () => {
    try {
        mainStore.loading = true;
        const response = await bookApi.getReviews(1);
        console.log(response.data)
        if (response.status === 200) {
            reviewsStore.addReviews(response.data.data, response.data.current_page, response.data.last_page, response.data.next_page_url);
        } else {
            toast.error(response.data.message);
        }
    } catch (error) {
        toast.error('Oops! Something went wrong', error);
    } finally {
        mainStore.loading = false;
    }
});
console.log(reviewsStore.reviews)
const showSeeMoreButton = computed(() => {
    return reviewsStore.reviews.next_page_url !== null;
});

// Function to load the next page of reviews
const loadMoreReviews = async () => {
    try {
        mainStore.loading = true;
        const response = await bookApi.getReviews(reviewsStore.reviews.current_page + 1);

        if (response.status === 200) {
            const newReviews = response.data.data;
            const currentPage = response.data.current_page;
            const lastPage = response.data.last_page;
            const nextPageUrl = response.data.next_page_url;

            // Add new reviews to the store
            reviewsStore.addReviews(newReviews, currentPage, lastPage, nextPageUrl);
        } else {
            toast.error(response.data.message);
        }
    } catch (error) {
        toast.error('Oops! Something went wrong', error);
    } finally {
        mainStore.loading = false;
    }
};
</script>

<style scoped>
</style>
