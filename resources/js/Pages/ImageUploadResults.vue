<script lang="ts" setup>
import { Image, Tag } from '@/types';
import { CoreLayout } from '@/Layouts';
import { UploadResultCard } from './Upload/components';

defineProps<{
  images: Image[];
  tags: Tag[];
}>();
</script>
<template>
  <CoreLayout title="Upload Results">
    <div class="d-flex flex-column align-center">
      <UploadResultCard
        v-for="image in images"
        :key="image.id"
        :image="image"
        :tags="tags"
        width="600"
        max-width="100%"
      />
    </div>
  </CoreLayout>
</template>
