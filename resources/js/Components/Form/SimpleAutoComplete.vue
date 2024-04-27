<script lang="ts" setup>
import {
  Combobox,
  ComboboxInput,
  ComboboxLabel,
  ComboboxOption,
  ComboboxOptions,
} from '@headlessui/vue';
import { computed, ref } from 'vue';

const props = defineProps<{
  items: string[] | { text: string; value: string | number }[];
  label?: string;
  multiple?: boolean;
}>();

const input = defineModel<string | number | boolean>();
const query = ref();

const filteredOptions = computed(() =>
  query.value === ''
    ? props.items
    : props.items.filter((item) => {
        return typeof item === 'string'
          ? item.toLowerCase().includes(query.value.toLowerCase())
          : item.text.toLocaleLowerCase().includes(query.value.toLowerCase());
      }),
);

function getItemValue(item: string | { text: string; value: string | number }) {
  return typeof item === 'string' ? item : item.value;
}

function getItemText(item: string | { text: string; value: string | number }) {
  return typeof item === 'string' ? item : item.text;
}
</script>

<template>
  <Combobox v-model="input">
    <div class="flex flex-col">
      <ComboboxLabel v-if="label">{{ label }}</ComboboxLabel>
      <ComboboxInput
        class="form-select"
        @change="query = $event.target.value"
      />
    </div>
    <ComboboxOptions
      class="absolute mt-1 max-h-60 max-w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
    >
      <ComboboxOption
        v-for="item in filteredOptions"
        :key="getItemValue(item)"
        :value="getItemValue(item)"
      >
        {{ getItemText(item) }}
      </ComboboxOption>
    </ComboboxOptions>
  </Combobox>
</template>
