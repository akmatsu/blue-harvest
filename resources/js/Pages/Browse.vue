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
    <v-row class="masonry">
      <v-col
        v-for="image in scrollImages"
        :key="image.id"
        class="masonry__item"
        cols="12"
        sm="6"
        md="4"
      >
        <v-card
          :href="route('image-view', { id: image.id })"
          @click.prevent.stop="
            $inertia.get(
              route('image-view', { id: image.id }),
              {},
              {
                preserveScroll: true,
                preserveState: true,
              },
            )
          "
        >
          <v-img :src="image.url" cover aspect-ratio="1"></v-img>
        </v-card>
      </v-col>
    </v-row>
    <div v-if="loading" class="d-flex justify-center">
      <v-progress-circular indeterminate color="primary" />
    </div>
  </CoreLayout>
</template>
