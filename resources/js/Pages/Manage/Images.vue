<script lang="ts" setup>
import { ImageTable } from '@/Components';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { Image, Paginated } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { imageDelete } from '@/utils';

const props = defineProps<{ images: Paginated<Image> }>();

const selected = ref<number[]>([]);

const itemsPerPage = ref(props.images.per_page);
const page = ref(props.images.current_page);

const search = ref<string>();

function handleDelete(id?: number | number[], admin?: boolean) {
  if (id) imageDelete(id, admin);
}

function handleSearch(query = search.value) {
  return router.get(
    '/images',
    {
      query,
      count: itemsPerPage.value,
      ...(!query && { page: page.value }),
    },
    { only: ['images'] },
  );
}
</script>

<template>
  <Head title="Manage Your Images" />
  <CoreLayout v-model="search" searchable fluid @search-submit="handleSearch">
    <v-card>
      <ImageTable
        v-model="selected"
        v-model:page="page"
        v-model:items-per-page="itemsPerPage"
        :images="images.data"
        :items-length="images.total"
        to="images.manageImage"
        @search="handleSearch"
        @delete-image="handleDelete"
      />
    </v-card>
  </CoreLayout>
</template>
