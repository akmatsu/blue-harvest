<script lang="ts" setup>
import { LinkBtn } from '@/Components';
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
    <v-alert icon="mdi-information" color="success">
      You're images were successfully uploaded and are being processed. You will
      receive a notification when they are finished processing.
      <template #append>
        <div class="d-flex flex-column" style="gap: 4px">
          <LinkBtn link="upload.index"> Upload more images </LinkBtn>
          <LinkBtn link="index" color="primary">Back to app</LinkBtn>
        </div>
      </template>
    </v-alert>
    <v-list>
      <div v-for="(image, index) in images" :key="image.id">
        <UploadResultCard :image="image" :tags="tags" />
        <v-divider v-if="index !== images.length - 1"></v-divider>
      </div>
    </v-list>
  </CoreLayout>
</template>
