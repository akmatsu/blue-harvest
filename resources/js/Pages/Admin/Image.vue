<script lang="ts" setup>
import { AdminLayout } from '@/Layouts';
import { Image } from '@/types';

defineProps<{
  image: Image;
}>();
</script>

<template>
  <AdminLayout>
    <v-card :title="image.name"></v-card>
  </AdminLayout>
</template>
