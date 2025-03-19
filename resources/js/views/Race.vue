<script setup>
import Layout from "@/components/common/Layout.vue";
import {ref} from 'vue';
import {vConfetti} from '@neoconfetti/vue';
import 'animate.css';

const formData = ref({
    name: '',
    email: '',
    age: '',
    category: 'beginner',
    agree: false
});

const submitted = ref(false);
const handleSubmit = () => {
    submitted.value = true;
    alert('You have successfully joined the race! 🏁');
    formData.value = {
        name: '',
        email: '',
        age: '',
        category: 'beginner',
        agree: false
    };
    submitted.value = false;
};
</script>

<template>
    <Layout>
        <div v-if="submitted"
             class=" bg-black absolute top-0 left-1/2  animate__animated animate__bounceIn"
             v-confetti="{
          particleCount: 1000,
          spread: 260,
          origin: { y: 0.5, x: 0.5 },
          colors: ['#FF5733', '#33FF57', '#3357FF', '#F3FF33', '#FF33F3'],
          startVelocity: 30,
          scalar: 1.2
        }"
        ></div>
        <div class="max-w-3xl relative mx-auto py-10 px-4">
            <h1 class="text-3xl font-bold text-center mb-8">Join the Reading Race! 🏁</h1>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <form @submit.prevent="(e) => { e.preventDefault(); handleSubmit(); }" class="space-y-6">
                    <div class="space-y-2">
                        <label class="block font-medium">Full Name</label>
                        <input
                            v-model="formData.name"
                            type="text"
                            required
                            class="w-full p-3 border rounded-md focus:ring focus:ring-blue-200"
                        >
                    </div>

                    <div class="space-y-2">
                        <label class="block font-medium">Email Address</label>
                        <input
                            v-model="formData.email"
                            type="email"
                            required
                            class="w-full p-3 border rounded-md focus:ring focus:ring-blue-200"
                        >
                    </div>

                    <div class="space-y-2">
                        <label class="block font-medium">Age</label>
                        <input
                            v-model="formData.age"
                            type="number"
                            min="16"
                            max="99"
                            required
                            class="w-full p-3 border rounded-md focus:ring focus:ring-blue-200"
                        >
                    </div>

                    <div class="space-y-2">
                        <label class="block font-medium">Race Category</label>
                        <select
                            v-model="formData.category"
                            class="w-full p-3 border rounded-md focus:ring focus:ring-blue-200"
                        >
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                            <option value="professional">Professional</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <input
                            v-model="formData.agree"
                            type="checkbox"
                            required
                            id="agree"
                            class="h-4 w-4 mr-2"
                        >
                        <label for="agree" class="ml-2">I agree to the race terms and conditions</label>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-orange_dark hover:bg-primary_dark text-black font-bold py-3 px-4 rounded-md transition"
                    >
                        Join the Race! 🏎️
                    </button>
                </form>
            </div>
        </div>
    </Layout>
</template>
