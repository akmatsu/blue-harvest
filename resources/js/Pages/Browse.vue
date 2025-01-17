<script lang="ts" setup>
import { CorePagination, ImageCard, MasonryGrid } from '@/Components';
import { useRequest } from '@/composables';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { useToasts } from '@/store/toasts';
import { Image, Paginated } from '@/types';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps<{ images: Paginated<Image> }>();
const scrollImages = ref(props.images.data);
const toast = useToasts();
const search = ref<string>();

async function handleSearch() {
  return router.get('/', { query: search.value }, { only: ['images'] });
}

const { exec: execSearch } = useRequest(handleSearch, {
  onError: (err) =>
    err.message
      ? toast.error(err.message)
      : toast.error('Something went wrong.'),
});
</script>

<template>
  <Head title="Browse" />
  <CoreLayout v-model="search" searchable @search-submit="execSearch">
    <MasonryGrid class="mb-4">
      <ImageCard v-for="image in scrollImages" :key="image.id" :image="image" />
    </MasonryGrid>
    <div
      v-if="scrollImages?.length === 0"
      class="text-center w-full text-gray-500"
    >
      <p>No Images found.</p>
    </div>
    <div class="d-flex justify-center">
      <CorePagination :pagination="images" :search="search" />
    </div>
  </CoreLayout>
</template>
