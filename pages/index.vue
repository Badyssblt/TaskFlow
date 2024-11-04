<script setup >

import {useAuth} from "~/store/auth.js";

const store = useAuth();
const isAuthenticated = ref(store.isAuthenticated);


watch(
    () => store.isAuthenticated,
    (newVal) => {
      isAuthenticated.value = newVal;
    }
);
</script>

<template>
  <div class="w-32 h-32 bg-primary rounded-full blur-3xl absolute top-24"></div>
  <div class="w-32 h-32 bg-primary rounded-full blur-3xl absolute bottom-1/2 right-48"></div>
  <div class="flex flex-col items-center md:mt-24">
    <h2 class="text-6xl font-medium text-center">Organisez vos projets<br/>
      avec vos amis </h2>
    <p class="text-center opacity-60 my-6">Créer des projets avec des tâches, des dead-lines et d’autres outils</p>
    <div class="flex gap-4">
      <NuxtLink to="/register" class="bg-primary px-8 py-2 rounded border-transparent hover:bg-transparent hover:border-white/20 border transition-all" v-if="!isAuthenticated">Inscrivez vous</NuxtLink>
      <NuxtLink to="/dashboard" class="bg-primary px-8 py-2 rounded border-transparent hover:bg-transparent hover:border-white/20 border transition-all" v-else>Mon espace</NuxtLink>
    </div>
  </div>
</template>

<style scoped>

</style>