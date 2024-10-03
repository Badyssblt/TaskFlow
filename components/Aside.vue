<script setup>
const route = useRoute();

const teamLink = ref(`${route.path}/team`)

const createTeamLink = () => {
  if (!route.path.endsWith('/team')) {
    teamLink.value = `${route.path}/team`;
  } else {
    teamLink.value = route.path;
  }
};

onMounted(() => {
  createTeamLink()
})
</script>

<template>
  <aside class="flex flex-col w-fit gap-8 px-14 border py-6 border-[#12131C]">
    <button>
      Notifications
    </button>
    <button>
      Liens utiles
    </button>
    <button>
      Avancement
    </button>
    <NuxtLink :to="teamLink">
      Equipes
    </NuxtLink>
  </aside>
</template>

<style scoped>

</style>