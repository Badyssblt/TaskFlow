<script setup >
const props = defineProps(['project']);

const { $extractId } = useNuxtApp();

const id = ref(0);

const todos = ref(props.project.todos.edges);
const totalTodos = ref(props.project.todos.totalCount);
const maxProgress = ref(0);
const currentProgress = ref(0);


const calculateProgress = () => {
  const finishedTodos = todos.value.filter((todo) => todo.node.state === 'finished');
  const progressTodos = todos.value.filter((todo) => todo.node.state === 'progress');

  maxProgress.value = totalTodos.value;
  currentProgress.value = finishedTodos.length + progressTodos.length;

  return (finishedTodos.length / maxProgress.value) * 100;

}

const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
};

onMounted(() => {
  id.value = $extractId(props.project.id);
  calculateProgress()
})

</script>

<template>
  <NuxtLink :to="`/project/${id}`">
  <div class="border border-white/20 rounded py-4 bg-background  w-72 md:w-96 max-w-lg">
    <div class="border-b border-white/20 px-6 pb-4">
      <div class="flex justify-between gap-14">
        <h3>{{ project.name }}</h3>
        <div class="flex flex-col">
          <p class="text-white/60">Début: <span class="text-green-600">{{ formatDate(project.started_at) }}</span></p>
          <p class="text-white/60">Fin: <span class="text-red-600">{{ formatDate(project.end_at) }}</span></p>
        </div>
      </div>
      <p class="text-white/60 mt-2 truncate">{{ project.description }}</p>
      <div class="flex flex-col mt-2">
        <p class="text-white/60 text-sm">Progression</p>
        <div class="w-full bg-white/80 h-2 rounded-full relative">
          <div class="bg-green-600 rounded-full h-2 right-0" :style="{ width: `${calculateProgress()}%` }">

          </div>
        </div>
      </div>
      <div class="bg-amber-200 py-1 rounded-full mt-4 w-fit px-6">
        <p class="text-amber-500 font-medium">En cours</p>
      </div>
    </div>
    <div>

    </div>
  </div>
  </NuxtLink>
</template>

<style scoped>

</style>