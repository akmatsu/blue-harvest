<script lang="ts" setup>
import { ToastStack, MobileDrawer, CoreAppBar } from '@/Components';

defineProps<{
  fluid?: boolean;
}>();

const drawer = ref(false);
</script>

<template>
  <v-app>
    <CoreAppBar v-model="drawer" />
    <MobileDrawer v-model="drawer" />
    <v-main>
      <v-container :fluid="fluid">
        <slot />
      </v-container>

      <ToastStack />
    </v-main>
  </v-app>
</template>
