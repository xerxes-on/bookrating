<script setup>
import { reactive, ref, toRef } from 'vue'
import { useRouter } from 'vue-router'
import { email, required, sameAs } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
import { useAuthStore } from '@/stores/auth.js'
import authAPI from '@/api/auth.js'
import Svg from '@/components/common/Image.vue'
import { useMainStore } from '@/stores/main.js'
import { useToast } from 'vue-toastification'

const showPassword = ref(false)
const router = useRouter()
const authStore = useAuthStore()
const mainStore = useMainStore()
const toast = useToast()
const data = reactive({
    name: '',
    email: '',
    password: '',
    confirm_password: '',
})
const passwordRef = toRef(data, 'password')
const validations = {
    name: { required },
    email: { required, email },
    password: { required },
    confirm_password: {
        required,
        sameAsPassword: sameAs(passwordRef),
    },
}
const v$ = useVuelidate(validations, data)
const registerHandler = async () => {
    const validation = await v$.value.$validate()
    if (validation) {
        try {
            mainStore.loading = true
            const response = await authAPI.register(data)
            if (response.status === 200) {
                authStore.setUser(response.data.user)
                authStore.setToken(response.data.authorization.token)
                await router.push({ name: 'verify' })
            } else {
                toast.warning(response.data.message)
            }
        } catch (err) {
            toast.error('Oops!' + err)
        } finally {
            mainStore.loading = false
        }
    }
}
</script>

<template>
    <div class="bg-light_blue w-full rounded-2xl p-4 py-10 mb-2 relative flex flex-col justify-center items-center">
        <h2 class="text-2xl font-bold mb-2">Welcome to LetsRate</h2>
        <p class="mb-6">Sign Up to Continue</p>
        <!--Form-->
        <form class="flex flex-col justify-center items-center w-full" @submit.prevent="registerHandler">
            <div class="mb-4 w-1/2">
                <input type="text" placeholder="Enter Name" v-model="data.name" class="w-full p-2 rounded-full" />
                <div class="input-errors" v-for="error of v$.name.$errors" :key="error.$uid">
                    <span class="error-msg">{{ error.$message }}</span>
                </div>
            </div>
            <div class="mb-4 w-1/2">
                <input type="email" v-model="data.email" placeholder="Enter email" id="email" autocomplete="on" class="w-full p-2 rounded-full" />
                <div class="input-errors" v-for="error of v$.email.$errors" :key="error.$uid">
                    <span class="error-msg">{{ error.$message }}</span>
                </div>
            </div>
            <div class="mb-4 relative w-1/2">
                <input
                    :type="showPassword ? 'text' : 'password'"
                    placeholder="Enter Password"
                    id="password"
                    autocomplete="on"
                    v-model="data.password"
                    class="w-full p-2 rounded-full"
                />
                <button @click.prevent="showPassword = !showPassword" class="absolute right-3 top-2.5">
                    <i class="fa-regular" :class="showPassword ? 'fa-eye' : 'fa-eye-slash'" />
                </button>
                <div class="input-errors" v-for="error of v$.password.$errors" :key="error.$uid">
                    <span class="error-msg">{{ error.$message }}</span>
                </div>
            </div>

            <div class="mb-4 relative w-1/2">
                <input
                    :type="showPassword ? 'text' : 'password'"
                    placeholder="Confirm Password"
                    autocomplete="on"
                    v-model="data.confirm_password"
                    id="confirm_password"
                    class="w-full p-2 rounded-full"
                />
                <button class="absolute right-3 top-2.5">
                    <i class="fa-regular" :class="showPassword ? 'fa-eye' : 'fa-eye-slash'" />
                </button>
                <div class="input-errors" v-for="error of v$.confirm_password.$errors" :key="error.$uid">
                    <span class="error-msg">{{ error.$message }}</span>
                </div>
            </div>
            <button class="w-1/4 bg-primary_dark text-black font-bold rounded-full mb-4">Register</button>
        </form>

        <div class="text-center mb-4">
            <p class="text-sm mb-2">Sign up with</p>
            <div class="flex justify-center space-x-4">
                <a href="#" class="text-2xl"><i class="fa-brands fa-google"></i></a>
                <a href="#" class="text-2xl"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" class="text-2xl"><i class="fa-brands fa-apple"></i></a>
                <a href="#" class="text-2xl"><i class="fa-brands fa-twitter"></i></a>
            </div>
        </div>

        <!-- Decorative elements -->
        <div class="absolute -top-8 right-4">
            <div class="w-16 h-16 bg-yellow-300 rounded-full flex items-center justify-center">
                <!-- Book icon placeholder -->
                <Svg name="login3" ext="svg" w="300px" h="300px" />
            </div>
        </div>
        <div class="absolute bottom-12 left-10">
            <div class="w-16 h-16 bg-yellow-300 rounded-full flex items-center justify-center">
                <!-- Book icon placeholder -->
                <Svg name="login1" ext="svg" w="300px" h="300px" />
            </div>
        </div>
    </div>
</template>

<style scoped>
.error-msg {
    color: red;
    font-weight: 500;
}
</style>
