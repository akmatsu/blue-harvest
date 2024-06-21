<script lang="ts" setup>
import { CoreLayout } from '@/Layouts';
import { Image, Tag } from '@/types';
import { UploadResultCard } from './Upload/components';

defineProps<{
  images: Image[];
  tags: Tag[];
}>();
</script>
<template>
  <CoreLayout title="Upload Results">
    <v-list>
      <div v-for="(image, index) in images" :key="image.id">
        <UploadResultCard :image="image" :tags="tags" />
        <v-divider v-if="index !== images.length - 1"></v-divider>
      </div>
    </v-list>
  </CoreLayout>
</template>
