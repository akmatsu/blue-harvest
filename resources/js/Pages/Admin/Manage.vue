<script lang="ts" setup>
import { ImageTable } from '@/Components';
import { AdminLayout } from '@/Layouts';
import { Image, Paginated } from '@/types';
import { imageDelete } from '@/utils';
import { Head, router } from '@inertiajs/vue3';

const search = ref<string>();
const props = defineProps<{ images: Paginated<Image> }>();

const selected = ref<number[]>();
const itemsPerPage = ref(props.images.per_page);
const page = ref(props.images.current_page);

function handleDelete(id?: number[] | number) {
  if (id) imageDelete(id, true);
}

function handleSearch(query = search.value) {
  return router.get(
    `/admin/images`,
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
      @delete-image="handleDelete"
    />
  </AdminLayout>
</template>
