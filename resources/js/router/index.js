import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth.js'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
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
})
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()
    const isAuthenticated = authStore.isAuthorized

    if (!isAuthenticated && !['login', 'register'].includes(to.name)) {
        next({ name: 'login' })
    } else if (isAuthenticated && ['login', 'register'].includes(to.name)) {
        next({ name: 'home' })
    } else {
        next()
    }
})

export default router
