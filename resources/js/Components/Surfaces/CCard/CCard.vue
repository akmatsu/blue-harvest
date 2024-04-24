<script lang="ts" setup>
import CCardTitle from './CCardTitle.vue';

defineProps<{
  title?: string;
}>();
</script>

<template>
  <div
    class="p-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
  >
    <slot name="title">
      <CCardTitle v-if="title">
        {{ title }}
      </CCardTitle>
    </slot>
    <slot />
  </div>
</template>
