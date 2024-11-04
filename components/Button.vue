<script setup>
  defineProps({
    loader: {
      type: Boolean,
      default: false
    }
  })
</script>

<template>
  <button class="bg-primary px-8 py-2 rounded border-transparent hover:bg-transparent hover:border-white/20 border transition-all flex justify-center items-center gap-4">
    <slot/>
    <Loader v-show="loader"/>
  </button>
</template>

<style scoped>

</style>