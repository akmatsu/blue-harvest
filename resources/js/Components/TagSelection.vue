<script lang="ts" setup>
import { useRequest } from '@/composables';
import { useToasts } from '@/store/toasts';
import { Tag } from '@/types';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const selectedTags = defineModel<string[]>({
  default: [],
});
const toast = useToasts();
const items = defineModel<Tag[]>('items');
const form = useForm<{ name?: string }>({
  name: undefined,
});

async function fetchTags() {
  return axios.get<Tag[]>('/tags', {
    params: { query: form.name },
  });
}

const { exec, loading } = useRequest(fetchTags, {
  onSuccess(res) {
    items.value = res.data;
  },
});

function createTag() {
  form.post('/tags', {
    onSuccess() {
      selectedTags.value.push(form.name!);
      form.reset();
    },
    onError(errors) {
      toast.error(errors.name);
    },
  });
}

let timeout: number;
function handleSearch(value: string) {
  loading.value = true;
  form.name = value;
  if (timeout) clearTimeout(timeout);
  timeout = setTimeout(async () => {
    await exec();
  }, 500);
}

onBeforeUnmount(() => {
  clearTimeout(timeout);
});
</script>

<template>
  <v-autocomplete
    :items
    :search="form.name"
    @update:search="handleSearch"
    chips
    multiple
    :no-data-text="
      form.name ? 'No matching tags found' : 'Start typing to search tags'
    "
    label="Tags"
    v-model="selectedTags"
    item-value="name"
    item-title="name"
    :loading="loading || form.processing"
  >
    <template #append-inner>
      <v-fade-transition>
        <v-btn
          color="primary"
          v-if="form.name"
          size="compact"
          prepend-icon="mdi-plus"
          text="Create Tag"
          class="text-caption"
          :loading="form.processing"
          @click="createTag"
        ></v-btn>
      </v-fade-transition>
    </template>
    <template #no-data>
      <p v-if="!loading">No Matching tags found.</p>
      <v-progress-circular
        v-else
        indeterminate
        color="primary"
        size="16"
        width="2"
      />
    </template>
  </v-autocomplete>
</template>
