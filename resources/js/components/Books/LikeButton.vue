<template>
    <button @click="like">
        <i :class="['fa-heart text-red text-xl', liked ? 'animate__heartBeat fa-solid' : 'fa-regular animate__backOutDown']"></i>
    </button>
</template>

<script setup>
import likeApi from '@/api/like.js'
import { useToast } from 'vue-toastification'

const toast = useToast()

const props = defineProps({
    id: {
        required: true,
        type: Number,
    },
    like_what:{
        type: String,
        required: true
    }
})
const liked = defineModel({
    type: Boolean,
})

const like = async () => {
    try {
        let like_response;
        if(props.like_what === 'quote' ){
             like_response = await likeApi.like_quote(props.id)
        }else{
             like_response = await likeApi.like_review(props.id)
        }
        if (like_response.status === 200) {
            liked.value = !liked.value
        } else {
            toast.error('Please try again!')
        }
    } catch (e) {
        toast.error('Oops try again!')
        console.log(e)
    }
}
</script>
