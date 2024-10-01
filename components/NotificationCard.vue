<script setup >
const props = defineProps(['notification'])
</script>

<template>
  <div class="border border-white/10 px-4 py-2 rounded">
      <slot/>
  </div>
</template>

<style scoped>

</style>