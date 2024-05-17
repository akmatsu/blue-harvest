<script lang="ts" setup>
import { Image, Tag } from '@/types';
import { UploadResultCard } from '../Upload/components';
import { CoreLayout } from '@/Layouts';

defineProps<{
  images: Image[];
  tags: Tag[];
}>();
</script>

<template>
  <CoreLayout title="Edit Images">
    <v-list>
      <div v-for="(image, index) in images" :key="image.id">
        <UploadResultCard :image :tags />
        <v-divider v-if="index !== images.length - 1"></v-divider>
      </div>
    </v-list>
  </CoreLayout>
</template>
