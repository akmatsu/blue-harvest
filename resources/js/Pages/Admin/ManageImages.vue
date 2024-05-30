<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3';
import { AdminLayout, CoreLayout } from '@/Layouts';
import { Image, Paginated } from '@/types';
import { ImageTable } from '@/Components';
import { imageDelete } from '@/utils';

const search = ref<string>();
const props = defineProps<{ images: Paginated<Image> }>();

const selected = ref<number[]>();
const itemsPerPage = ref(props.images.per_page);
const page = ref(props.images.current_page);

function editImages(ids = selected.value) {
  router.get('/admin/images/edit', {
    ids: ids,
  });
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
      to="admin.images.manage"
      @search="handleSearch"
      @edit-images="editImages"
      @delete-image="imageDelete"
    />
  </AdminLayout>
</template>
