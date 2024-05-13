<script lang="ts" setup>
import { NavTabs, UserMenu } from '@/Components';
import { usePage } from '@inertiajs/vue3';
import { RefSymbol } from '@vue/reactivity';
import { useDark, useToggle } from '@vueuse/core';
import { useDisplay, useTheme } from 'vuetify';

const { mdAndUp } = useDisplay();
const drawer = defineModel({ default: false });
const page = usePage();

const isAuth = computed(() => !!page.props.auth.user);
const theme = useTheme();

const isDark = useDark({
  onChanged(dark: boolean) {
    theme.global.name.value = dark ? 'dark' : 'light';
  },
});

const toggleDark = useToggle(isDark);
</script>

<template>
  <v-app-bar>
    <v-btn href="/" @click.prevent.stop="$inertia.get('/')">
      <v-app-bar-title>Blue Harvest</v-app-bar-title>
    </v-btn>
    <template #append>
      <v-app-bar-nav-icon v-if="!mdAndUp" @click="drawer = !drawer" />
      <v-btn @click="toggleDark()">Change Theme</v-btn>
      <!-- <v-switch
        value="isDark"
        label="Toggle Theme"
        hide-details
        @click="toggleDark()"
      ></v-switch> -->
      <NavTabs v-if="mdAndUp" class="mr-2" />
      <UserMenu v-if="isAuth && mdAndUp" />
      <primary-btn
        v-else
        prepend-icon="mdi-account-plus"
        href="/register"
        @click.prevent.stop="$inertia.get('/register')"
      >
        Register
      </primary-btn>
    </template>
  </v-app-bar>
</template>
