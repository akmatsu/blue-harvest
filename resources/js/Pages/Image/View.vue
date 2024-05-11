<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Image } from '@/types';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { ViewOptions } from './components';

defineProps<{
  image: Image;
}>();
</script>

<template>
  <Head title="Dashboard" />

  <CoreLayout fluid>
    <v-container class="d-flex justify-center align-center">
      <v-card width="100%">
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            :href="route('image-edit', { id: image.id })"
            @click.prevent.stop="
              $inertia.get(route('image-edit', { id: image.id }))
            "
          >
            Customize
          </v-btn>
          <ViewOptions :image="image" />
        </v-card-actions>

        <v-img :src="image.url" max-width="100%" max-height="700px" />
        <v-card-title class="d-flex justify-space-between" style="width: 100%">
          {{ image.name }}
        </v-card-title>
      </v-card>
    </v-container>
  </CoreLayout>
</template>
