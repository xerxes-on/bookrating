<script setup>
import UserInfo from '@/components/Profile/UserInfo.vue'
import UserReading from '@/components/Profile/UserReading.vue'
import UserStats from '@/components/Profile/UserStats.vue'
import LogoutButton from '@/components/Profile/LogoutButton.vue'
import Layout from '@/components/common/Layout.vue'
</script>

<template>
    <Layout>
        <div class="bg-primary p-6">
            <div class="max-w-6xl mx-auto">
                <UserInfo />
                <UserReading />
                <UserStats />
                <LogoutButton />
            </div>
        </div>
    </Layout>
</template>

<style scoped></style>
