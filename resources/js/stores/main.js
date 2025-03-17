import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useMainStore = defineStore(
    'mainStore',
    () => {
        const loading = ref(false)
        return {
            loading,
        }
    },
    { persist: true }
)
