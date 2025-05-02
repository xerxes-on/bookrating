<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import reviewsApi from '@/api/reviews.js'
import { formatDate } from '@/utilities.js'
import FollowButton from '@/components/common/FollowButton.vue'
import LikeButton from '@/components/Books/LikeButton.vue'

const props = defineProps({
    bookId: { type: [String, Number], required: true },
})

const reviews = ref([])
const page = ref(1)
const lastPage = ref(null)
const loading = ref(false)

const loadMore = async () => {
    if (loading.value) return
    if (lastPage.value && page.value > lastPage.value) return

    loading.value = true
    try {
        const payload = await reviewsApi.getReviews(props.bookId, page.value)
        reviews.value.push(...payload.data.data)
        lastPage.value = payload.data.last_page
        page.value++
    } finally {
        loading.value = false
    }
}

const onScroll = () => {
    const { scrollTop, clientHeight, scrollHeight } = document.documentElement
    if (scrollTop + clientHeight >= scrollHeight - 50) {
        // loadMore()
    }
}
onMounted(() => {
    loadMore()
    window.addEventListener('scroll', onScroll)
})
onBeforeUnmount(() => {
    window.removeEventListener('scroll', onScroll)
})
</script>

<template>
    <section class="mt-12 p-6 rounded-2xl">
        <h2 class="text-4xl font-bold mb-4">Reader Reviews</h2>
        <p class="text-gray-500 mb-6">Total loaded: {{ reviews.length }}</p>

        <div v-for="rev in reviews" :key="rev.id" class="mb-6 p-4 bg-white rounded-xl shadow">
            <div class="flex items-center mb-2" :id="'review-id-'+rev.id">
                <img :src="rev.user.profile_picture" class="w-12 h-12 rounded-full mr-3" />
                <div>
                    <p class="font-semibold">{{ rev.user.name }}</p>
                    <p class="text-sm text-gray-400">
                        @{{ rev.user.username }}  • {{ formatDate(rev.created_at) }}
                    </p>
                </div>
                <!-- Ratings and Reviews -->
                <div class="flex items-center ml-10">
                    <div class="flex justify-center text-2xl items-center mt-2">
                        <span v-for="n in 5" :key="n">
                            <i :class="['text-yellow', n <= rev.rating/2 ? 'fa-solid fa-star' : 'fa-regular fa-star']"></i>
                        </span>
                    </div>
                </div>
            </div>
            <p class="mb-2">{{ rev.comment }}</p>
            <div class="flex items-center space-x-4">
                <LikeButton :count="rev.likes" :liked="rev.is_liked" :id="rev.id" like_what="review"/>
                <FollowButton :whoToFollow="rev.user.id"/>
            </div>

        </div>

        <div v-if="loading" class="text-center py-4">Loading more…</div>
        <div v-else-if="lastPage && page > lastPage" class="text-center py-4 text-gray-400">
            No more reviews.
        </div>
    </section>
</template>
