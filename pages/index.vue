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
  <div class="w-full mt-12  relative border-b border-white/20 pb-24">
    <div class="w-[70%] m-auto border border-white/20 rounded-md">
      <img src="/images/taskflow.png" alt="" class="w-full">
    </div>
    <div class="absolute bottom-24 w-full">
      <svg class="w-full text-primary blur-3xl fill-current opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path d="M0,256L48,245.3C96,235,192,213,288,213.3C384,213,480,235,576,234.7C672,235,768,213,864,181.3C960,149,1056,107,1152,101.3C1248,96,1344,128,1392,144L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
      </svg>
    </div>
  </div>
  <div class="py-48 px-[5%] bg-primary/80 rounded-[50px]">
    <h3 class="font-bold text-6xl">Comment ça marche ?</h3>
    <div class="flex flex-col mt-16 gap-12">
      <div class="flex text-3xl">
        <div class="mr-12">
          <span>01.</span>
          <div class="h-96 w-full border-r-2 border-dashed mt-6 border-white/20">

          </div>
        </div>

        <div>
          <div>
            <h4 class=" font-medium">Créer un projet</h4>
            <p class="text-white/80 mt-6 text-base">Commencer par vous rendre sur <NuxtLink to="/dashboard" class="text-background font-bold hover:brightness-150 transition-all">votre espace</NuxtLink>, puis créer un projet. <br/> Renseigner les informations demandées</p>
          </div>
        </div>
      </div>
      <div class="flex text-3xl">
        <div class="mr-12">
          <span>02.</span>
          <div class="h-96 w-full border-r-2 border-dashed mt-6 border-white/20">

          </div>
        </div>

        <div>
          <div>
            <h4 class=" font-medium">Ajouter une tâche</h4>
            <p class="text-white/80 mt-6 text-base">Rendez-vous sur le projet créez, puis cliquez sur "Créer une tâche" et renseigner les informations de votre tâche. Cliquez ensuite sur créer. <br/></p>
          </div>
        </div>
      </div>
      <div class="flex text-3xl">
        <div class="mr-12">
          <span>03.</span>
          <div class="h-96 w-full border-r-2 border-dashed mt-6 border-white/20">

          </div>
        </div>

        <div>
          <div>
            <h4 class=" font-medium">Modifier une tâche</h4>
            <p class="text-white/80 mt-6 text-base flex items-center">
              Pour modifier une tâche, cliquez sur <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-background">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
            </svg>  , vous pourrez modifier le status (Finis, En Cours, Annulé), modifier les informations ou supprimer la tâche.


            </p>
          </div>
        </div>
      </div>
      <div class="flex text-3xl">
        <div class="mr-12">
          <span>04.</span>
          <div class="h-96 w-full border-r-2 border-dashed mt-6 border-white/20">

          </div>
        </div>

        <div>
          <div>
            <h4 class=" font-medium">Ajouter des membres à un projet</h4>
            <p class="text-white/80 mt-6 text-base">
              <span class="underline">Vous êtes le propriétaire du projet</span><br/>
              Cliquez sur "Ajouter un membre" (si vous n'avez pas ajouter l'utilisateur souhaiter, cliquez sur "Equipe", puis "Ajouter des amis", rechercher par Nom l'utilisateur, puis cliquez sur "Ajouter".<br/> L'utilisateur recevras une notification en haut à droite où il pourra accepter). Puis cliquez sur "Ajouter" sur l'utilisateur que vous souhaitez ajouter. Il recevra une notification en haut à droite où il pourra accepter.<br/><br/>
              <span class="underline">Vous n'êtes pas propriétaire du projet</span><br/>
              Cliquez sur vos notifications et accepter la demande du propriétaire.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>


</template>

<style scoped>
.clip-wave {
  clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 0% 80%, 0% 100%);
}
</style>