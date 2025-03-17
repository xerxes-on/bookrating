import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useHomeStore = defineStore(
    'homeStore',
    () => {
        const trending = ref(null)
        const suggestions = ref(null)
        const images = () => {
            suggestions.value.map((book) => book.image)
        }
        const resetStore = () => {
            trending.value = null
            suggestions.value = null
        }
        function setTrending(s){
            trending.value = s
        }
        function setSuggestions(suggest){
            suggestions.value = suggest
        }
        return {
            trending,
            suggestions,
            images,
            resetStore,
            setTrending,
            setSuggestions
        }
    },
    { persist: true }
)
