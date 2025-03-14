<script setup>
defineProps({
  custom: Boolean,
  center: Boolean,
});
</script>

<template>
  <section
    class="lg:px-0 lg:mx-auto"
    :class="{ 'text-center': center}"
  >
    <slot v-if="custom" />
    <h1 v-else class="text-2xl text-gray-500 dark:text-slate-400">
      <slot />
    </h1>
  </section>
</template>
