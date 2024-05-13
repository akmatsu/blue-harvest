<script lang="ts" setup>
import { NavTabs, ThemeToggle } from '@/Components';
import { useDisplay } from 'vuetify';

const { mdAndUp } = useDisplay();
const drawer = defineModel({ default: false });
</script>

<template>
  <v-app-bar density="compact">
    <v-btn href="/" @click.prevent.stop="$inertia.get('/')">
      <v-app-bar-title>Blue Harvest</v-app-bar-title>
    </v-btn>
    <template #append>
      <ThemeToggle />
      <v-divider vertical class="mx-2" />

      <NavTabs v-if="mdAndUp" class="mr-2" />

      <v-app-bar-nav-icon v-if="!mdAndUp" @click="drawer = !drawer" />
    </template>
  </v-app-bar>
</template>
