<script lang="ts" setup>
import { useRequest } from '@/composables';
import { PopularSearches } from '@/types';
import axios from 'axios';

defineEmits<{
  (e: 'enter'): void;
}>();

const search = defineModel<string>();

const suggestions = ref<string[]>([]);

async function fetchSearchSuggestions() {
  return axios.get<PopularSearches[]>('/popular-searches', {
    params: { query: search.value },
  });
}

const { loading, exec } = useRequest(fetchSearchSuggestions, {
  silent: true,
  onSuccess(res) {
    suggestions.value = res.data.map((item) => item.document.query);
  },
});

onMounted(async () => {
  const params = new URLSearchParams(window.location.search);
  const query = params.get('query');
  if (query) search.value = query;
  await exec();
});
</script>

<template>
  <v-combobox
    v-model="search"
    :items="suggestions"
    :loading="loading"
    prepend-inner-icon="mdi-magnify"
    placeholder="Search..."
    hide-details
    rounded="xl"
    variant="solo-filled"
    flat
    @input="exec"
    @keydown.enter.prevent="$emit('enter')"
  >
    <template #item="{ props, item }">
      <v-list-item v-bind="props" :title="item.title" @click="$emit('enter')" />
    </template>
  </v-combobox>
</template>
