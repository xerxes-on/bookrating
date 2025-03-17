import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useHomeStore } from '@/stores/home.js'
import { useBookStore } from '@/stores/book.js'
import { useReviewsStore } from '@/stores/reviews.js'
import { useProfileStore } from '@/stores/profile.js'

export const useAuthStore = defineStore(
    'authStore',
    () => {
        const user = ref(null)
        const token = ref(null)

        const resetStore = () => {
            user.value = null
            token.value = null
        }
        const setUser = (newUser) => {
            user.value = newUser
        }
        const isAuthorized = computed(() => !!user.value)

        const setToken = (newToken) => {
            token.value = newToken
        }
        const router = useRouter()
        const authStore = useAuthStore()
        const homeStore = useHomeStore()
        const bookStore = useBookStore()
        const reviews = useReviewsStore()
        const profile = useProfileStore()
        const logout = () => {
            authStore.resetStore()
            homeStore.resetStore()
            bookStore.resetStore()
            reviews.resetStore()
            profile.resetProfile()
            router.push({ name: 'login' })
        }
        return {
            user,
            token,
            setUser,
            setToken,
            resetStore,
            isAuthorized,
            logout
        }
    },

    { persist: true }
)
