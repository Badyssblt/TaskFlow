<script setup >
const isShow = ref(false);


const emit = defineEmits(['onConfirm'])

const props = defineProps({
  title: {
    type: String,
    default: "Êtes-vous sur ?"
  },
  description: {
    type: String,
    default: "Etes vous sur de vouloir supprimer la tâche ?"
  },
  btnText: {
    type: String,
    default: "Supprimer la tâche"
  }
})

const onConfirm = () => {
  emit('onConfirm');
}

const toggleMenu = () => {
  isShow.value = !isShow.value;
}

defineExpose({
  toggleMenu
})
</script>

<template>
  <div class="fixed top-0 left-0 w-full h-screen bg-white/10 z-50" v-if="isShow">
  </div>
  <div class="border border-white/20 rounded absolute z-50 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-background px-14 py-8 min-w-96 max-w-4xl" v-if="isShow">
    <h2 class="text-2xl text-center font-semibold">{{ title }}</h2>
    <p class="text-white/60 text-center">{{ description }}</p>
    <div class="flex flex-col mt-10 gap-2">
      <Button class="bg-red-900" @click="onConfirm">{{ btnText }}</Button>
      <Button class="border border-white/20 rounded bg-transparent" @click="toggleMenu">Annuler</Button>
    </div>
  </div>
</template>

<style scoped>

</style>