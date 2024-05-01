<script lang="ts" setup>
import { NavigationMenu } from '@/Components/nav';
import { ToastStack } from '@/Components';
import { useToasts } from '@/store/toasts';
defineProps<{
  fluid?: boolean;
}>();

const drawer = ref(false);
const toast = useToasts();
</script>

<template>
  <v-app>
    <v-app-bar title="Blue Harvest" elevation="2">
      <template #prepend>
        <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      </template>
    </v-app-bar>

    <VNavigationDrawer v-model="drawer">
      <NavigationMenu />
    </VNavigationDrawer>

    <v-main>
      <v-container :fluid="fluid">
        <slot />
        <v-btn @click="toast.show('test!')">CLICK ME!</v-btn>
      </v-container>
      <ToastStack />
    </v-main>
  </v-app>
</template>
