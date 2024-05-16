<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3';
import { Image, Paginated } from '@/types';
import { formatDate, imageDelete } from '@/utils';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { useRequest } from '@/composables/useRequest';
import { useToasts } from '@/store/toasts';

const props = defineProps<{ images: Paginated<Image> }>();
const toast = useToasts();

const selected = ref<number[]>([]);
const { exec, loading } = useRequest(imageDelete, {
  onSuccess: () => toast.success('Items successfully deleted.'),
});

const itemsPerPage = ref(props.images.per_page);
const page = ref(props.images.current_page);

const search = ref<string>();

function editImages(ids = selected.value) {
  router.get('/upload/results', {
    ids: ids,
  });
}

function handleSearch(query = search.value) {
  return router.get(
    `/images`,
    {
      query,
      count: itemsPerPage.value,
      ...(!query && { page: page.value }),
    },
    { only: ['images'] },
  );
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
    title: 'Tags',
    key: 'tags',
  },
  {
    title: 'Actions',
    key: 'url',
  },
];
</script>

<template>
  <Head title="Manage Images" />
  <CoreLayout v-model="search" searchable fluid @search-submit="handleSearch">
    <v-card>
      <v-data-table-server
        v-model="selected"
        v-model:items-per-page="itemsPerPage"
        v-model:page="page"
        :items="images.data"
        :items-length="images.total"
        :headers="headers"
        item-value="id"
        show-select
        @update:page="handleSearch()"
        @update:items-per-page="handleSearch()"
      >
        <template #top>
          <v-toolbar>
            <v-spacer></v-spacer>
            <v-btn color="primary" prepend-icon="$edit" @click="editImages()">
              Edit Images
            </v-btn>
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
          <v-btn v-bind="props" color="primary" @click="editImages([item.id])">
            View Image
          </v-btn>
        </template>
        <template #item.tags="{ item }">
          <v-chip-group>
            <v-chip
              v-for="tag in item.tags"
              density="compact"
              :href="`/images?query=${tag.name}`"
              @click.prevent.stop="handleSearch(tag.name)"
            >
              {{ tag.name }}
            </v-chip>
          </v-chip-group>
        </template>
      </v-data-table-server>
    </v-card>
  </CoreLayout>
</template>
