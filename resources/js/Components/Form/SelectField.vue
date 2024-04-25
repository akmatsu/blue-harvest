<script lang="ts" setup>
import { InputHTMLAttributes } from 'vue';

withDefaults(
  defineProps<{
    name?: string;
    type?: InputHTMLAttributes['type'];
    required?: boolean;
    items: { text: string; value?: string | number | null }[];
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
    <select :id="`input-${name}`" v-model="input" :name="name">
      <option v-for="item in items" :key="item.text" :value="item.value">
        {{ item.text }}
      </option>
    </select>
  </div>
</template>
