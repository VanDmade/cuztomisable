import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        name: 'login',
        path: '/login',
        alias: '/',
        meta: { require_authentication: false },
        component: () => import("./views/authentication/Login.vue"),
    },{
        name: 'registration',
        path: '/registration/:code?',
        meta: { require_authentication: false },
        component: () => import("./views/authentication/Registration.vue"),
    },{
        name: 'forgot',
        path: '/forgot',
        meta: { require_authentication: false },
        component: () => import("./views/authentication/Forgot.vue"),
    },{
        name: 'reset',
        path: '/reset/:token',
        meta: { require_authentication: false },
        component: () => import("./views/authentication/Reset.vue"),
    },{
        name: 'mfa',
        path: '/mfa/:token',
        meta: { require_authentication: false },
        component: () => import("./views/authentication/MFA.vue"),
    },{
        name: 'portal',
        path: '/portal',
        meta: { require_authentication: true },
        component: () => import("./views/Portal.vue"),
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});