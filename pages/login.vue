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
  <div class="flex flex-col gap-4 items-center">
    <h2 class="text-2xl font-semibold mt-6">Connectez vous sur Taskflow</h2>
    <form @submit.prevent="login" class="w-full  md:w-1/2 flex flex-col gap-4">
      <Input label="Votre email" placeholder="example@gmail.com" v-model="email"/>
      <Input label="Votre mot de passe" placeholder="*******" type="password" v-model="password"/>
      <Button class="mt-4 w-full" :loader="loading">Se connecter</Button>
      <NuxtLink to="/register" class="flex justify-center mt-6">Ou s'inscrire</NuxtLink>
      <Warn v-if="error" class="mt-6">
        {{ error }}
      </Warn>
    </form>

  </div>

</template>

<style scoped>

</style>