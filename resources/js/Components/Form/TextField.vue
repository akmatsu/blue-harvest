<script lang="ts" setup>
import { InputHTMLAttributes } from 'vue';

withDefaults(
  defineProps<{
    name?: string;
    type?: InputHTMLAttributes['type'];
    required?: boolean;
    min?: InputHTMLAttributes['min'];
    max?: InputHTMLAttributes['max'];
    rules?: ((input?: string | number) => string | true)[];
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
      class="form-input"
      :required="required"
      :type="type"
      :min="min"
      :max="max"
    />
  </div>
</template>
