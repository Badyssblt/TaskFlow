<script setup>
const props = defineProps(['team'])
const emit = defineEmits(['deleteTeam']);

const deleteTeam = () => {
  emit('deleteTeam', props.team.id);
};

</script>

<template>
  <div class="border border-white/20 px-10 py-6 rounded flex gap-6 relative">
    <button class="absolute right-2 top-2"  @click="deleteTeam">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
      </svg>
    </button>
    <div class="w-20 h-20 overflow-hidden rounded-full">
      <img src="https://i.pinimg.com/564x/24/22/2e/24222e52d37fdbfc2993517e5885cb26.jpg" alt="" class="w-full h-full object-cover">
    </div>
    <div>
      <p>{{ team.user.email }}</p>
      <p>Développeur Web</p>
      <div>
        <p>29 Septembre 2024</p>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>