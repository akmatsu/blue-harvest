<script lang="ts" setup>
import { NavTabs, SearchBar, ThemeToggle } from '@/Components';
import { useDisplay } from 'vuetify';

defineProps<{
  searchable?: boolean;
}>();

defineEmits<{
  (e: 'searchSubmit'): void;
}>();

const { mdAndUp } = useDisplay();
const drawer = defineModel({ default: false });
const search = defineModel<string>('search');
</script>

<template>
  <v-app-bar density="compact">
    <v-btn href="/" @click.prevent.stop="$inertia.get('/')">
      <v-app-bar-title>Blue Harvest</v-app-bar-title>
    </v-btn>
    <v-form
      v-if="searchable"
      style="width: 100%; max-width: 500px"
      class="d-flex align-center"
      @submit.prevent="$emit('searchSubmit')"
    >
      <SearchBar v-model="search" @enter="$emit('searchSubmit')" />
    </v-form>
    <template #append>
      <ThemeToggle />
      <v-divider vertical class="mx-2" />

      <NavTabs v-if="mdAndUp" class="mr-2" />

      <v-app-bar-nav-icon v-if="!mdAndUp" @click="drawer = !drawer" />
    </template>
  </v-app-bar>
</template>
