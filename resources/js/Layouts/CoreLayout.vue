<script lang="ts" setup>
import { ToastStack, MobileDrawer, CoreAppBar } from '@/Components';

defineProps<{
  fluid?: boolean;
  searchable?: boolean;
}>();

defineEmits<{
  (e: 'searchSubmit'): void;
}>();

const search = defineModel<string>();
const drawer = ref(false);
</script>

<template>
  <v-app>
    <CoreAppBar
      v-model="drawer"
      v-model:search="search"
      :searchable="searchable"
      @search-submit="$emit('searchSubmit')"
    />
    <MobileDrawer v-model="drawer" />
    <v-main>
      <v-container :fluid="fluid">
        <slot />
      </v-container>

      <ToastStack />
    </v-main>
  </v-app>
</template>
