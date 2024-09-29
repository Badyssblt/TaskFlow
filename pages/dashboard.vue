<script setup>

  const { $api } = useNuxtApp();

  const projects = ref([]);

  const getProjects = async () => {
    try {
      const response = await $api.post("http://localhost:8215/api/graphql", {
        query: `
          {
            projects {
              edges {
                node {
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
            }
          }
        `
      });
      projects.value = response.data.data.projects.edges;
    } catch (e) {
      console.log(e);
    }
  };

  onMounted(() => {
    getProjects();
  })
</script>

<template>
  <div class="flex gap-4 min-h-screen">
    <Aside/>
    <div class="w-full bg-[#12131C] px-24 pt-6">

      <div class="border border-white/20 rounded py-8 min-h-screen">
        <div class="flex justify-between border-b border-white/20 px-14 pb-8">
          <div>
            <h2 class="text-2xl font-semibold">Tous mes projets</h2>
            <p class="text-sm text-white/60 mt-2">Créer et accéder à vos projets</p>
          </div>
          <div class="flex flex-col gap-6 items-end">
            <Button class="flex items-center gap-4 w-fit">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
              Créer un projet</Button>
            <form class="w-96">
              <Input placeholder="Rechercher un projet"/>
            </form>
          </div>
        </div>
        <div class="flex flex-wrap gap-4 mt-12 px-14 justify-center ">
          <ProjectCard v-for="project in projects" :project="project.node"/>
        </div>
      </div>

    </div>
  </div>

</template>

<style scoped>

</style>