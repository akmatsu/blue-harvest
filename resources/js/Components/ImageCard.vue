<script lang="ts" setup>
import { Image } from '@/types';
import { LinkCard } from './containers';

defineProps<{
  image: Image;
}>();
</script>

<template>
  <LinkCard link="images.view" :params="{ id: image.id }" preserve-state>
    <v-img
      :src="image.url"
      cover
      :aspect-ratio="image.width / image.height"
    ></v-img>
    <v-card-text v-if="image.tags" class="px-2 py-0">
      <v-chip-group column>
        <v-chip
          size="small"
          v-for="tag in image.tags"
          :href="route('images', { query: tag.name })"
          @click.prevent="$inertia.get(route('images', { query: tag.name }))"
        >
          {{ tag.name }}
        </v-chip>
      </v-chip-group>
    </v-card-text>
  </LinkCard>
</template>
