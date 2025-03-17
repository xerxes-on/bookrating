<template>
    <button @click="follow(author?.data?.id)" class="mt-2 bg-dark_blue text-white px-10 py-1 rounded-full text-sm hover:bg-light_blue">
        {{ followed ? 'Unfollow' : 'Follow' }}
    </button>
</template>

<script setup>
import followApi from '@/api/follow.js'

defineProps({
    author: {
        required: true,
        type: Object,
    }
})
const followed = defineModel({
    type: Boolean,
})
const follow = async (id) => {
    try {
        const follow_response = await followApi.followAuthor(id)
        if (follow_response.status === 200) {
            followed.value = !followed.value
        } else {
            console.log(follow_response.data)
        }
    } catch (e) {
        console.log(e)
    }
}
</script>
