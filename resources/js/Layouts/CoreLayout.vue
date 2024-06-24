<script lang="ts" setup>
import { CoreAppBar, MobileDrawer, ToastStack } from '@/Components';
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
      <v-container class="app-container">
        <slot />
      </v-container>

      <ToastStack />
    </v-main>
  </v-app>
</template>

<style lang="scss">
div.v-container.app-container {
  @media screen and (min-width: 1920px) {
    padding-left: 250px;
    padding-right: 250px;
  }
}
</style>
