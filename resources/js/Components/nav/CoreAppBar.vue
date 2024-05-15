<script lang="ts" setup>
import { NavTabs, SearchBar, ThemeToggle } from '@/Components';
import { useDisplay } from 'vuetify';
import Logo from '../Logo.vue';

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
    <template #prepend>
      <v-btn icon href="/" @click.prevent.stop="$inertia.get('/')">
        <Logo style="width: 40px" />
      </v-btn>
    </template>

    <v-app-bar-title v-if="mdAndUp">Blue Harvest</v-app-bar-title>

    <v-form
      v-if="searchable"
      style="width: 100%; max-width: 500px"
      class="d-flex align-center"
      @submit.prevent="$emit('searchSubmit')"
    >
      <SearchBar v-model="search" @enter="$emit('searchSubmit')" />
    </v-form>
    <v-divider v-if="mdAndUp" class="mx-2" vertical></v-divider>
    <ThemeToggle v-if="mdAndUp" />
    <v-divider vertical class="mx-2"></v-divider>
    <template #append>
      <NavTabs v-if="mdAndUp" class="mr-2" />
      <v-app-bar-nav-icon v-if="!mdAndUp" @click="drawer = !drawer" />
    </template>
  </v-app-bar>
</template>
