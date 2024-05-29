<script lang="ts" setup>
import { Image, Tag } from '@/types';
import { UploadResultCard } from '../Upload/components';
import { AdminLayout } from '@/Layouts';
import { Head } from '@inertiajs/vue3';

defineProps<{
  images: Image[];
  tags: Tag[];
}>();
</script>

<template>
  <Head title="Edit Images - Admin" />
  <AdminLayout>
    <v-list>
      <div v-for="(image, index) in images" :key="image.id">
        <UploadResultCard :image :tags />
        <v-divider v-if="index !== images.length - 1"></v-divider>
      </div>
    </v-list>
  </AdminLayout>
</template>
