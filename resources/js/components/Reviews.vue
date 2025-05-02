<template>
    <div class="bg-primary min-h-screen p-4 flex flex-col items-center">
        <h1 class="text-center text-2xl font-bold mb-6">
            Most popular 100 reviewers this week
        </h1>

        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 max-w-screen-xl w-full"
        >
            <div
                v-for="review in reviews"
                :key="review.id"
                class="flex bg-primary p-4 rounded-xl shadow-sm space-x-4"
            >
                <div class="w-1/4 text-center" v-if="review.user">
                    <div class="flex items-center justify-center">
                        <img
                            :src="review.user.profile_picture"
                            alt="User Avatar"
                            class="w-16 h-16 rounded-full bg-cover"
                        />
                        <div>
                            <p class="ml-2 font-semibold text-xl">{{ review.user.name }}</p>
                        </div>
                    </div>
                    <div class="flex justify-center text-2xl items-center mt-2">
            <span v-for="n in 5" :key="n">
              <i
                  :class="[
                  'text-yellow',
                  n <= review.rating / 2
                    ? 'fa-solid fa-star'
                    : 'fa-regular fa-star'
                ]"
              ></i>
            </span>
                    </div>
                    <div>
                        <FollowButton
                            :whoToFollow="review.user.id"
                            v-model="review.user.is_followed"
                        />
                    </div>
                </div>
                <div class="flex-1 pl-3">

                    <router-link
                        :to="{ name: 'book', params: { id: review.book_id }, hash: '#xyz' }"
                    >
                        <p class="text-gray text-xl">{{ formatDate(review.created_at) }}</p>
                        <p class="mt-2 text-gray-800 text-sm leading-relaxed">
                            {{ review.comment }}
                        </p>

                    </router-link>
                    <div
                        class="flex  w-1/2 space-x-4 mt-4"
                    >
                        <LikeButton
                            :id="review.id"
                            like_what="review"
                            :count="review.likes"
                            :liked="review.is_liked"
                        />
                    </div>
                </div>
            </div>
        </div>

        <button
            v-if="showSeeMoreButton"
            @click="loadMoreReviews"
            :disabled="loading"
            class="bg-dark_blue text-white text-xl p-2 rounded-2xl m-5 hover:shadow-2xl"
        >
            <span v-if="loading">Loading…</span>
            <span v-else>See More</span>
        </button>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue'
import bookApi from '@/api/book.js'
import {useMainStore} from '@/stores/main.js'
import {useToast} from 'vue-toastification'
import {formatDate} from '@/utilities.js'
import FollowButton from '@/components/common/FollowButton.vue'
import LikeButton from '@/components/Books/LikeButton.vue'

// global loading indicator
const mainStore = useMainStore()
const toast = useToast()

// local state
const reviews = ref([])
const currentPage = ref(0)
const lastPage = ref(null)
const loading = ref(false)

// show button until we've fetched the last page
const showSeeMoreButton = computed(
    () => lastPage.value !== null && currentPage.value < lastPage.value
)

async function loadReviews(page = 1) {
    if (loading.value) return

    loading.value = true
    mainStore.loading = true

    try {
        const response = await bookApi.getReviews(page)
        if (response.status === 200) {
            const payload = response.data

            // on page 1, replace; otherwise append new, de-duplicated
            if (page === 1) {
                reviews.value = payload.data
            } else {
                const existing = new Set(reviews.value.map(r => r.id))
                const toAdd = payload.data.filter(r => !existing.has(r.id))
                reviews.value.push(...toAdd)
            }

            currentPage.value = payload.current_page
            lastPage.value = payload.last_page
        } else {
            toast.error(response.data.message)
        }
    } catch (err) {
        toast.error('Oops! Something went wrong')
        console.error(err)
    } finally {
        loading.value = false
        mainStore.loading = false
    }
}

function loadMoreReviews() {
    loadReviews(currentPage.value + 1)
}

onMounted(() => {
    loadReviews(1)
})
</script>
