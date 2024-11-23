import {useAuth} from "~/store/auth.js";

export default defineNuxtRouteMiddleware((to, from) => {
    const store = useAuth();
    const isAuthenticated = store.isAuthenticated;

    if (!isAuthenticated && to.path !== '/login') {
        return navigateTo('/login')
    }
})