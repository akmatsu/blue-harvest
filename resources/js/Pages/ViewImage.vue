<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Image } from '@/types';
import CoreLayout from '@/Layouts/CoreLayout.vue';

defineProps<{
  image: Image;
}>();
</script>

<template>
  <Head title="Dashboard" />

  <CoreLayout fluid>
    <v-container class="d-flex justify-center align-center">
      <v-card width="100%">
        <div class="d-flex justify-space-between">
          <v-card-title
            class="d-flex justify-space-between"
            style="width: 100%"
          >
            <span>
              {{ image.name }}
            </span>
            <v-menu>
              <template #activator="{ props }">
                <v-btn
                  color="primary"
                  v-bind="props"
                  append-icon="mdi-chevron-down"
                >
                  Download
                </v-btn>
              </template>
              <v-list>
                <v-list-item>Small</v-list-item>
                <v-list-item>Medium</v-list-item>
                <v-list-item>Large</v-list-item>
                <v-divider></v-divider>
                <v-list-item @click="">
                  <v-list-item-title
                    class="d-flex align-center"
                    style="gap: 8px"
                  >
                    Original
                    <span class="text-caption">
                      ({{ image.width }} x {{ image.height }})
                    </span>
                  </v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </v-card-title>
        </div>
        <v-img :src="image.url" max-width="100%" max-height="500px" />
      </v-card>
    </v-container>
  </CoreLayout>
</template>
