<script setup>
import Svg from '@/components/common/Image.vue'
import RegisterForm from '@/components/Login/RegisterForm.vue'
</script>

<template>
    <div class="min-h-screen bg-primary flex flex-col items-center justify-center p-4">
        <div class="w-full max-w-screen-md flex flex-col items-center justify-center relative">
            <RegisterForm />
            <p class="text-center">
                Already a user?
                <router-link to="{{ {name: 'login'} }}" class="text-blue-600 font-bold">Login</router-link>
            </p>
        </div>
        <div class="absolute bottom-0 left-0">
            <Svg name="login4" w="350px" h="350px" ext="svg" />
        </div>
        <div class="absolute bottom-4 right-4">
            <Svg name="login2" w="450px" h="450px" ext="svg" />
        </div>
    </div>
</template>

<style scoped></style>
