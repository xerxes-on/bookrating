import axios from 'axios'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth.js'
import { useRouter } from 'vue-router'
import { useHomeStore } from '@/stores/home.js'
import { useBookStore } from '@/stores/book.js'
import { useReviewsStore } from '@/stores/reviews.js'
import { useProfileStore } from '@/stores/profile.js'
import {useToast} from 'vue-toastification'


const authStore = useAuthStore()


const client = axios.create({
    baseURL: window.mainAppuUrl,
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
})

const { token } = storeToRefs(authStore)

client.interceptors.request.use(
    (config) => {
        if (token.value) {
            config.headers.Authorization = `Bearer ${token.value}`
        }
        return config
    },
    (error) => {
        return Promise.reject(error)
    }
)
client.interceptors.response.use(
    (response) => response,
    (error) => {
        const toast = useToast()
        const status = error.response?.status
        const data   = error.response?.data
        const router = useRouter()
        if (status === 422 && data.errors) {
            Object.values(data.errors)
                .flat()
                .forEach(msg => toast.warning(msg))
        }
        else if (data?.message) {
            toast.error(data.message)
        }
        if (status === 401 || status === 419) {
            const authStore    = useAuthStore()
            const homeStore    = useHomeStore()
            const bookStore    = useBookStore()
            const reviewsStore = useReviewsStore()
            const profileStore = useProfileStore()

            authStore.resetStore()
            homeStore.resetStore()
            bookStore.resetStore()
            reviewsStore.resetStore()
            profileStore.resetProfile()
            router.push({ name: 'login' })
        }

        return Promise.reject(error)
    }
)
export default client
