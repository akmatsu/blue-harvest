<script setup lang="ts">
import { ToastStack } from '@/Components';
</script>

<template>
  <v-app>
    <v-main style="min-height: 100vh">
      <v-container
        style="height: 100%"
        class="d-flex flex-column justify-center align-center"
      >
        <slot />
        <secondary-btn
          class="mt-4"
          prepend-icon="mdi-arrow-left"
          href="/"
          @click.prevent.stop="$inertia.get('/')"
        >
          Back to App
        </secondary-btn>
      </v-container>
    </v-main>

    <ToastStack />
  </v-app>
</template>
