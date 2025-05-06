import { createRouter, createWebHistory } from 'vue-router';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';
import Tasks from '../pages/Tasks.vue';

const routes = [
    { path: '/', redirect: '/login' },
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/tasks', component: Tasks }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;