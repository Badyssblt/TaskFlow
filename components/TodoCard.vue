<script setup >
const props = defineProps(['todo']);

const { $extractId, $api } = useNuxtApp();

// Permet de re-fetch les todos
const emit = defineEmits(['deleteValidation']);

const id = ref(0);

const modalRef = ref(null);

const name = ref(props.todo.name);
const startedAt = ref(props.todo.startedAt ? props.todo.startedAt.split('T')[0] : ''); // Extracting date
const endAt = ref(props.todo.endAt ? props.todo.endAt.split('T')[0] : '');

const formatDate = (value) => {
  if (!value) return '';
  const date = new Date(value);
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
};

const openModal = () => {
  if (modalRef.value) {
    modalRef.value.toggleMenu();
  }
};

const deleteTaskConfirmation = () => {
  modalRef.value.triggerConfirmation();
}


const deleteTask = async () => {
  try {
    const response = await $api.post('/api/graphql', {
      query: `
      mutation {
          deleteTodo(input: { id: "${props.todo.id}" }){
            clientMutationId
          }
      }
      `
    });
    emit('deleteValidation')
  }catch (e) {

  }
}

const changeTodoState = async (state) => {
  try {
    const response = await $api.post('/api/graphql', {
      query:
          `
          mutation {
            updateTodo(input: { id: "${props.todo.id}", state: "${state}" }){
              todo {
                id
              }
            }
          }
          `
    });
    openModal();
    // Permet de re-fetch les todos
    emit('deleteValidation')
  }catch (e) {

  }
}

const isPastDate = (value) => {
  if (!value) return false;
  const endDate = new Date(value);
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  return endDate < today;
};

onMounted(() => {
  id.value = $extractId(props.todo.id);
})

</script>

<template>
    <div class="border border-white/20 rounded w-fit py-4 bg-background min-w-96 relative">
      <button class="absolute right-4" @click="openModal">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 tex-white-60">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
        </svg>
      </button>


      <div class="border-b border-white/20 px-6 pb-4">
          <h3>{{ todo.name }}</h3>
        <p class="text-white/60 mt-2">{{ todo.description }}</p>
        <div class="bg-amber-200 py-1 rounded-full mt-4 w-fit px-6" v-if="todo.state === 'progress'">
          <p class="text-amber-500 font-medium" >En cours</p>
        </div>
        <div class="bg-green-200 py-1 rounded-full mt-4 w-fit px-6" v-if="todo.state === 'finished'">
          <p class="text-green-800 font-medium" >Finis</p>
        </div>
        <div class="bg-red-200 py-1 rounded-full mt-4 w-fit px-6" v-if="todo.state === 'canceled'">
          <p class="text-red-800 font-medium" >Annulé</p>
        </div>
        <p class="flex text-white/60 items-center gap-4 mt-4" :class="{'text-red-600': isPastDate(todo.endAt)}">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
          {{ formatDate(todo.endAt) }}</p>
      </div>
      <div>

      </div>
    </div>

  <!--  MODALS -->
  <Modal title="Paramètre d'une tâche" ref="modalRef" @onConfirm="deleteTask">
    <form @submit.prevent="createTask" class="flex flex-col gap-6 mt-4 md:w-[600px] w-full">
      <Input label="Nom de la tâche" placeholder="Entrer un nom de tâche" v-model="name"/>
      <div class="flex justify-between gap-6">
        <Input label="Début de la tâche" placeholder="26-09-24" type="date" class="w-full" v-model="startedAt"/>
        <Input label="Fin de la tâche" placeholder="26-09-24" type="date" class="w-full" v-model="endAt"/>
      </div>
      <Button>Modifier la tâche</Button>
      <Button type="button" class="bg-lime-700/60" @click="changeTodoState('finished')">Définir comme finit</Button>
      <Button type="button" class="bg-red-900" @click="deleteTaskConfirmation">Supprimer la tâche</Button>
    </form>
  </Modal>
  <!--  ENDMODALS -->
</template>

<style scoped>

</style>