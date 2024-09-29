<script setup>

const route = useRoute();

const id = ref(0);

const { $api } = useNuxtApp();

const project = ref({});

const router = useRouter();

const goBack = () => {
  if (window.history.length > 1) {
    router.back();
  } else {
    router.push('/');
  }
};

const getProject = async () => {
  try {
    const response = await $api.post("http://localhost:8215/api/graphql", {
      query: `
          {
            project(id: "/api/projects/${id.value}"){
              id
                  name
                  description
                  priority
                  started_at
                  end_at
                  budget
                  visibility
                  state
            }
          }
        `});
   project.value = response.data.data.project;
  }catch (e) {
    console.log(e)
  }
}
onMounted(() => {
  id.value = route.params.id;
  getProject();
})
</script>

<template>
  <div class="flex gap-4 min-h-screen">
    <Aside/>
    <div class="w-full bg-[#12131C] px-24 pt-6">
      <button @click="goBack" class="text-white/60 text-sm flex items-center gap-1 hover:text-white transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
        Retour aux projets</button>
      <div class="mt-12">

        <div class="border-b border-white/20 pb-12">
          <h2 class="text-2xl font-semibold">{{ project.name }}</h2>
          <p class="text-white/60 text-sm my-4">{{ project.description }}</p>
          <div>
            <div>

            </div>
            <button  class="flex items-center gap-4 border border-primary px-4 py-2 rounded bg-background">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-primary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
              </svg>
              Ajouter un membre
            </button>
          </div>
        </div>

        <div class="mt-12">
          <div class="flex items-center justify-between w-96">
            <h3>Tâches</h3>
            <button class="flex items-center gap-4 border border-primary px-4 py-2 rounded bg-background">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-primary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
              Créer une tâche
            </button>
          </div>
          <div class="flex gap-4 bg-background px-2 py-2 border border-white/20 rounded w-fit mt-2">
            <button class="bg-white/10 px-8 py-2 rounded">
              En cours
            </button>
            <button class=" px-8 py-2 rounded">
              Finis
            </button>
            <button class=" px-8 py-2 rounded">
             Annulé
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<style scoped>

</style>