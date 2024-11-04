<script setup>

</script>

<template>
  <div role="alert" class="rounded border-s-4 border-red-500 bg-red-900/50 p-4">
    <strong class="block font-medium text-red-400"> Une erreur est survenue </strong>

    <p class="mt-2 text-sm text-red-300">
      <slot/>
    </p>
  </div>
</template>

<style scoped>

</style>