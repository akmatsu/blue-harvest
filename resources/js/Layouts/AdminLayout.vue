<script lang="ts" setup>
import { CoreAppBar, MobileDrawer, ToastStack } from '@/Components';
import { adminNav } from '@/configs/navigation';

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
      :nav-items="adminNav"
      @search-submit="$emit('searchSubmit')"
    />
    <MobileDrawer :nav-items="adminNav" v-model="drawer" />
    <v-main>
      <v-container :fluid="fluid" class="app-container">
        <slot />
      </v-container>

      <ToastStack />
    </v-main>
  </v-app>
</template>

<style lang="scss">
.app-container {
  @media screen and (min-width: 1920px) {
    padding-left: 250px;
    padding-right: 250px;
  }
}
</style>
