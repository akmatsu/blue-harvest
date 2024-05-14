<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';
import { Image, Paginated } from '@/types';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { useToasts } from '@/store/toasts';
import axios from 'axios';
import { useRequest } from '@/composables';

const props = defineProps<{ images: Paginated<Image> }>();

const scrollImages = ref(props.images.data);
const nextPage = ref(props.images.next_page_url);
const toast = useToasts();

async function loadMoreImages() {
  return axios.get<Paginated<Image>>(nextPage.value);
}

const { loading, exec } = useRequest(loadMoreImages, {
  onSuccess: (res) => {
    if (res) {
      scrollImages.value.push(...res.data.data);
      nextPage.value = res.data.next_page_url;
    }
  },
  onError: (err) =>
    err.message
      ? toast.error(err.message)
      : toast.error('Something went wrong'),
});

onMounted(() => {
  loading.value = false;
  window.addEventListener('scroll', handleScroll);
});

function handleScroll() {
  const bottomOfWindow =
    window.innerHeight + window.scrollY >=
    document.documentElement.scrollHeight - 100; // Add some buffer

  if (bottomOfWindow && !loading.value && nextPage.value) {
    exec();
  }
}
</script>

<template>
  <Head title="Browse" />
  <CoreLayout>
    <div class="masonry-layout">
      <v-card
        v-for="image in scrollImages"
        :key="image.id"
        :href="route('image-view', { id: image.id })"
        class="mb-4"
        @click.prevent.stop="
          $inertia.get(route('image-view', { id: image.id }))
        "
      >
        <v-img :src="image.url" width="100%"></v-img>
      </v-card>
    </div>
  </CoreLayout>
</template>

<style lang="scss" scoped>
.masonry-layout {
  column-count: 1;
  gap: 1rem;
  @media screen and (min-width: 500px) {
    column-count: 2;
  }

  @media screen and (min-width: 1000px) {
    column-count: 3;
  }
}
</style>
