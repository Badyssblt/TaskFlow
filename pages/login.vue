<script setup>
import { jwtDecode } from "jwt-decode";

import {useAuth} from "~/store/auth.js";

const { $api } = useNuxtApp();

const email = ref('');
const password = ref('');

const store = useAuth();

const login = async () => {
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
    console.log(e)
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <form @submit.prevent="login">
      <Input label="Votre email" placeholder="example@gmail.com" v-model="email"/>
      <Input label="Votre mot de passe" placeholder="*******" type="password" v-model="password"/>
      <Button>Se connecter</Button>
    </form>

  </div>

</template>

<style scoped>

</style>