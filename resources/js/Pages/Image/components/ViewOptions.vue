<script lang="ts" setup>
import { Image } from '@/types';
import ViewOptionsListItem from './ViewOptionsListItem.vue';

defineProps<{
  image: Image;
}>();
</script>

<template>
  <v-menu>
    <template #activator="{ props }">
      <v-btn color="primary" v-bind="props" append-icon="mdi-chevron-down">
        Download
      </v-btn>
    </template>
    <v-list>
      <div v-for="img in image.optimizedImages" :key="image?.id">
        <ViewOptionsListItem v-if="img" :image="img" />
      </div>
      <v-divider></v-divider>

      <ViewOptionsListItem :image="image" />
    </v-list>
  </v-menu>
</template>
