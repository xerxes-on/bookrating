<script setup>
import Svg from '@/components/common/Image.vue'
import LoginForm from '@/components/Login/LoginForm.vue'
</script>
<template>
    <div class="min-h-screen bg-primary flex flex-col items-center justify-center p-4">
        <div class="w-full max-w-screen-md flex flex-col items-center justify-center">
            <!-- Login Card -->
            <LoginForm />
            <p class="text-center">
                New User?
                <router-link to="/register" class="text-blue-600 font-bold">Sign Up</router-link>
            </p>
        </div>
        <div class="absolute bottom-0 left-0">
            <Svg name="login4" ext="svg" w="350px" h="350px" />
        </div>
        <div class="absolute bottom-4 right-4">
            <Svg name="login2" ext="svg" w="450px" h="450px" />
        </div>
    </div>
</template>
