<script lang="ts" setup>
import { InputHTMLAttributes } from 'vue';

withDefaults(
  defineProps<{
    name?: string;
    type?: InputHTMLAttributes['type'];
    required?: boolean;
  }>(),
  {
    type: 'text',
  },
);

const input = defineModel();
</script>

<template>
  <div class="flex flex-col">
    <label :for="`input-${name}`" class="hover:cursor-pointer">{{
      name
    }}</label>
    <input
      :id="`input-${name}`"
      v-model="input"
      class="hover:cursor-text"
      :required="required"
      :type="type"
    />
  </div>
</template>
