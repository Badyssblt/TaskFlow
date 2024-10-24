<script setup>
import { jwtDecode } from "jwt-decode";

import {useAuth} from "~/store/auth.js";

const { $api } = useNuxtApp();

const email = ref('');
const password = ref('');
const name = ref('');

const store = useAuth();



const login = async () => {
  try {
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
}
</script>

<template>
  <div class="flex flex-col gap-4 items-center">
    <form @submit.prevent="login" class="w-full md:w-1/2">
      <h2 class="text-2xl font-semibold mt-6">Inscrivez vous sur TaskFlow</h2>
      <Input label="Votre nom" placeholder="John Doe" v-model="name"/>
      <Input label="Votre email" placeholder="example@gmail.com" v-model="email"/>
      <Input label="Votre mot de passe" placeholder="*******" type="password" v-model="password"/>
      <Button class="w-full mt-4">Se connecter</Button>
    </form>

  </div>

</template>

<style scoped>

</style>