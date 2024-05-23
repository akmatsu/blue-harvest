<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Image } from '@/types';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { ViewOptions } from './components';
import { ImageCard, MasonryGrid, ReportDialog } from '@/Components';

const props = defineProps<{
  image: Image;
  similarImages: Image[];
}>();

onMounted(() => {
  console.log(props.similarImages);
});
</script>

<template>
  <Head title="Dashboard" />

  <CoreLayout fluid>
    <v-container class="d-flex justify-center align-center">
      <v-card width="100%">
        <v-card-actions>
          <v-spacer></v-spacer>
          <!-- <v-btn prepend-icon="mdi-flag" variant="outlined">Flag</v-btn> -->
          <ReportDialog :item-id="image.id" item-type="image" />
          <ViewOptions :image="image" />
        </v-card-actions>

        <v-img :src="image.url" max-width="100%" max-height="700px" />
        <v-card-text>
          <v-chip-group>
            <v-chip
              v-for="tag in image.tags"
              :href="route('images', { query: tag.name })"
              @click.prevent.stop="
                $inertia.get(route('images', { query: tag.name }))
              "
            >
              {{ tag.name }}
            </v-chip>
          </v-chip-group>

          <h5 class="text-h5 my-4">Similar Images</h5>
          <MasonryGrid>
            <ImageCard v-for="img in similarImages" :image="img" />
          </MasonryGrid>
        </v-card-text>
      </v-card>
    </v-container>
  </CoreLayout>
</template>
