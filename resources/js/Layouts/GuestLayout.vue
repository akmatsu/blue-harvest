<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <v-app>
    <VAppBar title="Muffins" elevation="2">
      <Link class="v-btn" href="/browse">Start Browsing</Link>
    </VAppBar>
    <v-main>
      <slot />
    </v-main>
  </v-app>
</template>
