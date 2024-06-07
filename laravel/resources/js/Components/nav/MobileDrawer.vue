<script lang="ts" setup>
import { NavItem } from '@/configs/navigation';
import { NavigationMenu } from '.';
import { useDisplay } from 'vuetify';
const show = defineModel({ default: false });

defineProps<{
  navItems: NavItem[];
}>();

const { mdAndUp } = useDisplay();
</script>

<template>
  <v-navigation-drawer v-if="!mdAndUp" v-model="show" location="top" mobile>
    <NavigationMenu :items="navItems" />
  </v-navigation-drawer>
</template>
