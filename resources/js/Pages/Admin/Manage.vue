<script lang="ts" setup>
import { ImageTable } from '@/Components';
import { AdminLayout } from '@/Layouts';
import { Image, Paginated } from '@/types';
import { imageDelete } from '@/utils';
import { Head, router } from '@inertiajs/vue3';

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
    '/admin/images',
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
  <Head title="Admin Manage Images" />
  <AdminLayout v-model="search" searchable fluid @search-submit="handleSearch">
    <ImageTable
      v-model="selected"
      v-model:page="page"
      v-model:items-per-page="itemsPerPage"
      :images="props.images.data"
      :items-length="images.total"
      to="admin.images.show"
      @search="handleSearch"
      @delete-image="handleDelete($event, true)"
    />
  </AdminLayout>
</template>
