<script lang="ts" setup>
import { ImageTable } from '@/Components';
import { useTableViewProps } from '@/composables/useTableViewProps';
import { AdminLayout } from '@/Layouts';
import { Image, Paginated } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{ images: Paginated<Image> }>();

const { search, selected, page, itemsPerPage, handleSearch, handleDelete } =
  useTableViewProps(
    props.images.per_page,
    props.images.current_page,
    '/admin/images',
  );
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
