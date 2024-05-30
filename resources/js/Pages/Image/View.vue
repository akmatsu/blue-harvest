<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { Image } from '@/types';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { ViewOptions } from './components';
import { ImageCard, LinkBtn, MasonryGrid, ReportDialog } from '@/Components';

const props = defineProps<{
  image: Image;
  similarImages: Image[];
}>();

const page = usePage();

const url = computed(() => {
  if (props.image.optimized_images) {
    const img = props.image.optimized_images.find((im) => im.size === 'large');
    if (img) return img?.url;
  }
  return props.image.url;
});

const userIsOwner = computed(
  () => page.props.auth?.user?.id === props.image.user_id,
);

const userIsAdmin = computed(
  () => !!page.props.auth?.user?.roles?.find((r) => r.name === 'admin'),
);
</script>

<template>
  <Head :title="image.name" />

  <CoreLayout>
    <v-card>
      <v-card-actions>
        <v-spacer></v-spacer>
        <LinkBtn link="admin.images.show" :params="{ id: image.id }">
          Admin View
        </LinkBtn>
        <LinkBtn
          link="images.manageImage"
          :params="{ id: image.id }"
          v-if="userIsOwner"
        >
          Manage Image
        </LinkBtn>
        <ReportDialog :item-id="image.id" item-type="App\Models\Image" />
        <ViewOptions :image="image" />
      </v-card-actions>
      <div class="d-flex flex-column px-2 mb-4" style="gap: 8px">
        <v-alert
          v-for="r in image.restrictions"
          color="warning"
          density="compact"
        >
          {{ r.description }}
        </v-alert>
      </div>
      <v-img :src="url" max-width="100%" max-height="700px" />
      <v-card-text>
        <v-chip-group>
          <v-chip
            v-for="tag in image.tags"
            :href="route('index', { query: tag.name })"
            @click.prevent.stop="
              $inertia.get(route('index', { query: tag.name }))
            "
          >
            {{ tag.name }}
          </v-chip>
        </v-chip-group>
        <v-divider></v-divider>
        <h5 class="text-h5 my-4">Similar Images</h5>
        <MasonryGrid>
          <ImageCard v-for="img in similarImages" :image="img" />
        </MasonryGrid>
      </v-card-text>
    </v-card>
  </CoreLayout>
</template>
