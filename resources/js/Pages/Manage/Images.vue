<script lang="ts" setup>
import { ImageTable } from '@/Components';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { Image, Paginated } from '@/types';
import { Head } from '@inertiajs/vue3';
import { useTableViewProps } from '@/composables/useTableViewProps';

const props = defineProps<{ images: Paginated<Image> }>();

const { search, selected, page, itemsPerPage, handleSearch, handleDelete } =
  useTableViewProps(
    props.images.per_page,
    props.images.current_page,
    '/images',
  );
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
