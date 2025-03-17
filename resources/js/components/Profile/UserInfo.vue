<script setup>
import { ref } from 'vue'
import { useProfileStore } from '@/stores/profile.js'
import editProfileApi from '@/api/editProfile.js'
import { formatDate, removeNullKeys } from '@/utilities.js'
import { useMainStore } from '@/stores/main.js'
import { useToast } from 'vue-toastification'
import { vConfetti } from '@neoconfetti/vue'
import 'animate.css'
const profileStore = useProfileStore()
const mainStore = useMainStore()
const toast = useToast()
const user = profileStore.getUser()

const isFlipped = ref(false)
const visible = ref(false)
const data = ref({
    name: null,
    username: null,
    date_of_birth: null,
    gender: null,
    profile_picture: null,
})
function handleFileChange(event) {
    data.value.profile_picture = event.target.files[0]
}
async function updateProfile() {
    try {
        mainStore.loading = true
        removeNullKeys(data.value)
        const response = await editProfileApi.edit(data.value)
        if (response.status === 200) {
            profileStore.setUser(response.data.user)
            visible.value = true
            user.value = response.data.user
            isFlipped.value = false
        } else {
            toast.warning(response.data.message)
        }
    } catch (error) {
        toast.error('Oops! '+ error)
    } finally {
        mainStore.loading = false
    }
}
console.log(user.user)
</script>

<template>
    <section v-if="user" class="mb-6 h-[32rem]">
        <div :class="['flip-card-inner', { flipped: isFlipped }]">
            <!-- Front Side (Profile Header) -->
            <div class="flip-card-front bg-orange p-6 rounded-2xl shadow-md w-96 h-full">
                <div class="flex justify-between">
                    <div v-if="visible" v-confetti="{ particleCount: 1000,}" class="absolute celebrate left-1/2" />
                    <div class="flex flex-col">
                        <img src="https://cataas.com/cat" alt="User Avatar" class="h-64 w-48 rounded-2xl object-cover mr-6" />
                        <div>
                            <h1 class="text-2xl font-extra-light">Details</h1>
                            <p class="text-dark_blue font-extralight inline">Gender:</p>
                            <span class="mr-2">{{ user.gender === 2 ? 'No data' : (user.gender === 1 ? 'Male': 'Female' ) }} </span>
                            <br />
                            <p class="text-dark_blue inline">Date of Birth:</p>
                            <span>{{ user.date_of_birth == null ? 'No data' : formatDate(user.date_of_birth) }} </span>
                        </div>
                    </div>
                    <div class="p-2">
                        <h1 class="font-bold text-3xl">{{ user.name }}</h1>
                        <span>@{{ user.username }}</span>
                        <div class="flex justify-between p-4 w-full">
                            <div class="bg-light_blue p-4 m-4 rounded-2xl shadow-2xl text-white flex flex-col justify-center items-center">
                                <h2 class="font-bold text-2xl">{{ user.followers.length }}</h2>
                                <p class="text-dark_blue">Followers</p>
                            </div>
                            <div class="bg-light_blue p-4 m-4 rounded-2xl shadow-2xl text-white flex flex-col justify-center items-center">
                                <h2 class="font-bold text-2xl">{{ user.following?.length }}</h2>
                                <p class="text-dark_blue">Following</p>
                            </div>
                            <div class="bg-light_blue p-4 m-4 rounded-2xl shadow-2xl text-white flex flex-col justify-center items-center">
                                <h2 class="font-bold text-2xl">{{ user.reviews?.length }}</h2>
                                <p class="text-dark_blue">Rated books</p>
                            </div>
                        </div>
                        <p class="text-dark_blue inline">Joined in:</p>
                        <span class="mr-4">{{ formatDate(user.created_at) }}</span>
                        <br />
                        <p class="mt-2 text-gray-600">
                            Favorite <span class="text-dark_blue text-lg">Genres</span>: <br />
                            <span v-for="(genre, index) in user.liked_genres" :key="index">{{ genre.category_name }}</span>
                        </p>
                        <h1 class="text-2xl mt-2">My BookShelves</h1>
                        <div class="flex justify-between">
                            <div class="bg-dark_blue px-8 py-2 m-4 rounded-xl shadow-2xl text-white flex flex-col justify-center items-center">
                                <p class="">Read</p>
                                <p class="text-xl">{{ profileStore.books_status.finished.length }}</p>
                            </div>
                            <div class="bg-dark_blue py-2 px-4 m-4 rounded-xl shadow-2xl text-white flex flex-col justify-center items-center">
                                <p class="">Reading</p>
                                <p class="text-xl">{{ profileStore.books_status.reading.length }}</p>
                            </div>
                            <div class="bg-dark_blue py-2 px-4 m-4 rounded-xl shadow-2xl text-white flex flex-col justify-center items-center">
                                <p class="">Want to read</p>
                                <p class="text-xl">{{ profileStore.books_status.wantToRead.length }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <h1 class="text-center text-xl py-5">My Favourite book</h1>
                        <img :src="'/images/home7.svg'" alt="Favorite Book" class="w-44 rounded-lg object-cover" />
                        <button @click="isFlipped = !isFlipped" class="m-5 bg-dark_blue text-white px-4 py-2 rounded-lg mt-10">Edit Profile</button>
                    </div>
                </div>
            </div>

            <!-- Back Side (Edit Form) -->
            <div class="flip-card-back bg-orange p-6 rounded-2xl shadow-md w-96 h-fit">
                <h2 class="text-xl font-bold mb-4">Edit Profile</h2>
                <form @submit.prevent="updateProfile">
                    <div class="flex justify-between">
                        <div class="p-3">
                            <div>
                                <label for="full_name">Full Name</label>
                                <input v-model="data.name" type="text" autocomplete="on" id="full_name" class="rounded-2xl p-2 bg-primary m-5 border-dark_blue" />
                            </div>
                            <div>
                                <label for="username">Username</label>
                                <input v-model="data.username" type="text" autocomplete="on" id="username" class="rounded-2xl p-2 bg-primary m-5 border-dark_blue" />
                            </div>
                            <div>
                                <label for="dob">Date of Birth</label>
                                <input v-model="data.date_of_birth" autocomplete="on" type="date" id="dob" class="rounded-2xl p-2 bg-primary m-5 border-dark_blue" />
                            </div>
                            <div>
                                <label for="gender">Gender</label>
                                <select v-model="data.gender" id="gender" class="rounded-2xl p-2 bg-primary m-5 border-dark_blue">
                                    <option value="2">Prefer not to say</option>
                                    <option value="1">Male</option>
                                    <option value="0">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="p-2">
                            <img src="https://cataas.com/cat" alt="profile" class="h-64 w-48 rounded-2xl object-cover mr-6" />
                            <input class="rounded-2xl p-2 bg-primary m-5 border-dark_blue" type="file" @change="handleFileChange" />
                            <button @click="isFlipped = !isFlipped" class="border-2 m-2 border-dark_blue rounded-2xl p-3 font-semibold">Cancel</button>
                            <button type="submit" class="bg-dark_blue rounded-2xl p-3 text-white font-semibold">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<style scoped>
.flip-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transition: transform 0.6s ease-in-out;
    transform-style: preserve-3d;
}

.flipped {
    transform: rotateY(180deg);
}

.flip-card-front,
.flip-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
}

.flip-card-back {
    transform: rotateY(180deg);
}
</style>
