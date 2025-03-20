import axios from 'axios'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth.js'
import { useRouter } from 'vue-router'
import { useHomeStore } from '@/stores/home.js'
import { useBookStore } from '@/stores/book.js'
import { useReviewsStore } from '@/stores/reviews.js'
import { useProfileStore } from '@/stores/profile.js'



const authStore = useAuthStore()


const client = axios.create({
    baseURL: 'https://bookrating.xerxes.uz/' + 'api/v1',
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
    (response) => {
        return response
    },
    (response) => {
        if(response.status === 401 || response.status ===419){
            const router = useRouter()
            const authStore = useAuthStore()
            const homeStore = useHomeStore()
            const bookStore = useBookStore()
            const reviews = useReviewsStore()
            const profile = useProfileStore()
            authStore.resetStore()
            homeStore.resetStore()
            bookStore.resetStore()
            reviews.resetStore()
            profile.resetProfile()
            router.push({ name: 'login' })
        }
    },
)
export default client
