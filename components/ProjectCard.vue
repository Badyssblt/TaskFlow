<script setup >
const props = defineProps(['project']);

const { $extractId } = useNuxtApp();

const id = ref(0);

onMounted(() => {
  id.value = $extractId(props.project.id);
})

</script>

<template>
  <NuxtLink :to="`/project/${id}`">
  <div class="border border-white/20 rounded py-4 bg-background w-96 max-w-lg">
    <div class="border-b border-white/20 px-6 pb-4">
      <div class="flex justify-between gap-14">
        <h3>{{ project.name }}</h3>
        <div class="flex flex-col">
          <p class="text-white/60">Début: <span class="text-green-600">26/09/2024</span></p>
          <p class="text-white/60">Fin: <span class="text-red-600">26/09/2024</span></p>
        </div>
      </div>
      <p class="text-white/60 mt-2 truncate">{{ project.description }}</p>
      <div class="flex flex-col mt-2">
        <p class="text-white/60 text-sm">Progression</p>
        <div class="w-full bg-white/80 h-2 rounded-full relative">
          <div class="bg-green-600 rounded-full h-2 right-0 w-1/2">

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