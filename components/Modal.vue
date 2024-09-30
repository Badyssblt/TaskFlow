<script setup >
defineProps(['title']);

const isShow = ref(false);
const emit = defineEmits(['onConfirm'])

    const toggleMenu = () => {
      isShow.value = !isShow.value;
    }

const confirmationModal = ref(null);

const triggerConfirmation = () => {
  confirmationModal.value.toggleMenu();
}

const onConfirm = () => {
  emit('onConfirm');
  triggerConfirmation();
}

defineExpose({
  toggleMenu,
  triggerConfirmation
})


</script>

<template>
  <div class="fixed top-0 left-0 w-full h-screen bg-white/10 z-10" v-if="isShow">
  </div>
  <ConfirmationModal ref="confirmationModal" @onConfirm="onConfirm"/>
  <div class="border border-white/20 rounded fixed z-40 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-background px-6 py-8 min-w-96 max-w-4xl" v-if="isShow">
    <button type="button" @click="toggleMenu" class="absolute right-6">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-white/20">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
      </svg>
    </button>

    <h2 class="font-semibold text-lg">{{ title }}</h2>
    <div class="flex flex-col" >
      <slot />
    </div>
  </div>
</template>

<style scoped>

</style>