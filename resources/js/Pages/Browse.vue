<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';
import { Image, Paginated } from '@/types';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { useToasts } from '@/store/toasts';
import axios from 'axios';
import { useRequest } from '@/composables';
import MasonrySimple from 'masonry-simple';

const props = defineProps<{ images: Paginated<Image> }>();

const scrollImages = ref(props.images.data);
const nextPage = ref(props.images.next_page_url);
const toast = useToasts();

async function loadMoreImages() {
  const res = await axios.get<Paginated<Image>>(nextPage.value);
  return new Promise((resolve) => {
    setTimeout(() => {
      resolve(res);
    }, 2000);
  });
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

const masonry = new MasonrySimple({
  container: '.masonry',
});

onMounted(() => {
  masonry.init();
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
    <div class="masonry">
      <!-- <v-card
        v-for="image in scrollImages"
        :key="image.id"
        :href="route('image-view', { id: image.id })"
        class="mb-4 masonry__item"
        @click.prevent.stop="
          $inertia.get(route('image-view', { id: image.id }))
        "
      > -->
      <v-img
        v-for="image in scrollImages"
        :key="image.id"
        class="masonry__item"
        :src="image.url"
      ></v-img>
      <!-- </v-card> -->
    </div>
    <div v-if="loading" class="d-flex justify-center">
      <v-progress-circular indeterminate color="primary" />
    </div>
  </CoreLayout>
</template>

<style lang="scss" scoped>
.masonry {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  grid-auto-flow: dense;
  grid-gap: 20px;

  @media (width >= 992px) {
    grid-template-columns: 0.8fr 1.3fr 0.7fr;
  }

  &__item {
    background-color: hsl(181 43% 77%);

    img {
      width: 100%;
      height: auto;
      object-fit: cover;
      object-position: center;
    }
  }
}
</style>
