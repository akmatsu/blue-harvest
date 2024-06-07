<script lang="ts" setup>
import { ToastStack, MobileDrawer, CoreAppBar } from '@/Components';
import { navigation } from '@/configs/navigation';

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
      :nav-items="navigation"
      v-model="drawer"
      v-model:search="search"
      :searchable="searchable"
      @search-submit="$emit('searchSubmit')"
    />
    <MobileDrawer v-model="drawer" :nav-items="navigation" />
    <v-main>
      <v-container :fluid="fluid">
        <slot />
      </v-container>

      <ToastStack />
    </v-main>
  </v-app>
</template>
