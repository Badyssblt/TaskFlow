<script setup>
const { $api } = useNuxtApp();

const name = ref('');
const description = ref('');
const priority = ref('');
const startedAt = ref('');
const endAt = ref('');
const budget = ref(0);

const allProjects = ref([]);
const projects = ref([]);

const createProjectModal = ref(null);

const createProject = async () => {
  console.log(parseInt(priority.value));
  try {
    const response = await $api.post('/api/graphql', {
      query: `
        mutation {
          createProject(input: { name: "${name.value}", description: "${description.value}", priority: ${parseInt(priority.value)}, started_at: "${startedAt.value}", end_at: "${endAt.value}", budget: ${parseFloat(budget.value)}, visibility: "public", state: ACTIVE, startedAt: "${startedAt.value}", endAt: "${endAt.value}" }) {
            project {
              name
            }
          }
        }
        `
    });
    await getProjects();
    openModal();
  } catch (e) {
    console.log(e);
  }
};

const getProjects = async () => {
  try {
    const response = await $api.post("/api/graphql", {
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
    allProjects.value = response.data.data.projects.edges;
    projects.value = allProjects.value;
  } catch (e) {
    console.log(e);
  }
};

const openModal = () => {
  createProjectModal.value.toggleMenu();
};

const searchProjects = () => {
  if (!searchQuery.value) {
    projects.value = allProjects.value;
  } else {
    projects.value = allProjects.value.filter(project =>
        project.node.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }
};

const searchQuery = ref('');

onMounted(() => {
  getProjects();
});
</script>


<template>
  <div class="flex min-h-screen">
    <div class="w-full bg-[#12131C] px-24 pt-6">

      <div class="border border-white/20 rounded py-8 min-h-screen">
        <div class="flex justify-between border-b border-white/20 px-14 pb-8">
          <div>
            <h2 class="text-2xl font-semibold">Tous mes projets</h2>
            <p class="text-sm text-white/60 mt-2">Créer et accéder à vos projets</p>
          </div>
          <div class="flex flex-col gap-6 items-end">
            <Button class="flex items-center gap-4 w-fit" @click="openModal">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
              Créer un projet</Button>
            <form class="w-96" @submit.prevent="searchProjects">
              <Input v-model="searchQuery" @input="searchProjects" placeholder="Rechercher un projet"/>
            </form>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 place-items-center mt-6 px-12">
          <ProjectCard v-for="project in projects" :project="project.node" :key="project.node.id" />
        </div>

      </div>

    </div>
  </div>
  <Modal ref="createProjectModal" title="Créer un nouveau projet">
    <form @submit.prevent="createProject">
      <Input label="Nom du projet" placeholder="Mon projet" class="mt-4" v-model="name"/>
      <label for="description" class="flex flex-col mt-4">
        Description du projet
        <textarea id="description" class="bg-transparent border border-white/20 rounded p-4" v-model="description">

      </textarea>
      </label>
      <label for="priority" class="flex flex-col mt-4 ">
        Priorité
        <select name="priority" id="priority" class="text-white bg-transparent border border-white/20 focus:outline-none px-4 py-2 appearance-none rounded" v-model="priority">
          <option value="nothing" class="bg-background">Sélectionner une priorité</option>
          <option value="0" class="bg-background">Normal</option>
          <option value="1" class="bg-background">Haute</option>
        </select>
      </label>
      <Input type="date" label="Début du projet" class="mt-4" v-model="startedAt"/>
      <Input type="date" label="Fin du projet" class="mt-4" v-model="endAt"/>
      <Input type="number" label="Budget" class="mt-4" v-model="budget"/>
      <Button class="w-full mt-4">Créer le projet</Button>
    </form>


  </Modal>
</template>

<style scoped>

</style>