<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3';
import { Image, Paginated } from '@/types';
import { formatDate, imageDelete } from '@/utils';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { useRequest } from '@/composables/useRequest';
import { useToasts } from '@/store/toasts';

defineProps<{ images: Paginated<Image> }>();
const toast = useToasts();

const selected = ref<number[]>([]);
const { exec, loading } = useRequest(imageDelete, {
  onSuccess: () => toast.success('Items successfully deleted.'),
});

const search = ref<string>();

function handleSearch() {
  return router.get(`/images?query=${search.value}`, {}, { only: ['images'] });
}

const headers = [
  {
    title: 'ID',
    key: 'id',
  },
  {
    title: 'TYPE',
    key: 'mime_type',
  },
  {
    title: 'Name',
    key: 'name',
  },
  {
    title: 'Created',
    key: 'created_at',
  },
  {
    title: 'Updated',
    key: 'updated_at',
  },
  {
    title: 'Actions',
    key: 'url',
  },
];
</script>

<template>
  <Head title="Manage Images" />
  <CoreLayout v-model="search" searchable @search-submit="handleSearch">
    <v-card>
      <v-data-table
        v-model="selected"
        :items="images.data"
        :headers="headers"
        item-value="id"
        show-select
      >
        <template #top>
          <v-toolbar>
            <v-spacer></v-spacer>
            <v-dialog max-width="400px">
              <template #activator="{ props }">
                <v-btn
                  prepend-icon="mdi-delete"
                  v-bind="props"
                  color="error"
                  :disabled="!selected.length"
                  :loading="loading"
                >
                  Delete
                </v-btn>
              </template>
              <template #default="{ isActive }">
                <v-card title="Delete Images">
                  <v-card-text>
                    <p>
                      Are you sure you want to delete the selected images? This
                      is not reversible.
                    </p>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="isActive.value = false">Cancel</v-btn>
                    <v-btn variant="flat" color="error" @click="exec(selected)">
                      Delete
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </template>
            </v-dialog>
          </v-toolbar>
        </template>
        <template #item.created_at="{ item }">
          {{ formatDate(item.created_at) }}
        </template>
        <template #item.updated_at="{ item }">
          {{ formatDate(item.updated_at) }}
        </template>
        <template #item.url="{ item }">
          <v-dialog max-width="800">
            <template #activator="{ props }">
              <v-btn v-bind="props" color="primary">View Image</v-btn>
            </template>
            <v-card :title="item.name">
              <v-card-text>
                <v-img :src="item.url"></v-img>
                <div class="d-flex">
                  <p class="text-caption">
                    Dimensions: {{ item.width }} x {{ item.height }}
                  </p>
                  <v-spacer></v-spacer>
                  <p class="text-caption">Size: {{ item.size }} Bytes</p>
                </div>
              </v-card-text>
            </v-card>
          </v-dialog>
        </template>
      </v-data-table>
    </v-card>
  </CoreLayout>
</template>
