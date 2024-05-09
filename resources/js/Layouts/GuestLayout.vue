<script setup lang="ts">
import { ToastStack } from '@/Components';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <v-app>
    <VAppBar title="Muffins" elevation="2">
      <Link :href="route('browse-images')" as="div">
        <v-btn>Start Browsing</v-btn>
      </Link>
    </VAppBar>
    <v-main>
      <v-container>
        <slot />
      </v-container>
    </v-main>

    <ToastStack />
  </v-app>
</template>
