<script setup>

const isShow = ref(true);

const handleResize = () => {
  isShow.value = window.innerWidth >= 768; // 768px correspond à la taille "md"
};

const toggleMenu = () => {
  isShow.value = !isShow.value;
}

onMounted(() => {
  handleResize();
  window.addEventListener('resize', handleResize);
});
</script>

<template>
  <header class="px-[5%] py-4 md:py-8">
    <div class="flex justify-between md:hidden ">
      <h1 class="font-bold text-xl">TaskFlow</h1>
      <button @click="toggleMenu">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
        </svg>
      </button>
    </div>
    <menu class="fixed w-full h-screen  bg-background z-50 top-0 left-0 md:static md:bg-transparent md:h-fit" v-show="isShow">
      <button class="absolute right-4 top-4 md:hidden" @click="toggleMenu">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
      </button>
      <div class="flex justify-center items-center gap-24 flex-col mt-8 md:flex-row md:justify-between md:mt-0">
        <h1 class="font-bold text-xl">TaskFlow</h1>
        <div class="flex gap-4">
          <button>Fonctionnalités</button>
          <button>Se connecter</button>
          <button>Commencer</button>
        </div>
        <div>
          <button>Nous contacter</button>
        </div>
      </div>

    </menu>
  </header>
</template>

<style scoped>

</style>