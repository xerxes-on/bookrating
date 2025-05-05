<script setup>
import { ref, computed, watch } from 'vue'
import { useToast } from 'vue-toastification'
import { useMainStore } from '@/stores/main.js'
import { useBookStore } from '@/stores/book.js'
import bookApi from '@/api/book.js'
import FollowAuthorButton from '@/components/Books/FollowAuthorButton.vue'
import { vConfetti } from '@neoconfetti/vue'

/**
 * PROPS
 */
const props = defineProps({
    author: {
        type: Object,
        required: true
    },
    book: {
        type: Object,
        required: true
    }
})

/**
 * STORES & UTILITIES
 */
const toast      = useToast()
const mainStore  = useMainStore()
const bookStore  = useBookStore()

/**
 * STATE
 */
const isFlipped       = ref(false)
const isSubmitting    = ref(false)
const showConfetti    = ref(false)
const rating          = ref(0)
const comment         = ref('')

/**
 * COMPUTED / DERIVED
 */
const hasText         = computed(() => comment.value.trim().length > 0)
const canSubmit       = computed(() => rating.value > 0 && hasText.value && !isSubmitting.value)

/**
 * ACTIONS
 */
async function submitReview () {
    if (!canSubmit.value) {
        toast.warning('Please rate the book and write a short comment first.')
        return
    }

    isSubmitting.value = true
    try {
        await bookApi.rateBook({
            rating : rating.value,
            comment: comment.value,
            book_id: props.book.id
        })

        // refresh reviews list
        const { data } = await bookApi.getBookReviews(props.book.id)
        bookStore.reviews = data

        // celebrate 🎉
        showConfetti.value = true
        setTimeout(() => {
            showConfetti.value = false
            comment.value = ''
            rating.value = 0
            isFlipped.value = false
        }, 3000)
        toast.success('Thanks for your review!')
    } catch (err) {
    } finally {
        isSubmitting.value = false
    }
}

</script>

<template>
    <!-- AUTHOR HEADER -->
    <section v-if="author" class="relative mx-auto my-16 max-w-5xl bg-primary p-6 rounded-2xl shadow">
        <div class="flex flex-col lg:flex-row gap-6 items-start">
            <!-- avatar -->
            <img :src="author.data.author_picture" alt="Author avatar" class="size-40 shrink-0 rounded-full object-cover" />

            <!-- info -->
            <div class="flex-1 space-y-4">
                <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-3xl font-bold">{{ author.data.name }}</h2>
                    <FollowAuthorButton v-model="author.isFollowed" :author="author" />
                </header>

                <p class="text-gray-600">
                    Books: <span class="font-semibold text-dark_blue">{{ author.books_count || 0 }}</span>
                    · Followers: <span class="font-semibold">{{ author.data.followers_count || 0 }}</span>
                </p>

                <p class="text-gray-700">{{ author.data.about_author }}</p>
            </div>
        </div>
    </section>

    <!-- RATING CARD / REVIEW FORM (flip‑card) -->
    <section class="mx-auto max-w-4xl">
        <div class="relative h-[580px] w-full [perspective:2000px]">
            <div :class="['absolute inset-0 transition-transform duration-700', { 'rotate-y-180': isFlipped }]" class="w-full h-full [transform-style:preserve-3d]">
                <!-- FRONT -->
                <div class="absolute inset-0 backface-hidden flex flex-col items-center justify-center rounded-2xl bg-orange p-8 text-center space-y-6 shadow-lg">
                    <h1 class="font-tangerine text-5xl sm:text-6xl">Rating & Reviews</h1>
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="avatar" class="size-24 rounded-full" />
                    <p class="text-lg">What do you think?</p>

                    <!-- static community rating -->
                    <div>
                        <div class="flex items-center justify-center text-yellow mb-2">
                            <i v-for="n in 5" :key="n" :class="n <= 4 ? 'fa-solid fa-star' : 'fa-regular fa-star'" class="mx-1 text-xl"></i>
                        </div>
                        <p class="text-gray-700">4.5 ({{ book.ratings_count }} ratings · {{ book.reviews_count }} reviews)</p>
                    </div>

                    <button @click="isFlipped = true" class="rounded-xl bg-dark_blue px-6 py-3 font-semibold text-white hover:bg-light_blue">Write a Review</button>
                </div>

                <!-- BACK -->
                <div class="absolute inset-0 rotate-y-180 backface-hidden flex items-center justify-center rounded-2xl bg-primary p-6">
                    <form @submit.prevent="submitReview" class="w-full max-w-lg space-y-6 bg-orange p-6 rounded-2xl shadow-lg relative">
                        <div v-if="showConfetti" v-confetti="{ particleCount: 800 }" class="absolute inset-0" />

                        <header class="text-center space-y-2">
                            <h2 class="text-2xl font-bold">Write a Review</h2>
                            <img :src="book.image" alt="book cover" class="mx-auto size-24 rounded object-cover" />
                            <h3 class="text-lg font-semibold">{{ book.title }}</h3>
                            <p class="text-gray-600">{{ book.author.data.name }}</p>
                        </header>

                        <!-- star picker -->
                        <div class="flex justify-center gap-2 text-4xl">
              <span
                  v-for="star in 5"
                  :key="star"
                  class="cursor-pointer transition-transform"
                  :class="star <= rating ? 'text-yellow scale-110' : 'text-gray-300'"
                  @click="rating = star"
              >★</span>
                        </div>

                        <textarea
                            v-model="comment"
                            rows="4"
                            placeholder="Your thoughts…"
                            class="w-full resize-none rounded-xl p-3 placeholder-gray-400"></textarea>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="isFlipped = false" class="rounded-xl border-2 border-dark_blue px-4 py-2 font-medium">Cancel</button>
                            <button :disabled="!canSubmit" class="rounded-xl bg-dark_blue px-4 py-2 font-medium text-white disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ isSubmitting ? 'Submitting…' : 'Submit' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.backface-hidden {
    backface-visibility: hidden;
}
.rotate-y-180 {
    transform: rotateY(180deg);
}
</style>
