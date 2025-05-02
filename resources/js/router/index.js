import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth.js'
import {useBookStore} from "@/stores/book.js";
import {useHomeStore} from "@/stores/home.js";
import {useReviewsStore} from "@/stores/reviews.js";
import {useProfileStore} from "@/stores/profile.js";

const router = createRouter({
    history: createWebHistory('/'),
    routes: [
        {
            path: '/login',
            name: 'login',
            component: () => import('@/views/Login.vue'),
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('@/views/Register.vue'),
        },
        {
            path: '/',
            name: 'home',
            component: () => import('@/views/Home.vue'),
        },
        {
            path: '/profile',
            name: 'profile',
            component: () => import('@/views/Profile.vue'),
        },
        {
            path: '/search',
            name: 'search',
            component: () => import('@/views/Search.vue'),
        },
        {
            path: '/mybooks',
            name: 'myBooks',
            component: () => import('@/views/MyBooks.vue'),
        },
        {
            path: '/book/:id',
            name: 'book',
            component: () => import('@/views/Book.vue'),
        },
        {
            path: '/quotes/:id',
            name: 'quote',
            component: () => import('@/views/Quote.vue'),
        },
        {
            path: '/authors',
            name: 'authors',
            component: () => import('@/views/Authors.vue'),
        },
        {
            path: '/author/:id',
            name: 'author',
            component: () => import('@/views/Author.vue'),
        },
        {
            path: '/join-the-race',
            name: 'join',
            component: () => import('@/views/Race.vue'),
        },
        {
            path: '/reviews',
            name: 'reviews',
            component: () => import('@/views/Reviews.vue'),
        },
        {
            path: '/verify',
            name: 'verify',
            component: () => import('@/views/EmailVerify.vue'),
        },
        {
            path: '/logout',
            name: 'logout',
            component: () => import('@/views/Logout.vue'),
        },
        {
            path: '/:pathMatch(.*)*',
            name: 'notFound',
            component: () => import('@/views/NotFound.vue'),
        },
    ],
    scrollBehavior(to) {
        if (to.hash) {
            return { el: to.hash, behavior: 'smooth' }
        }
        return { top: 0 }
    },
})
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()
    const homeStore = useHomeStore()
    const bookStore = useBookStore()
    const reviews = useReviewsStore()
    const profile = useProfileStore()
    const token = authStore.token

    const needsAuth = !['login', 'register'].includes(to.name)
    const isLoggedIn = !!authStore.user && token

    if (needsAuth && !isLoggedIn) {
        authStore.resetStore()
        homeStore.resetStore()
        bookStore.resetStore()
        reviews.resetStore()
        profile.resetProfile()
        return next({ name: 'login' })
    }

    if ((to.name === 'login' || to.name === 'register') && isLoggedIn) {
        return next({ name: 'home' })
    }
    next()
})

export default router

