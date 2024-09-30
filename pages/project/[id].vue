<script setup>

const router = useRouter();
const route = useRoute();

const id = ref(0);

const name = ref('');
const startedAt = ref();
const endAt = ref();


const { $api } = useNuxtApp();

const project = ref({});
const todos = ref([]);

const modalRef = ref(null);

const currentFilter = ref('progress');

const openModal = () => {
  if (modalRef.value) {
    modalRef.value.toggleMenu();
  }
};


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
                  state,
                  todos {
                    edges {
                      node {
                      id,
                      name,
                      startedAt,
                      endAt,
                      state
                      }
                    }
                  }
            }
          }
        `});
   project.value = response.data.data.project;
   todos.value = project.value.todos.edges;
  }catch (e) {
    console.log(e)
  }
}

const changeFilter = (filter) => {
  currentFilter.value = filter;
};

const filteredTodos = computed(() => {
  if (currentFilter.value === 'progress') {
    return todos.value.filter(todo => todo.node.state === 'progress');
  } else if (currentFilter.value === 'finished') {
    return todos.value.filter(todo => todo.node.state === 'finished');
  } else if (currentFilter.value === 'canceled') {
    return todos.value.filter(todo => todo.node.state === 'canceled');
  }
  return todos.value;
});



const createTask = async () => {
  try {
    const response = await $api.post('/api/graphql', {
      query: `
      mutation {
        createTodo(input: { name: "${name.value}", started_at: "${startedAt.value}", end_at: "${endAt.value}", startedAt: "${startedAt.value}", endAt: "${endAt.value}", project: "${project.value.id}", state: "progress" }){
          todo {
            id,
            name,
            startedAt,
            endAt,
            state
          }
        }
      }
      `
    });
    openModal();
    await getProject();
  }catch (e) {

  }
}



onMounted(async () => {
  id.value = route.params.id;
  await getProject();
  console.log(filteredTodos)
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
            <button class="flex items-center gap-4 border border-primary px-4 py-2 rounded bg-background" @click="openModal">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-primary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
              Créer une tâche
            </button>
          </div>
          <div class="flex gap-4 bg-background px-2 py-2 border border-white/20 rounded w-fit mt-2">
            <button class=" px-8 py-2 rounded" :class="currentFilter === 'progress' ? 'bg-white/10' : ''" @click="changeFilter('progress')">
              En cours
            </button>
            <button class=" px-8 py-2 rounded" :class="currentFilter === 'finished' ? 'bg-white/10' : ''" @click="changeFilter('finished')">
              Finis
            </button>
            <button class=" px-8 py-2 rounded" :class="currentFilter === 'canceled' ? 'bg-white/10' : ''" @click="changeFilter('canceled')">
             Annulé
            </button>
          </div>
        </div>
        <div class="flex flex-wrap mt-12 gap-4" v-if="filteredTodos.length !== 0">
          <TodoCard
              v-for="todo in filteredTodos"
              :todo="todo.node"
              :key="todo.node.id"
              @deleteValidation="getProject"
          />
        </div>
        <div v-else>
          <p class="text-white/60 mt-4">Vous n'avez aucune tâche...</p>
        </div>
      </div>
    </div>

  </div>

  <!--  MODALS -->
  <Modal title="Créer une tâche" ref="modalRef">
    <form @submit.prevent="createTask" class="flex flex-col gap-6 mt-4 md:w-[600px] w-full">
      <Input label="Nom de la tâche" placeholder="Entrer un nom de tâche" v-model="name"/>
      <div class="flex justify-between gap-6">
        <Input label="Début de la tâche" placeholder="26-09-24" type="date" class="w-full" v-model="startedAt"/>
        <Input label="Fin de la tâche" placeholder="26-09-24" type="date" class="w-full" v-model="endAt"/>
      </div>
      <Button >Créer la tâche</Button>
      <!--              <Button>Modifier la tâche</Button>-->
      <!--              <Button class="bg-lime-700/60">Définir comme finit</Button>-->
      <!--              <Button type="button" class="bg-red-900" @click="deleteTaskConfirmation">Supprimer la tâche</Button>-->
    </form>
  </Modal>
  <!--  ENDMODALS -->

</template>

<style scoped>

</style>