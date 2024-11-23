<template>
  <NuxtLayout name="404">
    <div>
      <h2 class="text-center font-bold">404</h2>
      <div class="text-4xl">Une erreur est survenue</div>
      <button class="font-bold button" @click="goBack">Back</button>
    </div>
  </NuxtLayout>
</template>

<script setup>


const goBack = () => {
  navigateTo("/")
}
</script>

<style scoped>
.button {
  padding: 4px 6px;
  margin: 10px 0px;
  background: black;
  color: white;
}
</style>