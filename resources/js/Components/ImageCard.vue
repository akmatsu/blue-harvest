<script lang="ts" setup>
import { Image } from '@/types';
import { LinkCard } from './containers';

const props = defineProps<{
  image: Image;
}>();

const url = computed(() => {
  if (props.image.optimized_images) {
    const img = props.image.optimized_images?.find(
      (im) => im.size === 'medium',
    );
    if (img) return img?.url;
  }
  return props.image.url;
});

function getQuery(tag: string) {
  return `tag:"${tag}"`;
}
</script>

<template>
  <LinkCard link="images.view" :params="{ id: image.id }" preserve-state>
    <v-img :src="url" cover :aspect-ratio="image.width / image.height"></v-img>
    <v-card-text v-if="image.tags?.length" class="px-2 py-0">
      <v-chip-group column>
        <v-chip
          size="small"
          v-for="tag in image.tags"
          :href="route('index', { query: getQuery(tag.name) })"
          @click.prevent.stop="
            $inertia.get(route('index', { query: getQuery(tag.name) }))
          "
        >
          {{ tag.name }}
        </v-chip>
      </v-chip-group>
    </v-card-text>
  </LinkCard>
</template>
