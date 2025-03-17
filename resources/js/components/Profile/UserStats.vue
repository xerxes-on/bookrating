<script setup>
import { Bar } from 'vue-chartjs'
import { BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, Title, Tooltip } from 'chart.js'
import { useProfileStore } from '@/stores/profile.js'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)
const profileStore = useProfileStore()
const data = {
    labels: profileStore.reviewMonths,
    datasets: [
        {
            label: 'Number of Books Rated',
            backgroundColor: '#f87979',
            data: profileStore.reviewCounts,
        },
    ],
}
const options = {
    responsive: true,
    maintainAspectRatio: false,
    barThickness: 30,
    hoverBackgroundColor: '#000000',
    scales: {
        y: {
            beginAtZero: true,
            max: Math.max(Object.values(profileStore.reviewCounts)),
            label: 'hours',
        },
    },
}
</script>

<template>
    <!-- Statistics Section -->
    <section class="bg-orange p-6 rounded-lg shadow-md mb-6 w-full">
        <h2 class="text-xl font-bold mb-4">This Week's Statistics</h2>
        <div class="flex justify-between items-center">
            <!-- Weekly Reading Stats -->
            <Bar :data="data" :options="options" style="width: 70%" />
        </div>
    </section>
</template>

<style scoped></style>
