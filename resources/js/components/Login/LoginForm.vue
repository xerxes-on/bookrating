<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { email, required } from '@vuelidate/validators'
import authAPI from '@/api/auth.js'
import { useAuthStore } from '@/stores/auth.js'
import Svg from '@/components/common/Image.vue'
import { useMainStore } from '@/stores/main.js'
import { useToast } from 'vue-toastification'

const router = useRouter()
const showPassword = ref(false)
const mainStore = useMainStore()
const toast = useToast()

const data = reactive({
    email: '',
    password: '',
})
const validations = {
    password: { required },
    email: { required, email },
}
const v$ = useVuelidate(validations, data)
const loginHandler = async () => {
        await v$.value.$validate()
        try {
            mainStore.loading = true
            const response = await authAPI.login(data)
            console.log(response)
            if (response.status === 200) {
                const authStore = useAuthStore()
                authStore.setUser(response.data.user)
                authStore.setToken(response.data.authorization.token)
                await router.push({ name: 'home' })
            } else {
                toast.warning(response.data.message)
            }
        } catch (err) {
            toast.error('Oops!'+ err)
        } finally {
            mainStore.loading = false
        }
}
</script>
<template>
    <main class="bg-light_blue w-full rounded-2xl p-4 py-10 mb-2 relative flex flex-col justify-center items-center">
        <h2 class="text-2xl font-bold mb-2">Welcome Back</h2>
        <p class="mb-6">Login to Continue</p>

        <form @submit.prevent="loginHandler" class="flex flex-col justify-center items-center w-full">
            <div class="mb-4 w-1/2">
                <input type="email" v-model="data.email" placeholder="Enter Email" id="email" autocomplete="on" class="w-full p-2 rounded-full" />
                <div class="" v-for="error of v$.email.$errors" :key="error.$uid">
                    <span class="error-msg">{{ error.$message }}</span>
                </div>
            </div>
            <div class="mb-4 relative w-1/2">
                <input
                    :type="showPassword ? 'text' : 'password'"
                    placeholder="Enter Password"
                    v-model="data.password"
                    id="password"
                    autocomplete="on"
                    class="w-full p-2 rounded-full"
                />
                <button @click.prevent="showPassword = !showPassword" class="absolute right-3 top-2.5">
                    <i class="fa-regular" :class="showPassword ? 'fa-eye' : 'fa-eye-slash'" />
                </button>
                <div class="input-errors" v-for="error of v$.password.$errors" :key="error.$uid">
                    <span class="error-msg">{{ error.$message }}</span>
                </div>
            </div>
            <button type="submit" class="w-1/4 bg-primary_dark text-black font-bold rounded-full mb-4">Login</button>
        </form>
        <div class="absolute -top-8 right-4">
            <div class="w-16 h-16 bg-yellow-300 rounded-full flex items-center justify-center">
                <Svg name="login3" ext="svg" w="300px" h="300px" />
            </div>
        </div>
        <div class="absolute bottom-12 left-10">
            <div class="w-16 h-16 bg-yellow-300 rounded-full flex items-center justify-center">
                <Svg name="login1" ext="svg" w="300px" h="300px" />
            </div>
        </div>
    </main>
</template>

<style scoped>
.error-msg {
    color: red;
    font-weight: 500;
}
</style>
