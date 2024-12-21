import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../Pages/HomePageContent.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView,
        },
        {
            path: '/about',
            name: 'about',
            component: () => import('../Pages/AboutPage.vue'),
        },
        {
            path: '/:catchAll(.*)',
            name: '404',
            component: () => import('../Pages/NotFound.vue'),
        },
    ],
})

export default router
