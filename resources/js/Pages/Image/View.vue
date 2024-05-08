<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Image } from '@/types';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { ViewOptions } from './components';

const props = defineProps<{
  image: Image;
}>();

onMounted(() => {
  console.log(props.image);
});
</script>

<template>
  <Head title="Dashboard" />

  <CoreLayout fluid>
    <v-container class="d-flex justify-center align-center">
      <v-card width="100%">
        <v-card-title class="d-flex justify-space-between" style="width: 100%">
          <span>
            {{ image.name }}
          </span>
          <div class="d-flex" style="gap: 8px">
            <Link :href="route('image-edit', { id: image.id })">
              <v-btn color="primary">Edit</v-btn>
            </Link>
            <ViewOptions :image="image" />
          </div>
        </v-card-title>

        <v-card-text>
          <v-img :src="image.url" max-width="100%" max-height="500px" />
        </v-card-text>
      </v-card>
    </v-container>
  </CoreLayout>
</template>
