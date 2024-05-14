<script lang="ts" setup>
import { Image } from '@/types';
import ViewOptionsListItem from './ViewOptionsListItem.vue';

defineProps<{
  image: Image;
}>();
</script>

<template>
  <v-menu open-on-hover>
    <template #activator="{ props }">
      <primary-btn v-bind="props" append-icon="mdi-chevron-down">
        Download
      </primary-btn>
    </template>
    <v-list>
      <div v-for="img in image.optimized_images" :key="image?.id">
        <ViewOptionsListItem v-if="img" :image="img" />
      </div>
      <v-divider></v-divider>

      <ViewOptionsListItem :image="image" />
    </v-list>
  </v-menu>
</template>
