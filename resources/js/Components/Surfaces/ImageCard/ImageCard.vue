<script lang="ts" setup>
import { CCard } from '@/Components';
import { Image } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
  image: Image;
}>();
const page = usePage();
</script>

<template>
  <CCard :key="image.id" class="relative p-0">
    <img class="w-full" :src="image.url" />
    <p class="text-center pt-4 pb-2">{{ image.name }}</p>
    <div
      v-if="page.props.auth.user.id === image.user_id"
      class="flex gap-4 justify-end items-center"
    >
      <v-btn icon="$delete"></v-btn>
      <Link :href="route('image-view', { id: image.id })"> View </Link>
    </div>
  </CCard>
</template>
