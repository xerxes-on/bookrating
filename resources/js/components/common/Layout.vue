<script setup>
import DropDown from '@/components/common/DropDown.vue'
import { ref } from 'vue'

const activeMenu = ref(null)
const showMenu = (menu) => {
    activeMenu.value = menu
}
const hideMenu = () => {
    activeMenu.value = null
}
</script>

<template>
    <!--Header here-->

    <header class="bg-primary p-8 pt-10 flex items-center justify-between">
        <!-- Logo Section -->
        <RouterLink :to="{ name: 'home' }">
            <div class="flex items-center space-x-2">
                <img alt="Logo" class="w-11 h-11 rounded-full object-cover" :src="'/images/logo.png'" />
                <span class="text-2xl font-bold">LetsRate</span>
            </div>
        </RouterLink>
        <!-- Navigation Section -->
        <nav class="flex space-x-8">
                <RouterLink :to="{ name: 'search' }" class="hover:bg-light_blue p-2 rounded-2xl font-semibold">Search </RouterLink>
                <RouterLink :to="{ name: 'myBooks' }" class="hover:bg-light_blue p-2 rounded-2xl font-semibold">Books </RouterLink>
                <RouterLink :to="{ name: 'authors' }" class="hover:bg-light_blue p-2 rounded-2xl font-semibold">Authors </RouterLink>

                <a class="hover:bg-light_blue p-2 rounded-2xl font-semibold" href="/reviews">Reviews</a>
            <RouterLink :to="{ name: 'profile' }">
                <img alt="Profile" class="w-10 h-10 rounded-full object-cover" :src="'/images/logo.png'" />
            </RouterLink>
        </nav>
    </header>

    <slot></slot>

    <!--    Footer-->
    <footer class="bg-primary_dark py-10 px-6">
        <!-- Copyright -->
        <div class="border-t border-gray-300 mt-8 pt-6 text-center text-black">
            <p>Copyright ©2025 Team C Wolverhampton . All rights reserved</p>
        </div>
    </footer>
</template>
