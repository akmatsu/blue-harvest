<script lang="ts" setup>
import { Head, Link } from '@inertiajs/vue3';
import { Image } from '@/types';
import CoreLayout from '@/Layouts/CoreLayout.vue';

defineProps<{ images: Image[] }>();
</script>

<template>
  <Head title="Browse" />
  <CoreLayout>
    <v-row>
      <v-col v-for="image in images" :key="image.id" cols="12" md="3" sm="6">
        <Link :href="route('image-view', { id: image.id })">
          <v-card link>
            <v-img :src="image.url" aspect-ratio="1"></v-img>
          </v-card>
        </Link>
      </v-col>
    </v-row>
  </CoreLayout>
</template>
