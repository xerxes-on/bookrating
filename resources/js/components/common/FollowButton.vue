<template>
    <button class="mt-2 bg-dark_blue text-white px-10 py-1 rounded-full text-sm hover:bg-light_blue" @click="follow">
        {{ status ? 'Unfollow' : 'Follow' }}
    </button>
</template>

<script setup>
import followApi from '@/api/follow.js'

const props = defineProps({
    whoToFollow: {
        required: true,
        type: Number,
    },
})
const status = defineModel({
    type: Boolean,
})
const follow = async () => {
    try {
        const follow_response = await followApi.follow(props.whoToFollow)
        if (follow_response.status === 200) {
            status.value = !status.value
        } else {
            console.log(follow_response.data.message)
        }
    } catch (e) {
        console.log(e)
    }
}
</script>
