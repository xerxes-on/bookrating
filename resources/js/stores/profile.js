import { defineStore } from 'pinia'
import { computed, ref } from 'vue'

export const useProfileStore = defineStore(
    'profileStore',
    () => {
        const user = ref(null)
        function setUser(user_new){
            user.value = null
            user.value = user_new
        }
        function getUser(){
            return user.value
        }
        const books_status = {
            finished: computed(() => user.value?.user?.books?.filter((book) => book.pivot.status === 0) || []),
            reading: computed(() => user.value?.user?.books?.filter((book) => book.pivot.status === 1) || []),
            wantToRead: computed(() => user.value?.user?.books?.filter((book) => book.pivot.status === 2) || []),
        }

        const reviewCounts = computed(() => {
            const counts = {}
            user.value?.user?.reviews?.forEach((review) => {
                const date = new Date(review.created_at)
                const monthYear = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`

                if (!counts[monthYear]) {
                    counts[monthYear] = 0
                }
                counts[monthYear]++
            })
            return counts
        })

        const reviewMonths = computed(() => Object.keys(reviewCounts.value)) // Array of months
        const reviewValues = computed(() => Object.values(reviewCounts.value)) // Array of counts

        function resetProfile(){
            user.value = null
        }
        return {
            user,
            books_status,
            reviewCounts,
            reviewMonths,
            reviewValues,
            setUser,
            resetProfile,
            getUser
        }
    },
    { persist: true }
)
