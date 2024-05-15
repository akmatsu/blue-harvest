<script lang="ts" setup>
import { NavTabs, ThemeToggle } from '@/Components';
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
      style="width: 100%; max-width: 500px"
      @submit.prevent="$emit('searchSubmit')"
    >
      <v-text-field
        v-if="searchable"
        v-model="search"
        hide-details
        density="compact"
        prepend-inner-icon="mdi-magnify"
        placeholder="search..."
        name="search"
      ></v-text-field>
    </v-form>
    <template #append>
      <ThemeToggle />
      <v-divider vertical class="mx-2" />

      <NavTabs v-if="mdAndUp" class="mr-2" />

      <v-app-bar-nav-icon v-if="!mdAndUp" @click="drawer = !drawer" />
    </template>
  </v-app-bar>
</template>
