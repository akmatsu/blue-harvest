<script lang="ts" setup>
import { Head, Link } from '@inertiajs/vue3';
import { Image } from '@/types';
import CoreLayout from '@/Layouts/CoreLayout.vue';

defineProps<{ images: Image[] }>();
</script>

<template>
  <Head title="Browse" />
  <CoreLayout>
    <div class="masonry-layout">
      <v-card
        v-for="image in images"
        :key="image.id"
        :href="route('image-view', { id: image.id })"
        class="mb-4"
        @click.prevent.stop="
          $inertia.get(route('image-view', { id: image.id }))
        "
      >
        <v-img :src="image.url" width="100%"></v-img>
      </v-card>
    </div>
  </CoreLayout>
</template>

<style lang="scss" scoped>
.masonry-layout {
  column-count: 1;
  gap: 1rem;
  @media screen and (min-width: 500px) {
    column-count: 2;
  }

  @media screen and (min-width: 1000px) {
    column-count: 3;
  }
}
</style>
