<script lang="ts" setup>
import { navigation, NavItem } from '@/configs/navigation';
import { usePage } from '@inertiajs/vue3';
import { ThemeToggle } from '@/Components';

defineProps<{
  items: NavItem[];
}>();
const page = usePage();
const isAuth = computed(() => !!page.props.auth.user);
const isAdmin = computed(
  () => !!page.props.auth.user?.roles?.find((role) => role.name === 'admin'),
);
</script>

<template>
  <v-list nav>
    <div
      v-for="item in items"
      :key="item.to"
      :class="[{ 'd-none': item.requireAuth && !isAuth }]"
    >
      <v-list-item
        v-if="!item.requireAuth || isAuth"
        link
        :href="item.to"
        :prepend-icon="item.icon"
        :active="$page.url.startsWith(item.to)"
        :title="item.title"
        @click.prevent.stop="$inertia.get(item.to)"
      />
    </div>
    <v-list-item
      v-if="isAuth"
      href="/profile"
      prepend-icon="mdi-account-edit"
      :active="$page.url === '/profile'"
      @click.prevent.stop="$inertia.get('/profile')"
    >
      <v-list-item-title> View Account </v-list-item-title>
    </v-list-item>

    <v-list-item
      v-if="isAuth && isAdmin"
      href="/profile"
      prepend-icon="mdi-account-edit"
      :active="$page.url === '/profile'"
      @click.prevent.stop="$inertia.get('/profile')"
    >
      <v-list-item-title> Admin View</v-list-item-title>
    </v-list-item>

    <v-list-item
      v-if="isAuth"
      prepend-icon="mdi-logout"
      href="/logout"
      @click.prevent.stop="$inertia.post('/logout')"
    >
      <v-list-item-title>Log out</v-list-item-title>
    </v-list-item>

    <v-list-item
      v-else
      prepend-icon="mdi-account-plus"
      href="/register"
      title="Register"
      @click.prevent.stop="$inertia.get('/register')"
    />
    <ThemeToggle>
      <template #activator="{ props, isDark, icon }">
        <v-list-item v-bind="props" :prepend-icon="icon">
          {{ isDark ? 'Switch to light theme' : 'Switch to dark theme' }}
        </v-list-item>
      </template>
    </ThemeToggle>
  </v-list>
</template>
