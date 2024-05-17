<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3';
import { Image, Paginated } from '@/types';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { useToasts } from '@/store/toasts';
import { useRequest } from '@/composables';

const props = defineProps<{ images: Paginated<Image> }>();

const scrollImages = ref(props.images.data);

const toast = useToasts();
const page = ref(props.images.current_page);

const search = ref<string>();

async function handleSearch() {
  return router.get('/', { query: search.value }, { only: ['images'] });
}

const { exec: execSearch } = useRequest(handleSearch, {
  onError: (err) =>
    err.message
      ? toast.error(err.message)
      : toast.error('Something went wrong.'),
});

// function handlePage(value: number) {}

const totalPages = computed(() =>
  Math.ceil(props.images.total / props.images.per_page),
);
</script>

<template>
  <Head title="Browse" />
  <CoreLayout v-model="search" searchable @search-submit="execSearch">
    <div class="masonry px-lg-12 mb-4">
      <v-card
        v-for="image in scrollImages"
        :key="image.id"
        :href="route('image-view', { id: image.id })"
        class="mb-4"
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
        <v-img
          :src="image.url"
          cover
          :aspect-ratio="image.width / image.height"
        ></v-img>
      </v-card>
    </div>
    <div class="d-flex justify-center">
      <v-pagination
        v-model="page"
        :length="totalPages"
        density="compact"
        show-first-last-page
        size="small"
        total-visible="6"
      >
        <template #item="{ isActive, page, props }">
          <v-btn
            v-bind="props"
            variant="text"
            size="small"
            density="compact"
            :disabled="isActive"
            :active="isActive"
            :href="route('browse-images', { page, query: search })"
            @click.prevent="
              $inertia.get(
                route('browse-images', {
                  page: page,
                  query: search,
                }),
              )
            "
          >
            {{ page }}
          </v-btn>
        </template>
        <template
          #first="{
            disabled,
            icon,
            'aria-label': ariaLabel,
            'aria-disabled': ariaDisabled,
          }"
        >
          <v-btn
            :icon="icon"
            variant="text"
            density="compact"
            size="small"
            :disabled="disabled"
            :aria-label="ariaLabel"
            :aria-disabled="ariaDisabled"
            :href="route('browse-images', { page: 1 })"
            @click.prevent="
              $inertia.get(route('browse-images', { page: 1, query: search }))
            "
          ></v-btn>
        </template>
        <template
          #last="{
            disabled,
            icon,
            'aria-label': ariaLabel,
            'aria-disabled': ariaDisabled,
          }"
        >
          <v-btn
            :icon="icon"
            variant="text"
            density="compact"
            size="small"
            :disabled="disabled"
            :aria-label="ariaLabel"
            :aria-disabled="ariaDisabled"
            :href="
              route('browse-images', { page: images.last_page, query: search })
            "
            @click.prevent="
              $inertia.get(
                route('browse-images', {
                  page: images.last_page,
                  query: search,
                }),
              )
            "
          ></v-btn>
        </template>
        <template
          #next="{
            disabled,
            icon,
            'aria-label': ariaLabel,
            'aria-disabled': ariaDisabled,
          }"
        >
          <v-btn
            :icon="icon"
            variant="text"
            density="compact"
            size="small"
            :disabled="disabled"
            :aria-label="ariaLabel"
            :aria-disabled="ariaDisabled"
            :href="
              route('browse-images', {
                page: images.current_page + 1,
                query: search,
              })
            "
            @click.prevent="
              $inertia.get(
                route('browse-images', {
                  page: images.current_page + 1,
                  query: search,
                }),
              )
            "
          ></v-btn>
        </template>
        <template
          #prev="{
            disabled,
            icon,
            'aria-label': ariaLabel,
            'aria-disabled': ariaDisabled,
          }"
        >
          <v-btn
            :icon="icon"
            variant="text"
            density="compact"
            size="small"
            :disabled="disabled"
            :aria-label="ariaLabel"
            :aria-disabled="ariaDisabled"
            :href="
              route('browse-images', {
                page: images.current_page - 1,
                query: search,
              })
            "
            @click.prevent="
              $inertia.get(
                route('browse-images', {
                  page: images.current_page - 1,
                  query: search,
                }),
              )
            "
          ></v-btn>
        </template>
      </v-pagination>
    </div>
  </CoreLayout>
</template>

<style lang="scss" scoped>
.masonry {
  column-count: 1;
  column-gap: 1rem;

  @media screen and (min-width: 600px) {
    column-count: 2;
  }

  @media screen and (min-width: 960px) {
    column-count: 3;
  }
}
</style>
