<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <v-app>
    <VAppBar title="Muffins" elevation="2">
      <Link :href="route('dashboard')">
        <v-icon>$delete</v-icon>
      </Link>
    </VAppBar>
    <v-main>
      <slot />
    </v-main>
  </v-app>
</template>
