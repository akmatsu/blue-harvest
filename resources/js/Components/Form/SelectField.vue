<script lang="ts" setup>
import {
  Listbox,
  ListboxButton,
  ListboxLabel,
  ListboxOption,
  ListboxOptions,
} from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/24/solid';
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

const input = defineModel<string | number>();
</script>

<template>
  <div class="relative mb-4">
    <Listbox v-model="input">
      <div class="flex flex-col">
        <ListboxLabel :for="`input-${name}`" class="hover:cursor-pointer">{{
          name
        }}</ListboxLabel>
        <ListboxButton class="form-input">
          <span class="block truncate">
            {{ input }}
          </span>
          <span
            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
          >
            <ChevronUpDownIcon class="size-5" />
          </span>
        </ListboxButton>
      </div>
      <transition
        leave-active-class="transition duration-100 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <ListboxOptions
          class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
        >
          <ListboxOption
            v-for="item in items"
            v-slot="{ active, selected }"
            :key="item.text"
            :value="item.value"
            as="template"
          >
            <li
              :class="[
                active ? 'bg-amber-100 text-amber-900' : 'text-gray-900',
                'relative cursor-default select-none py-2 pl-10 pr-4',
              ]"
            >
              <span
                :class="[
                  selected ? 'font-medium' : 'font-normal',
                  'block truncate',
                ]"
              >
                {{ item.text }}
              </span>
              <span
                v-if="selected"
                class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
              >
                <CheckIcon class="h-5 w-5" aria-hidden="true" />
              </span>
            </li>
          </ListboxOption>
        </ListboxOptions>
      </transition>
    </Listbox>
  </div>
</template>
