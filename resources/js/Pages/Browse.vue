<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3';
import { Image, Paginated } from '@/types';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { useToasts } from '@/store/toasts';
import axios from 'axios';
import { useRequest } from '@/composables';

const props = defineProps<{ images: Paginated<Image> }>();

const scrollImages = ref(props.images.data);
const nextPage = ref(props.images.next_page_url);
const toast = useToasts();

const search = ref<string>();

async function handleSearch() {
  return router.get(`/?query=${search.value}`, {}, { only: ['images'] });
}

async function loadMoreImages() {
  return axios.get<Paginated<Image>>(nextPage.value);
}

const { loading: searchLoading, exec: execSearch } = useRequest(handleSearch, {
  onError: (err) =>
    err.message
      ? toast.error(err.message)
      : toast.error('Something went wrong.'),
});

const { loading: pageLoading, exec } = useRequest(loadMoreImages, {
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

const loading = computed(() => searchLoading.value || pageLoading.value);

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
  <CoreLayout v-model="search" searchable @search-submit="execSearch">
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

      <v-col v-if="!scrollImages.length" cols="12" class="text-center">
        <p>Unfortunately no results matched search search. Try again.</p>
      </v-col>
      <v-col v-if="loading" class="d-flex justify-center" cols="12">
        <v-progress-circular indeterminate color="primary" />
      </v-col>
    </v-row>
  </CoreLayout>
</template>
