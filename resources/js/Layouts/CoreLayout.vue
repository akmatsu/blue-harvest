<script lang="ts" setup>
import { NavigationMenu } from '@/Components/nav';
import { ToastStack } from '@/Components';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { useRequest } from '@/composables/useRequest';

defineProps<{
  fluid?: boolean;
}>();

const drawer = ref(true);

async function logout() {
  axios.post('/logout');
}

const { exec, loading } = useRequest(logout);
</script>

<template>
  <v-app>
    <v-app-bar title="Blue Harvest" density="compact">
      <template #prepend>
        <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      </template>
      <template #append>
        <v-menu>
          <template #activator="{ props }">
            <v-btn v-bind="props" icon="mdi-account"></v-btn>
          </template>

          <v-list nav>
            <Link href="/profile" as="div">
              <v-list-item link prepend-icon="mdi-account-edit">
                View Account
              </v-list-item>
            </Link>
            <!-- <Link href="/profile" as="div"> -->
            <v-list-item prepend-icon="mdi-logout" @click="exec">
              Log out
            </v-list-item>
            <!-- </Link> -->
          </v-list>
        </v-menu>
      </template>
    </v-app-bar>

    <VNavigationDrawer v-model="drawer">
      <NavigationMenu />
    </VNavigationDrawer>

    <v-main>
      <v-container :fluid="fluid">
        <slot />
      </v-container>

      <ToastStack />
    </v-main>
  </v-app>
</template>
