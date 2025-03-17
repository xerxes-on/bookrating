<script setup></script>

<template>
    <!-- Reading Section -->
    <section class="bg-orange p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-bold mb-4">Currently Reading</h2>
        <div class="flex justify-between">
            <div class="flex">
                <img :src="'/images/home5.svg'" alt="Book Cover" class="w-48 rounded-lg object-cover mr-4" />
            </div>
            <div class="w-1/2">
                <h1 class="text-2xl font-bold p-4">Book Name</h1>
                <p class="text-gray-600 p-4">Author Name</p>
                <div class="w-full bg-white rounded-full m-5 dark:bg-gray-700">
                    <div class="bg-dark_blue h-5 rounded-full" style="width: 85%"></div>
                </div>
                <h2 class="mt-2 text-sm text-center">83% completed</h2>
            </div>
            <div class="flex items-center justify-center">
                <button class="bg-dark_blue h-10 text-white px-4 py-2 rounded-lg">Continue</button>
            </div>
        </div>
    </section>
</template>

<style scoped></style>
