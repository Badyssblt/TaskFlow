<script setup>
import { jwtDecode } from "jwt-decode";

import {useAuth} from "~/store/auth.js";

const { $api } = useNuxtApp();

const email = ref('');
const password = ref('');
const name = ref('');

const store = useAuth();

const loading = ref(false);



const login = async () => {
  if(email.value === '' || password.value === "" || name.value === ""){
    return;
  }
  try {
    loading.value = true;
    const response = await $api.post('/api/graphql', {
      query: `
      mutation {
        createUser(input: { email: "${email.value}", password: "${password.value}", name: "${name.value}" } ){
          user {
            email
          }
        }
      }
      `
    });
    navigateTo('/login');
  }catch (e) {
    console.log(e)
  }
  loading.value = false;
}
</script>

<template>
  <div class="w-32 h-32 bg-primary rounded-full blur-3xl absolute top-28"></div>
  <div class="w-32 h-32 bg-primary rounded-full blur-3xl absolute bottom-1/2 right-24"></div>
  <div class="flex flex-col gap-4 items-center">
    <h2 class="text-2xl font-semibold mt-6 text-center">Inscrivez vous sur TaskFlow</h2>
    <form @submit.prevent="login" class="w-full md:w-1/2 flex flex-col gap-4">
      <Input label="Votre nom" placeholder="John Doe" v-model="name"/>
      <Input label="Votre email" placeholder="example@gmail.com" v-model="email"/>
      <Input label="Votre mot de passe" placeholder="*******" type="password" v-model="password"/>
      <Button class="w-full mt-4" :loader="loading">S'inscrire</Button>
      <NuxtLink to="/login" class="flex justify-center mt-6">Ou se connecter</NuxtLink>
    </form>

  </div>

</template>

<style scoped>

</style>