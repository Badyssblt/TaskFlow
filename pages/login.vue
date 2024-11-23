<script setup>
import { jwtDecode } from "jwt-decode";

import {useAuth} from "~/store/auth.js";
import Warn from "~/components/Warn.vue";

const { $api } = useNuxtApp();

const email = ref('');
const password = ref('');

const store = useAuth();

const loading = ref(false);
const error = ref(false);

const login = async () => {
  loading.value = true;
  error.value = false;
  try {
    const response = await $api.post('/api/login_check', {
      username: email.value,
      password: password.value
    });
    const decoded = jwtDecode(response.data.token);
    store.token = response.data.token
    store.user = {
      email: email.value,
      id: decoded.id
    }
    navigateTo('/dashboard');
  }catch (e) {
    if(e.status === 400){
      error.value = "Veuillez remplir tous les champs"
    }
    if(e.status === 401){
      error.value = "Mots de passes incorrect...";
    }
  }
  loading.value = false;
}
</script>

<template>
  <div class="w-32 h-32 bg-primary rounded-full blur-3xl absolute top-28"></div>
  <div class="w-32 h-32 bg-primary rounded-full blur-3xl absolute bottom-1/2 right-24"></div>
  <div class="flex mx-[5%] border border-white/20 rounded-md gap-14">
    <div class="wavy w-[30%] h-auto rounded-md">
      <div class="mt-8">
        <h2 class="font-bold text-2xl text-center">Bon retour !</h2>
        <p class="text-white/80 text-center">Reprenez vos projets là où vous les avez laissés.</p>
      </div>
    </div>
    <div class="flex flex-col gap-4 m-auto w-96 flex-1 my-8  py-24">
      <div>
        <h2 class="text-2xl font-semibold w-fit">Connectez vous sur Taskflow</h2>
        <p class="text-white/60 text-sm mt-2">Connectez-vous pour suivre vos tâches et atteindre vos objectifs.</p>
      </div>

      <form @submit.prevent="login" class="w-full  md:w-1/2 flex flex-col gap-4">
        <Input label="Votre email" placeholder="example@gmail.com" v-model="email"/>
        <Input label="Votre mot de passe" placeholder="*******" type="password" v-model="password"/>
        <Button class="mt-4 w-full" :loader="loading">Se connecter</Button>
        <p class="text-center">Pas encore de compte ? <NuxtLink to="/register" class="mt-6 text-primary font-bold hover:brightness-150 transition-all">S'inscrire</NuxtLink></p>
        <Warn v-if="error" class="mt-6">
          {{ error }}
        </Warn>
      </form>

    </div>
  </div>


</template>

<style scoped>
.wavy {
  background-image: radial-gradient(circle at 85% 1%, hsla(190,0%,93%,0.05) 0%, hsla(190,0%,93%,0.05) 96%,transparent 96%, transparent 100%),radial-gradient(circle at 14% 15%, hsla(190,0%,93%,0.05) 0%, hsla(190,0%,93%,0.05) 1%,transparent 1%, transparent 100%),radial-gradient(circle at 60% 90%, hsla(190,0%,93%,0.05) 0%, hsla(190,0%,93%,0.05) 20%,transparent 20%, transparent 100%),radial-gradient(circle at 79% 7%, hsla(190,0%,93%,0.05) 0%, hsla(190,0%,93%,0.05) 78%,transparent 78%, transparent 100%),radial-gradient(circle at 55% 65%, hsla(190,0%,93%,0.05) 0%, hsla(190,0%,93%,0.05) 52%,transparent 52%, transparent 100%),linear-gradient(135deg, rgb(49,94,171),rgb(49,94,171));
}
</style>