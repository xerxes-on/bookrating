import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useBookStore = defineStore(
    'bookStore',
    () => {
        const book = ref('')
        const reviews = ref('')
        const quote = ref('')
        const rate_book = ref('')
        const resetStore = () => {
            book.value = null
            reviews.value = null
            quote.value = null
            rate_book.value = null
        }
        return {
            book,
            reviews,
            quote,
            rate_book,
            resetStore,
        }
    },
    { persist: true }
)
